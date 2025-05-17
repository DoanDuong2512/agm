<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Authority;
use App\Models\Customer;
use Illuminate\Http\Request;
use Modules\CMS\App\Services\PageTitleService;

class AuthorityController extends Controller
{
    public function index(Request $request)
    {
        PageTitleService::setTitle('Quản lý ủy quyền');
        $perPage = (int) $request->input('per_page', 10);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;

        $query = Authority::with(['authorizer', 'authorized']);

        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('authorizer', function($q) use ($search) {
                $q->where('ma_co_dong', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })
            ->orWhereHas('authorized', function($q) use ($search) {
                $q->where('ma_co_dong', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            })
            ->orWhere('ten_nguoi_duoc_uy_quyen', 'like', "%{$search}%");
        }

        $authorities = $query->orderBy('id', 'desc')
                           ->paginate($perPage)
                           ->appends($request->all());

        return view('cms::authority.index', compact('authorities', 'perPage'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nguoi_uy_quyen' => 'required|exists:customers,id',
            'nguoi_duoc_uy_quyen' => 'required_if:is_shareholder,1|exists:customers,id',
            'co_phan_uy_quyen' => 'required|integer|min:0',
            'is_shareholder' => 'required|boolean',
            'ten_nguoi_duoc_uy_quyen' => 'required_if:is_shareholder,0|string|max:255',
            'vn_id' => 'required_if:is_shareholder,0|string|max:20',
            'vn_id_issue_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        // Kiểm tra người nhận ủy quyền không được giống người ủy quyền
        if ($request->is_shareholder == 1 && $request->nguoi_uy_quyen == $request->nguoi_duoc_uy_quyen) {
            return response()->json([
                'errors' => [
                    'nguoi_duoc_uy_quyen' => ['Người nhận ủy quyền không thể giống với người ủy quyền']
                ]
            ], 422);
        }

        // Kiểm tra người ủy quyền đã được người khác ủy quyền chưa
        $hasBeenAuthorized = Authority::where('nguoi_duoc_uy_quyen', $request->nguoi_uy_quyen)
            ->where('is_shareholder', 1)
            ->exists();

        if ($hasBeenAuthorized) {
            return response()->json([
                'errors' => [
                    'nguoi_uy_quyen' => ['Người ủy quyền đã được người khác ủy quyền. Không thể ủy quyền cho người khác.']
                ]
            ], 422);
        }

        // Lấy thông tin người ủy quyền
        $authorizer = Customer::findOrFail($request->nguoi_uy_quyen);

        // Tính tổng số cổ phần đã ủy quyền hiện tại
        $totalDelegatedShares = Authority::where('nguoi_uy_quyen', $request->nguoi_uy_quyen)->sum('co_phan_uy_quyen');

        // Kiểm tra số cổ phần ủy quyền có hợp lệ không
        $availableShares = $authorizer->co_phan_so_huu - $totalDelegatedShares;

        if ($request->co_phan_uy_quyen > $availableShares) {
            return response()->json([
                'errors' => [
                    'co_phan_uy_quyen' => ["Số cổ phần ủy quyền không thể vượt quá số cổ phần có thể ủy quyền ($availableShares)"]
                ]
            ], 422);
        }

        // Kiểm tra xem đã tồn tại bản ghi ủy quyền với cặp tương ứng chưa
        $query = Authority::where('nguoi_uy_quyen', $request->nguoi_uy_quyen);

        if ($request->is_shareholder == 1) {
            // Trường hợp người nhận là cổ đông, kiểm tra theo nguoi_duoc_uy_quyen
            $existingAuthority = $query->where('nguoi_duoc_uy_quyen', $request->nguoi_duoc_uy_quyen)
                ->where('is_shareholder', 1)
                ->first();

            if ($existingAuthority) {
                return response()->json([
                    'errors' => [
                        'nguoi_duoc_uy_quyen' => ['Đã tồn tại bản ghi ủy quyền với người ủy quyền và người nhận ủy quyền này']
                    ]
                ], 422);
            }
        } else {
            // Trường hợp người nhận không phải cổ đông, kiểm tra theo thông tin cá nhân
            $existingAuthority = $query->where('ten_nguoi_duoc_uy_quyen', $request->ten_nguoi_duoc_uy_quyen)
                ->where('vn_id', $request->vn_id)
                ->where('is_shareholder', 0)
                ->first();

            if ($existingAuthority) {
                return response()->json([
                    'errors' => [
                        'ten_nguoi_duoc_uy_quyen' => ['Đã tồn tại bản ghi ủy quyền với người ủy quyền và thông tin người nhận ủy quyền này']
                    ]
                ], 422);
            }
        }

        // Nếu is_shareholder=1, lấy thông tin từ customer và điền vào các trường
        if ($request->is_shareholder == 1 && $request->filled('nguoi_duoc_uy_quyen')) {
            $customer = Customer::findOrFail($request->nguoi_duoc_uy_quyen);
            $validated['ten_nguoi_duoc_uy_quyen'] = $customer->name;
            $validated['vn_id'] = $customer->vn_id;
            $validated['vn_id_issue_date'] = $customer->vn_id_issue_date;
            $validated['address'] = $customer->address;
        }

        // Lưu bản ghi ủy quyền
        Authority::create($validated);

        // Cập nhật thông tin của người nhận ủy quyền (nếu là cổ đông)
        if ($request->is_shareholder == 1 && $request->filled('nguoi_duoc_uy_quyen')) {
            $recipient = Customer::findOrFail($request->nguoi_duoc_uy_quyen);

            // Cập nhật tổng số cổ phần được ủy quyền
            $totalSharesReceived = Authority::where('nguoi_duoc_uy_quyen', $recipient->id)
                                           ->where('is_shareholder', 1)
                                           ->sum('co_phan_uy_quyen');

            // Tổng cổ phần được ủy quyền trừ cổ đông nội bộ
            $totalSharesReceivedExcludingInternal = Authority::where('nguoi_duoc_uy_quyen', $recipient->id)
                ->where('is_shareholder', 1)
                ->whereHas('authorizer', function($query) {
                    $query->where('co_dong_noi_bo', '!=', 1);
                })
                ->sum('co_phan_uy_quyen');

            // Đếm số người đã ủy quyền cho cổ đông này
            $totalDelegators = Authority::where('nguoi_duoc_uy_quyen', $recipient->id)
                                        ->where('is_shareholder', 1)
                                        ->count();

            // Cập nhật thông tin cổ đông
            $recipient->update([
                'tong_co_phan_duoc_uy_quyen' => $totalSharesReceived,
                'tong_co_phan_duoc_uy_quyen_tru_noi_bo' => $totalSharesReceivedExcludingInternal,
                'tong_so_co_dong_uy_quyen' => $totalDelegators,
            ]);
        }

        // Cập nhật thông tin của người ủy quyền
        // Tính lại tổng số cổ phần đã ủy quyền
        $totalDelegatedShares = Authority::where('nguoi_uy_quyen', $authorizer->id)
                                        ->sum('co_phan_uy_quyen');

        // Cập nhật thông tin người ủy quyền
        $authorizer->update([
            'co_phan_sau_uy_quyen' => $authorizer->co_phan_so_huu - $totalDelegatedShares
        ]);

        return response()->json([
            'message' => 'Ủy quyền đã được tạo thành công.',
            'redirect' => route('cms.authority.index')
        ]);
    }

    public function edit(Authority $authority)
    {
        $authority->load(['authorizer', 'authorized']);
        return response()->json($authority);
    }

    public function update(Request $request, Authority $authority)
    {
        $validated = $request->validate([
            'nguoi_uy_quyen' => 'required|exists:customers,id',
            'nguoi_duoc_uy_quyen' => 'required_if:is_shareholder,1|exists:customers,id',
            'co_phan_uy_quyen' => 'required|integer|min:0',
            'is_shareholder' => 'required|boolean',
            'ten_nguoi_duoc_uy_quyen' => 'required_if:is_shareholder,0|string|max:255',
            'vn_id' => 'required_if:is_shareholder,0|string|max:20',
            'vn_id_issue_date' => 'nullable|date',
            'address' => 'nullable|string|max:255',
        ]);

        // Kiểm tra người nhận ủy quyền không được giống người ủy quyền
        if ($request->is_shareholder == 1 && $request->nguoi_uy_quyen == $request->nguoi_duoc_uy_quyen) {
            return response()->json([
                'errors' => [
                    'nguoi_duoc_uy_quyen' => ['Người nhận ủy quyền không thể giống với người ủy quyền']
                ]
            ], 422);
        }

        // Kiểm tra người ủy quyền đã được người khác ủy quyền chưa (loại trừ bản ghi hiện tại)
        if ($request->nguoi_uy_quyen != $authority->nguoi_uy_quyen) {
            $hasBeenAuthorized = Authority::where('nguoi_duoc_uy_quyen', $request->nguoi_uy_quyen)
                ->where('is_shareholder', 1)
                ->exists();

            if ($hasBeenAuthorized) {
                return response()->json([
                    'errors' => [
                        'nguoi_uy_quyen' => ['Người ủy quyền đã được người khác ủy quyền. Không thể ủy quyền cho người khác.']
                    ]
                ], 422);
            }
        }

        // Kiểm tra xem đã tồn tại bản ghi ủy quyền với cặp tương ứng chưa
        $query = Authority::where('nguoi_uy_quyen', $request->nguoi_uy_quyen)
                          ->where('id', '!=', $authority->id); // Loại trừ bản ghi hiện tại

        if ($request->is_shareholder == 1) {
            // Trường hợp người nhận là cổ đông, kiểm tra theo nguoi_duoc_uy_quyen
            $existingAuthority = $query->where('nguoi_duoc_uy_quyen', $request->nguoi_duoc_uy_quyen)
                ->where('is_shareholder', 1)
                ->first();

            if ($existingAuthority) {
                return response()->json([
                    'errors' => [
                        'nguoi_duoc_uy_quyen' => ['Đã tồn tại bản ghi ủy quyền với người ủy quyền và người nhận ủy quyền này']
                    ]
                ], 422);
            }
        } else {
            // Trường hợp người nhận không phải cổ đông, kiểm tra theo thông tin cá nhân
            $existingAuthority = $query->where('ten_nguoi_duoc_uy_quyen', $request->ten_nguoi_duoc_uy_quyen)
                ->where('vn_id', $request->vn_id)
                ->where('is_shareholder', 0)
                ->first();

            if ($existingAuthority) {
                return response()->json([
                    'errors' => [
                        'ten_nguoi_duoc_uy_quyen' => ['Đã tồn tại bản ghi ủy quyền với người ủy quyền và thông tin người nhận ủy quyền này']
                    ]
                ], 422);
            }
        }

        // Lưu thông tin trước khi cập nhật để xử lý sau
        $oldIsShareHolder = $authority->is_shareholder;
        $oldAuthorizedPersonId = $authority->nguoi_duoc_uy_quyen;
        $oldShares = $authority->co_phan_uy_quyen;

        // Nếu is_shareholder=1, lấy thông tin từ customer và điền vào các trường
        if ($request->is_shareholder == 1 && $request->filled('nguoi_duoc_uy_quyen')) {
            $customer = Customer::findOrFail($request->nguoi_duoc_uy_quyen);
            $validated['ten_nguoi_duoc_uy_quyen'] = $customer->name;
            $validated['vn_id'] = $customer->vn_id;
            $validated['vn_id_issue_date'] = $customer->vn_id_issue_date;
            $validated['address'] = $customer->address;
        }

        // Cập nhật bản ghi ủy quyền
        $authority->update($validated);

        // Cập nhật thông tin của người nhận ủy quyền cũ (nếu là cổ đông)
        if ($oldIsShareHolder && $oldAuthorizedPersonId) {
            $oldAuthorized = Customer::find($oldAuthorizedPersonId);
            if ($oldAuthorized) {
                // Nếu người nhận ủy quyền mới khác với người cũ hoặc không còn là cổ đông
                if ($request->is_shareholder != 1 || $oldAuthorizedPersonId != $request->nguoi_duoc_uy_quyen) {
                    // Tính lại tổng số cổ phần được ủy quyền và số người ủy quyền
                    $totalShares = Authority::where('nguoi_duoc_uy_quyen', $oldAuthorizedPersonId)
                                          ->where('is_shareholder', 1)
                                          ->sum('co_phan_uy_quyen');

                    // Tính lại tổng số cổ phần được ủy quyền trừ cổ đông nội bộ
                    $totalSharesExcludingInternal = Authority::where('nguoi_duoc_uy_quyen', $oldAuthorizedPersonId)
                        ->where('is_shareholder', 1)
                        ->whereHas('authorizer', function($query) {
                            $query->where('co_dong_noi_bo', '!=', 1);
                        })
                        ->sum('co_phan_uy_quyen');

                    $totalDelegators = Authority::where('nguoi_duoc_uy_quyen', $oldAuthorizedPersonId)
                                              ->where('is_shareholder', 1)
                                              ->count();

                    // Cập nhật thông tin cổ đông cũ
                    $oldAuthorized->update([
                        'tong_co_phan_duoc_uy_quyen' => $totalShares,
                        'tong_co_phan_duoc_uy_quyen_tru_noi_bo' => $totalSharesExcludingInternal,
                        'tong_so_co_dong_uy_quyen' => $totalDelegators,
                    ]);
                }
            }
        }

        // Cập nhật thông tin của người nhận ủy quyền mới (nếu là cổ đông)
        if ($request->is_shareholder == 1 && $request->filled('nguoi_duoc_uy_quyen')) {
            $newAuthorized = Customer::find($request->nguoi_duoc_uy_quyen);
            if ($newAuthorized) {
                // Tính lại tổng số cổ phần được ủy quyền
                $totalShares = Authority::where('nguoi_duoc_uy_quyen', $request->nguoi_duoc_uy_quyen)
                                      ->where('is_shareholder', 1)
                                      ->sum('co_phan_uy_quyen');

                // Tính lại tổng số cổ phần được ủy quyền trừ cổ đông nội bộ
                $totalSharesExcludingInternal = Authority::where('nguoi_duoc_uy_quyen', $request->nguoi_duoc_uy_quyen)
                    ->where('is_shareholder', 1)
                    ->whereHas('authorizer', function($query) {
                        $query->where('co_dong_noi_bo', '!=', 1);
                    })
                    ->sum('co_phan_uy_quyen');

                // Đếm số người đã ủy quyền
                $totalDelegators = Authority::where('nguoi_duoc_uy_quyen', $request->nguoi_duoc_uy_quyen)
                                          ->where('is_shareholder', 1)
                                          ->count();

                // Cập nhật thông tin cổ đông mới
                $newAuthorized->update([
                    'tong_co_phan_duoc_uy_quyen' => $totalShares,
                    'tong_co_phan_duoc_uy_quyen_tru_noi_bo' => $totalSharesExcludingInternal,
                    'tong_so_co_dong_uy_quyen' => $totalDelegators,
                ]);
            }
        }

        // Cập nhật thông tin người ủy quyền
        $authorizer = Customer::find($request->nguoi_uy_quyen);
        if ($authorizer) {
            // Tính lại tổng số cổ phần đã ủy quyền
            $totalDelegatedShares = Authority::where('nguoi_uy_quyen', $authorizer->id)
                                          ->sum('co_phan_uy_quyen');

            // Cập nhật thông tin
            $authorizer->update([
                'co_phan_sau_uy_quyen' => $authorizer->co_phan_so_huu - $totalDelegatedShares
            ]);
        }

        return response()->json([
            'message' => 'Ủy quyền đã được cập nhật thành công.',
            'redirect' => route('cms.authority.index')
        ]);
    }

    public function destroy(Authority $authority)
    {
        // Lưu thông tin trước khi xóa để cập nhật sau
        $is_shareholder = $authority->is_shareholder;
        $nguoi_duoc_uy_quyen = $authority->nguoi_duoc_uy_quyen;
        $nguoi_uy_quyen = $authority->nguoi_uy_quyen;
        $co_phan_uy_quyen = $authority->co_phan_uy_quyen;

        // Xóa bản ghi ủy quyền
        $authority->delete();

        // Nếu người nhận ủy quyền là cổ đông, cập nhật lại thông tin của họ
        if ($is_shareholder && $nguoi_duoc_uy_quyen) {
            $authorized = Customer::find($nguoi_duoc_uy_quyen);

            if ($authorized) {
                // Tính lại tổng số cổ phần được ủy quyền
                $totalShares = Authority::where('nguoi_duoc_uy_quyen', $nguoi_duoc_uy_quyen)
                                       ->where('is_shareholder', 1)
                                       ->sum('co_phan_uy_quyen');

                // Tính lại tổng số cổ phần được ủy quyền trừ cổ đông nội bộ
                $totalSharesExcludingInternal = Authority::where('nguoi_duoc_uy_quyen', $nguoi_duoc_uy_quyen)
                    ->where('is_shareholder', 1)
                    ->whereHas('authorizer', function($query) {
                        $query->where('co_dong_noi_bo', '!=', 1);
                    })
                    ->sum('co_phan_uy_quyen');

                // Đếm số người ủy quyền còn lại
                $totalDelegators = Authority::where('nguoi_duoc_uy_quyen', $nguoi_duoc_uy_quyen)
                                          ->where('is_shareholder', 1)
                                          ->count();

                // Cập nhật thông tin cổ đông
                $authorized->update([
                    'tong_co_phan_duoc_uy_quyen' => $totalShares,
                    'tong_co_phan_duoc_uy_quyen_tru_noi_bo' => $totalSharesExcludingInternal,
                    'tong_so_co_dong_uy_quyen' => $totalDelegators,
                ]);
            }
        }

        // Cập nhật thông tin người ủy quyền
        if ($nguoi_uy_quyen) {
            $authorizer = Customer::find($nguoi_uy_quyen);
            if ($authorizer) {
                // Tính lại tổng số cổ phần đã ủy quyền
                $totalDelegatedShares = Authority::where('nguoi_uy_quyen', $nguoi_uy_quyen)
                                              ->sum('co_phan_uy_quyen');

                // Cập nhật thông tin
                $authorizer->update([
                    'co_phan_sau_uy_quyen' => $authorizer->co_phan_so_huu - $totalDelegatedShares
                ]);
            }
        }

        return redirect()->route('cms.authority.index')
            ->with('flash_message', 'Ủy quyền đã được xóa thành công.')
            ->with('flash_type', 'success');
    }

    public function searchCustomers(Request $request)
    {
        $search = $request->get('term');

        // Tìm kiếm cổ đông và loại bỏ những người đã được ủy quyền
        $customers = Customer::where(function($query) use ($search) {
            $query->where('ma_co_dong', 'LIKE', "%{$search}%")
                  ->orWhere('name', 'LIKE', "%{$search}%");
        })
        ->limit(10)
        ->get(['id', 'ma_co_dong', 'name', 'vn_id', 'co_phan_so_huu']);

        return response()->json($customers);
    }

    /**
     * Lấy thông tin số cổ phần có thể ủy quyền của một cổ đông
     */
    public function getAvailableShares(Request $request)
    {
        $customerId = $request->input('customer_id');

        if (!$customerId) {
            return response()->json(['error' => 'Thiếu mã cổ đông'], 400);
        }

        // Lấy thông tin cổ đông
        $customer = Customer::findOrFail($customerId);

        // Tính tổng số cổ phần đã ủy quyền
        $totalDelegatedShares = Authority::where('nguoi_uy_quyen', $customerId)->sum('co_phan_uy_quyen');

        // Tính số cổ phần còn có thể ủy quyền
        $availableShares = $customer->co_phan_so_huu - $totalDelegatedShares;

        return response()->json([
            'co_phan_so_huu' => $customer->co_phan_so_huu,
            'co_phan_da_uy_quyen' => $totalDelegatedShares,
            'co_phan_co_the_uy_quyen' => $availableShares
        ]);
    }
}
