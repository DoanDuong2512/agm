@extends('cms::layouts.master')

@section('title', 'Quản lý phiếu biểu quyết')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <!-- Tiêu đề ở giữa và trên dòng đầu tiên -->
        <div class="row g-2 align-items-center">
            <div class="col-12 text-center">
                <h2 class="page-title">
                    Đại hội cổ đông thường niên năm 2025
                </h2>
            </div>
        </div>
        <!-- Form tìm kiếm và các nút -->
        <div class="row g-2 align-items-center mt-3">
            <div class="col-12 col-md-auto col-lg-8">
                <form action="{{ route('cms.vote.index') }}" method="GET" class="mb-2 mb-md-0">
                    <div class="d-flex flex-column flex-md-row gap-2 filter-group">
                        <div class="input-group input-search">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm" value="{{ request()->search }}">
                            <button type="submit" class="btn btn-primary" style="padding: 6px">
                                <x-tabler-icon name="search" size="20" style="margin: 0" />
                            </button>
                        </div>
                        <select name="status" class="form-select input-filter" onchange="this.form.submit()">
                            <option value="">Chọn trạng thái</option>
                            <option value="Đóng phiếu" {{ request()->status == 'Đóng phiếu' ? 'selected' : '' }}>Đóng phiếu</option>
                            <option value="Mở phiếu" {{ request()->status == 'Mở phiếu' ? 'selected' : '' }}>Mở phiếu</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="col-12 col-md-auto col-lg-4 ms-auto d-print-none">
                <div class="btn-list justify-content-end">
                    <!-- Nút outline - Điều hướng đến create cho phiếu bầu cử -->
                         <a class="btn btn-outline-primary d-flex align-items-center gap-2">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm phiếu bầu cử</span>
                    </a>
                    <!-- Nút contained - Điều hướng đến create cho phiếu biểu quyết -->
                        <a class="btn btn-primary d-flex align-items-center gap-2">
                        <x-tabler-icon name="plus" size="20" />
                        <span>Thêm phiếu biểu quyết</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Loại phiếu</th>
                                <th>Tên phiếu</th>
                                <th>Trạng thái</th>
                                <th>Thời gian mở</th>
                                <th>Thời gian đóng</th>
                                <th class="w-1">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $fakeVotes = [
                                    ['id' => 1, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 2, 'loai_phieu' => 'Biểu quyết', 'ten_phieu' => 'Phiếu biểu quyết 3', 'trang_thai' => 'Mở phiếu', 'thoi_gian_mo' => '00:14:59', 'thoi_gian_dong' => ''],
                                    ['id' => 3, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 4, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 5, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 6, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 7, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 8, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 9, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                    ['id' => 10, 'loai_phieu' => 'Bầu cử', 'ten_phieu' => 'Phiếu bầu cử 1', 'trang_thai' => 'Đóng phiếu', 'thoi_gian_mo' => '00:00:00', 'thoi_gian_dong' => ''],
                                ];
                            @endphp

                            @forelse($fakeVotes as $index => $vote)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <span class="badge {{ $vote['loai_phieu'] == 'Bầu cử' ? 'bg-blue-lt' : 'bg-yellow-lt' }}">
                                        {{ $vote['loai_phieu'] }}
                                    </span>
                                </td>
                                <td>{{ $vote['ten_phieu'] }}</td>
                                <td>
                                    <span class="badge {{ $vote['trang_thai'] == 'Đóng phiếu' ? 'bg-green-lt' : 'bg-red-lt' }}">
                                        {{ $vote['trang_thai'] }}
                                    </span>
                                </td>
                                <td>{{ $vote['thoi_gian_mo'] }}</td>
                                <td>{{ $vote['thoi_gian_dong'] }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="#" class="text-primary">
                                            <x-tabler-icon name="eye" size="20" />
                                        </a>
                                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#editVoteModal" data-vote-id="{{ $vote['id'] }}">
                                            <x-tabler-icon name="edit" size="20" />
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center">Không có phiếu biểu quyết nào</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 d-flex justify-content-between align-items-center">
                    <div>
                        Hiển thị 1-2 tổng số 100 tram BTS
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.filter-group {
    .input-search {
        width: 60%;
    }
    .input-filter {
        width: 20%
    }
}
@media (max-width: 768px) {
    .filter-group {
        .input-search {
            width: 100%;
        }
        .input-filter {
            width: 100%;
        }
    }
}
.badge.bg-green-lt {
    background-color: #e6f5e6 !important;
    color: #2e7d32 !important;
}
.badge.bg-red-lt {
    background-color: #fdeded !important;
    color: #d32f2f !important;
}
.form-label.required::after {
    content: "*";
    color: red;
    margin-left: 4px;
}
</style>
@endpush