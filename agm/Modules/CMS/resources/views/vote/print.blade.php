@extends('cms::layouts.master')

@push('styles')
    <link rel="stylesheet" href="{{ asset('modules/cms/static/css/element.css') }}">
@endpush

@section('content')
    <div id="app" class="bg-white p-5">
        <h1 class="text-center">Đại hội cổ đông thường niên năm 2025</h1>
        <div>
            <el-row :gutter="20">
                <el-col :span="12">
                    <div>Số cổ đông tham dự<span class="text-red">*</span></div>
                    <el-input v-model="form.so_co_dong_tham_du"></el-input>
                </el-col>
                <el-col :span="12">
                    <div>Số lượng cổ đông ủy quyền <span class="text-red">*</span></div>
                    <el-input v-model="form.so_luong_co_dong_uy_quyen"></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="20" class="mt-5">
                <el-col :span="12">
                    <div>Tổng số cổ phần tham gia <span class="text-red">*</span></div>
                    <el-input v-model="form.tong_so_co_phan_tham_gia"></el-input>
                </el-col>
                <el-col :span="12">
                    <div>
                        Tổng số cổ phần có quyền biểu quyết <span class="text-red">*</span>
                    </div>
                    <el-input v-model="form.tong_so_co_phan_co_quyen_bieu_quyet"></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="20" class="mt-5">
                <el-col :span="12">
                    <div>Tỉ lệ (%) <span class="text-red">*</span></div>
                    <el-input disabled v-model="form.ti_le"></el-input>
                </el-col>
            </el-row>
            <div class="mt-5">
                <el-button @click="save" type="primary">In biên bản</el-button>
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script src="{{ asset('modules/cms/static/js/vue.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/element.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/element-en.js') }}"></script>

    <script>
        ELEMENT.locale(ELEMENT.lang.en)
        $(document).ready(function() {
            new Vue({
                el: '#app',
                data: {
                    form: {
                        so_co_dong_tham_du: '',
                        so_luong_co_dong_uy_quyen: '',
                        tong_so_co_phan_tham_gia: '',
                        tong_so_co_phan_co_quyen_bieu_quyet: '',
                        ti_le: ''
                    },
                },
                async mounted() {
                    this.fetchData()
                },
                methods: {
                    fetchData() {
                        const url  = '{{route('cms.vote.agm_info')}}'
                        axios.get(url)
                            .then(res => {
                                if (res.data.data) {
                                    this.form = res.data.data
                                }
                            })
                    },
                    save() {
                        const url  = '{{route('cms.vote.agm_info_store')}}'
                        axios.post(url, this.form)
                            .then(res => {
                                const route = '{{ route('cms.print.bbkt') }}'
                                window.location.href = route
                            })
                    }
                }
            })
        })
    </script>
@endpush
