@extends('cms::layouts.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h1>Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <!-- Tổng số Users -->
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng số tài khoản </h5>
                            <h2 class="mb-0">{{ \App\Models\User::count() }}</h2>
                        </div>
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="#" class="text-white text-decoration-none">
                        Manage Users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Tổng số Cổ đông -->
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng số cổ đông </h5>
                            <h2 class="mb-0">{{ \App\Models\Customer::count() }}</h2>
                        </div>
                        <i class="fas fa-user-tie fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="#" class="text-white text-decoration-none">
                        Manage Shareholders <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
<!-- Tổng số cổ đông đã check in -->
<div class="col-md-4 mb-4">
    <div class="card bg-info text-white">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title">Tổng số cổ đông đã tham dự</h5>
                    <h2 class="mb-0">{{ \App\Models\Customer::where('is_checkin', 1)->count() }}</h2>
                </div>
                <i class="fas fa-user-check fa-3x"></i>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0">
            <a href="#" class="text-white text-decoration-none">
                View Checked In <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>
        <!-- Tổng số Phiếu biểu quyết -->
        <div class="col-md-4 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Tổng số Phiếu biểu quyết - bầu cử</h5>
                            <h2 class="mb-0">{{ \App\Models\Vote::count() }}</h2>
                        </div>
                        <i class="fas fa-vote-yea fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="#" class="text-white text-decoration-none">
                        Manage Votes <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>


                <!-- Số người đã biểu quyết -->
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title">Số người đã nhập phiếu giấy </h5>
                            <h2 class="mb-0">
                                {{ \App\Models\VoteItemCustomerImport::distinct('customer_id')->count('customer_id') }}
                            </h2>
                        </div>
                        <i class="fas fa-user-edit fa-3x"></i>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="#" class="text-white text-decoration-none">
                        Xem chi tiết <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection