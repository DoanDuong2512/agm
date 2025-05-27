<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Modules\CMS\App\Services\PageTitleService;
use App\Models\Authority;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        PageTitleService::setTitle('Quản lý cổ đông');
        $perPage = (int) $request->input('per_page', 10);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;
        
        $query = Customer::query()
            ->with(['authoritiesGiven' => function($query) {
                $query->where('is_shareholder', 1);
            }]);
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                // Tìm kiếm chính xác
                $q->where('vn_id', 'like', "%{$search}%")
                  ->orWhere('ma_co_dong', 'like', "%{$search}%")
                  // Tìm kiếm số trong chuỗi
                  ->orWhereRaw('REPLACE(vn_id, "", "") LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('REPLACE(ma_co_dong, "ELC", "") LIKE ?', ["%{$search}%"]);
            });
        }
        // Filter by internal shareholder status
        if ($request->has('co_dong_noi_bo') && $request->co_dong_noi_bo !== '' && $request->co_dong_noi_bo !== null) {
            $query->where('co_dong_noi_bo', (int)$request->co_dong_noi_bo);
        }
        
        // Filter by check-in status
        if ($request->has('is_checkin') && $request->is_checkin !== '' && $request->is_checkin !== null) {
            $query->where('is_checkin', (int)$request->is_checkin);
        }
        
        $customers = $query->orderBy('id', 'asc')
                           ->paginate($perPage)
                           ->appends($request->all());
        
        return view('cms::customers.index', compact('customers', 'perPage'));
    }

    public function create()
    {
        return view('cms::customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'vn_id' => ['required', 'string', 'max:20'],
            'vn_id_issue_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'password' => ['required', Password::defaults()],
            'is_active' => ['required', 'in:0,1'],
            'is_checkin' => ['required', 'in:0,1'],
            'ma_co_dong' => ['nullable', 'string', 'max:50'],
            'co_phan_so_huu' => ['nullable', 'integer', 'min:0'],
            'tong_co_phan_duoc_uy_quyen' => ['nullable', 'integer', 'min:0'],
            'tong_so_co_dong_uy_quyen' => ['nullable', 'integer', 'min:0'],
            'co_dong_noi_bo' => ['nullable', 'boolean'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = ((int)$validated['is_active'] === 1) ? Customer::ACTIVATED : Customer::NOT_ACTIVATED;
        
        $validated['is_checkin'] = (int)$validated['is_checkin'];

        Customer::create($validated);

        return response()->json([
            'message' => 'Cổ đông đã được tạo thành công.',
            'redirect' => route('cms.customers.index')
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('cms::customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'vn_id' => ['required', 'string', 'max:20'],
            'vn_id_issue_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female,other'],
            'address' => ['nullable', 'string'],
            'is_active' => ['required', 'in:0,1'],
            'is_checkin' => ['required', 'in:0,1'],
            'ma_co_dong' => ['nullable', 'string', 'max:50'],
            'co_phan_so_huu' => ['nullable', 'integer', 'min:0'],
            'tong_co_phan_duoc_uy_quyen' => ['nullable', 'integer', 'min:0'],
            'tong_so_co_dong_uy_quyen' => ['nullable', 'integer', 'min:0'],
            'co_dong_noi_bo' => ['nullable', 'boolean'],
        ]);
        if ((int)$validated['is_checkin'] === 1) {
            // Kiểm tra xem có phải người được ủy quyền không phải cổ đông không
            $hasShareholderAuthority = Authority::where('nguoi_uy_quyen', $customer->id)
                ->where('is_shareholder', 1)
                ->exists();

            // Nếu không phải người được ủy quyền không phải cổ đông và đã ủy quyền hết cổ phần
            if ($hasShareholderAuthority && $customer->co_phan_da_uy_quyen >= $customer->co_phan_so_huu) {
                $validated['is_checkin'] = 0;
            }
        }
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::defaults()],
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = ((int)$validated['is_active'] === 1) ? Customer::ACTIVATED : Customer::NOT_ACTIVATED;
        $customer->update($validated);

        return response()->json([
            'message' => 'Cổ đông đã được cập nhật thành công.',
            'redirect' => route('cms.customers.index')
        ]);
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return redirect()->route('cms.customers.index')
            ->with('flash_message', 'Cổ đông đã được xóa thành công.')
            ->with('flash_type', 'success');
    }

    public function toggleCheckin(Customer $customer)
    {
        $hasShareholderAuthority = Authority::where('nguoi_uy_quyen', $customer->id)
                ->where('is_shareholder', 1)
                ->exists();

            // Nếu không phải người được ủy quyền không phải cổ đông và đã ủy quyền hết cổ phần
        if ($hasShareholderAuthority && $customer->co_phan_da_uy_quyen >= $customer->co_phan_so_huu) {
            $customer->is_checkin = 0;
        } else {
            $customer->is_checkin = !$customer->is_checkin;
        }
        $customer->update([
            'is_checkin' => $customer->is_checkin
        ]);

        return response()->json([
            'message' => $customer->is_checkin ? 'Đã check-in thành công' : 'Đã hủy check-in thành công',
            'status' => $customer->is_checkin
        ]);
    }
}

