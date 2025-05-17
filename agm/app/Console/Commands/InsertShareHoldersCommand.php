<?php

namespace App\Console\Commands;

use App\Models\Customer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;

class InsertShareHoldersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:insert-shareholders {file : Đường dẫn đến file Excel} {--password=123456 : Mật khẩu mặc định}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import danh sách cổ đông từ file Excel vào bảng customers';

    /**
     * Ánh xạ cột từ Excel sang database
     */
    protected $columnMapping = [
        'MA_CO_DONG' => 'ma_co_dong',
        'HO_TEN' => 'name',
        'SO_DKSH' => 'vn_id',
        'DIA_CHI_LIEN_HE' => 'address',
        'EMAIL' => 'email',
        'DIEN_THOAI' => 'phone',
        'SO_CP' => 'co_phan_so_huu',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Thêm thời gian bắt đầu
        $startTime = microtime(true);
        
        $this->info('Bắt đầu import danh sách cổ đông...');
        $filePath = $this->argument('file');
        $defaultPassword = $this->option('password');

        // Kiểm tra file tồn tại
        if (!file_exists($filePath)) {
            $this->error("File không tồn tại: {$filePath}");
            return 1;
        }

        try {
            // Đọc file Excel
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            // Lấy header và xác định vị trí các cột
            $header = array_map('trim', $rows[1]);
            $this->info('Header từ Excel: ' . implode(', ', $header));
            $columnIndexes = $this->mapColumnIndexes($header);

            // Kiểm tra xem có đủ các cột cần thiết không
            $missingColumns = array_diff(array_keys($this->columnMapping), array_keys($columnIndexes));
            if (!empty($missingColumns)) {
                $this->error('Thiếu các cột: ' . implode(', ', $missingColumns));
                return 1;
            }

            $this->info('Đã tìm thấy các cột: ' . implode(', ', array_keys($columnIndexes)));
            $this->newLine();

            // Khởi tạo biến đếm
            $total = count($rows) - 2; // Trừ dòng header
            $success = 0;
            $skipped = 0;
            $failed = 0;

            $this->output->progressStart($total);
            DB::table('customers')->truncate();
            DB::table('customers')->insert([
                'name' => 'Nguyễn Mạnh Dũng',
                'vn_id' => '001200006553', 
                'email' => 'mrdung20005b@gmail.com',
                'phone' => '0366065647',
                'gender' => 'male',
                'address' => 'Hanoi',
                'password' => Hash::make('dungnmzxje'), 
                'is_active' => 1, 
                'created_at' => now(),
                'updated_at' => null,
            ]);
            // Import dữ liệu
            DB::beginTransaction();

            // Batch size để insert nhiều records cùng lúc
            $batchSize = 500;
            $batch = [];

            for ($i = 1; $i < count($rows)-2; $i++) {
                $row = $rows[$i];

                // Bỏ qua dòng trống
                if (empty(array_filter($row))) {
                    $skipped++;
                    $this->output->progressAdvance();
                    continue;
                }

                try {
                    // Chuẩn bị dữ liệu
                    $data = [];
                    foreach ($this->columnMapping as $excelColumn => $dbColumn) {
                        if (isset($columnIndexes[$excelColumn])) {
                            $index = $columnIndexes[$excelColumn];
                            $value = $row[$index] ?? null;

                            // Xử lý các kiểu dữ liệu đặc biệt
                            if ($dbColumn === 'co_phan_so_huu' && $value !== null) {
                                $value = (int) $value;
                            }
                            
                            // Xử lý cột co_dong_noi_bo
                            if ($dbColumn === 'co_dong_noi_bo') {
                                $value = !empty($value) ? 1 : 0;
                            }

                            $data[$dbColumn] = $value;
                        }
                    }

                    // Thêm các trường bắt buộc
                    $data['password'] = Hash::make($defaultPassword);
                    $data['is_active'] = 0;
                    $data['created_at'] = now();
                    $data['updated_at'] = now();

                    // Validate dữ liệu
                    $validator = Validator::make($data, [
                        'name' => 'required|string|max:100',
                        'vn_id' => 'required|string|max:20',
                        'email' => 'nullable|email|max:100',
                        'phone' => 'nullable|string|max:20',
                    ]);

                    if ($validator->fails()) {
                        throw new \Exception("Lỗi validation: " . implode(', ', $validator->errors()->all()));
                    }

                    // Kiểm tra trùng lặp
                    $existingCustomer = Customer::where('vn_id', $data['vn_id'])->first();

                    if ($existingCustomer) {
                        // Cập nhật thông tin
                        $existingCustomer->update($data);
                        $this->info("Dòng {$i}: Cập nhật cổ đông " . $data['name'] . " (CCCD: " . $data['vn_id'] . ")");
                    } else {
                        // Thêm vào batch
                        $batch[] = $data;

                        // Khi đủ batch size thì insert
                        if (count($batch) >= $batchSize) {
                            Customer::insert($batch);
                            $this->info("Đã import " . count($batch) . " records");
                            $success += count($batch);
                            $batch = [];
                        }
                    }

                } catch (\Exception $e) {
                    $failed++;
                    $this->error("Lỗi ở dòng {$i}: " . $e->getMessage());
                    Log::error("Lỗi import cổ đông dòng {$i}: " . $e->getMessage());
                }

                $this->output->progressAdvance();
            }

            // Insert những records còn lại trong batch cuối
            if (!empty($batch)) {
                Customer::insert($batch);
                $success += count($batch);
            }

            $this->output->progressFinish();

            DB::commit();

            // Tính thời gian thực thi
            $endTime = microtime(true);
            $executionTime = $endTime - $startTime;
            $minutes = floor($executionTime / 60);
            $seconds = $executionTime % 60;

            // Hiển thị kết quả
            $this->newLine();
            $this->info("=== KẾT QUẢ IMPORT ===");
            $this->info("Tổng số dòng: {$total}");
            $this->info("Import thành công: {$success}");
            $this->info("Bỏ qua (dòng trống): {$skipped}");
            $this->info("Lỗi: {$failed}");
            $this->info(sprintf("Thời gian thực thi: %d phút %.2f giây", $minutes, $seconds));

            if ($failed > 0) {
                $this->warn("Xem chi tiết lỗi trong log hoặc trong output ở trên.");
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Lỗi: " . $e->getMessage());
            Log::error("Lỗi import cổ đông: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Ánh xạ index của các cột trong file Excel
     *
     * @param array $header
     * @return array
     */
    protected function mapColumnIndexes(array $header): array
    {
        $columnIndexes = [];

        foreach ($header as $index => $columnName) {
            $columnName = trim($columnName);
            if (in_array($columnName, array_keys($this->columnMapping))) {
                $columnIndexes[$columnName] = $index;
            }
        }

        return $columnIndexes;
    }
}
