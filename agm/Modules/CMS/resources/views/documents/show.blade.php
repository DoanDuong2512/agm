@extends('cms::layouts.master')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Chi tiết tài liệu
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="{{ route('cms.documents.index') }}" class="btn btn-secondary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-left" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l6 6"></path>
                            <path d="M5 12l6 -6"></path>
                        </svg>
                        Quay lại
                    </a>
                    <a href="{{ route('cms.documents.edit', $document) }}" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                            <path d="M16 5l3 3"></path>
                        </svg>
                        Chỉnh sửa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <div class="datagrid">
                    <div class="datagrid-item">
                        <div class="datagrid-title">Tiêu đề</div>
                        <div class="datagrid-content">{{ $document->title }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">Tên file</div>
                        <div class="datagrid-content">{{ $document->file_name }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">Kích thước</div>
                        <div class="datagrid-content">{{ $document->formatted_file_size }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">Ngày tạo</div>
                        <div class="datagrid-content">{{ $document->created_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">Ngày cập nhật</div>
                        <div class="datagrid-content">{{ $document->updated_at->format('d/m/Y H:i') }}</div>
                    </div>

                    <div class="datagrid-item">
                        <div class="datagrid-title">Trạng thái</div>
                        <div class="datagrid-content">
                            @if($document->is_published == 1)
                            <span class="badge bg-success">Đã xuất bản</span>
                            @else
                            <span class="badge bg-warning">Bản nháp</span>
                            @endif
                        </div>
                    </div>

                    @if($document->description)
                    <div class="datagrid-item">
                        <div class="datagrid-title">Mô tả</div>
                        <div class="datagrid-content">{{ $document->description }}</div>
                    </div>
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('cms.documents.download', $document) }}" class="btn btn-primary">
                        <x-tabler-icon name="download" size="20" />
                        Tải xuống
                    </a>
                    
                    @if($document->mime_type === 'application/pdf')
                    <a href="{{ asset('storage/' . $document->file_path) }}" class="btn btn-success ms-2" target="_blank">
                        <x-tabler-icon name="eye" size="20" />
                        Xem trước
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection