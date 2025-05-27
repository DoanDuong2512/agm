<?php

namespace Modules\CMS\App\Http\Controllers;

use App\Http\Controllers\Api\BaseApiController;
use Illuminate\Http\Request;
// use App\Models\Vote;
// use App\Models\VoteItem;

class VotesCmsController extends BaseApiController
{
    public function index(Request $request)
    {
        return view('cms::votes.index');
    }

// public function list()
// {
//     $votes = Vote::orderBy('created_at', 'desc')->paginate(10);
// return view('cms::votes.list', compact('votes'));}
// public function create()
// {
//     return view('cms::vote.create');
// }

// public function store(Request $request)
// {
//     $validated = $request->validate([
//         'ten_phieu' => 'required|string|max:255',
//         'loai' => 'required|in:BAU_CU,BIEU_QUYET',
//         'so_luong_nguoi_duoc_trung_cu' => 'nullable|integer|min:1',
//         'cho_phep_co_dong_noi_bo_bieu_quyet' => 'nullable|boolean',
//         'cho_phep_gui_lai_phieu_bau' => 'nullable|boolean',
//         'thoi_gian_mo' => 'nullable|date',
//         'thoi_gian_dong' => 'nullable|date',
//     ]);
//     $vote = new Vote();
//     $vote->fill($validated);
//     $vote->trang_thai = 'CHUA_MO';
//     $vote->created_by = auth()->id();
//     $vote->save();
//     return redirect()->route('cms.votes.list')->with('success', 'Tạo phiếu biểu quyết thành công');
// }

// public function edit(Vote $vote)
// {
//     return view('cms::votes.edit', compact('vote'));
// }

// public function update(Request $request, Vote $vote)
// {
//     $validated = $request->validate([
//         'ten_phieu' => 'required|string|max:255',
//         'loai' => 'required|in:BAU_CU,BIEU_QUYET',
//         'so_luong_nguoi_duoc_trung_cu' => 'nullable|integer|min:1',
//         'cho_phep_co_dong_noi_bo_bieu_quyet' => 'nullable|boolean',
//         'cho_phep_gui_lai_phieu_bau' => 'nullable|boolean',
//         'thoi_gian_mo' => 'nullable|date',
//         'thoi_gian_dong' => 'nullable|date',
//     ]);
//     $vote->fill($validated);
//     $vote->save();
//     return redirect()->route('cms.votes.list')->with('success', 'Cập nhật phiếu thành công');
// }

// public function destroy(Vote $vote)
// {
//     $vote->delete();
//     return redirect()->route('cms.votes.list')->with('success', 'Xóa phiếu thành công');
// }
//     // Add other methods (create, store, show, edit, update, destroy) as needed
}