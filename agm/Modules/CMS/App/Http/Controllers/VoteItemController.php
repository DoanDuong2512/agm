<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Vote;
use App\Models\VoteItem;
use App\Models\VoteItemCustomer;
use App\Models\VoteItemCustomerImport;
use Illuminate\Http\Request;
use Modules\CMS\App\Services\PageTitleService;

class VoteItemController extends Controller
{
    public function index(Request $request)
    {
        PageTitleService::setTitle('Quản lý phiếu biểu quyết');
        $perPage = (int) $request->input('per_page', 10);
        $perPage = ($perPage > 0 && $perPage <= 100) ? $perPage : 10;
        
        $query = VoteItem::query();
        
        // Handle search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('noi_dung', 'like', "%{$search}%")
                  ->orWhere('vi_tri_ung_cu', 'like', "%{$search}%");
            });
        }
        
        // Filter by vote ID
        if ($request->has('vote_id') && $request->vote_id !== '' && $request->vote_id !== null) {
            $query->where('vote_id', (int)$request->vote_id);
        }
        
        $voteItems = $query->orderBy('id', 'desc')
                            ->with(['vote'])
                            ->paginate($perPage)
                            ->appends($request->all());
        
        $votes = Vote::orderBy('id', 'asc')->get();
        return view('cms::vote-items.index', compact('voteItems', 'votes', 'perPage'));
    }

    public function create()
    {
        $votes = Vote::orderBy('id', 'asc')->get();
        return view('cms::vote-items.create', compact('votes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vote_id' => ['required', 'exists:votes,id'],
            'noi_dung' => ['required', 'string', 'max:255'],
            'vi_tri_ung_cu' => ['nullable', 'string', 'max:255'],
            'ti_le_chap_thuan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tong_co_phan_bieu_quyet' => ['nullable', 'integer', 'min:0'],
            'tong_so_nguoi_bieu_quyet' => ['nullable', 'integer', 'min:0'],
        ]);

        VoteItem::create($validated);

        return response()->json([
            'message' => 'Phiếu biểu quyết đã được tạo thành công.',
            'redirect' => route('cms.vote-items.index')
        ]);
    }

    public function edit(VoteItem $voteItem)
    {
        $votes = Vote::orderBy('id', 'asc')->get();
        return view('cms::vote-items.edit', compact('voteItem', 'votes'));
    }

    public function update(Request $request, VoteItem $voteItem)
    {
        $validated = $request->validate([
            'vote_id' => ['required', 'exists:votes,id'],
            'noi_dung' => ['required', 'string', 'max:255'],
            'vi_tri_ung_cu' => ['nullable', 'string', 'max:255'],
            'ti_le_chap_thuan' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tong_co_phan_bieu_quyet' => ['nullable', 'integer', 'min:0'],
            'tong_so_nguoi_bieu_quyet' => ['nullable', 'integer', 'min:0'],
        ]);

        $voteItem->update($validated);

        return response()->json([
            'message' => 'Phiếu biểu quyết đã được cập nhật thành công.',
            'redirect' => route('cms.vote-items.index')
        ]);
    }

    public function destroy(VoteItem $voteItem)
    {
        // Xóa tất cả kết quả biểu quyết trong bảng vote_item_customers
        VoteItemCustomer::where('vote_item_id', $voteItem->id)->delete();
        
        // Xóa tất cả kết quả biểu quyết trong bảng vote_item_customer_import
        VoteItemCustomerImport::where('vote_item_id', $voteItem->id)->delete();
        
        // Xóa phiếu biểu quyếts
        $voteItem->delete();

        return response()->json([
            'message' => 'Nội dung biểu quyết và tất cả kết quả biểu quyết liên quan đã được xóa thành công.',
            'success' => true
        ]);
    }
}
