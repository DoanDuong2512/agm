@extends('cms::layouts.master')

@section('content')
<div class="container-xl">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Chỉnh sửa tài liệu
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
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('cms.documents.update', $document) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label required">Tiêu đề</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $document->title) }}" required>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4">{{ old('description', $document->description) }}</textarea>
                        @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File PDF hiện tại</label>
                        <div class="form-control-plaintext">
                            {{ $document->file_name }}
                            ({{ $document->formatted_file_size }})
                            <a href="{{ route('cms.documents.download', $document) }}" class="btn btn-link">Tải xuống</a>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thay thế file PDF</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" name="file" accept=".pdf,.doc,.docx">
                        <small class="form-hint">Chấp nhận file PDF, Word (.doc, .docx), kích thước tối đa 10MB. Để trống nếu không muốn thay thế file.</small>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Trạng thái</label>
                        <div>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_published" value="1" {{ old('is_published', $document->is_published) == 1 ? 'checked' : '' }}>
                                <span class="form-check-label">Xuất bản</span>
                            </label>
                            <label class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_published" value="0" {{ old('is_published', $document->is_published) == 0 ? 'checked' : '' }}>
                                <span class="form-check-label">Bản nháp</span>
                            </label>
                        </div>
                        @error('is_published')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection