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
                    <div>Mã cổ đông <span class="text-red">*</span></div>
                    <el-autocomplete
                        v-model="keyword"
                        style="width: 100%"
                        :fetch-suggestions="remoteMethod"
                        placeholder="Tìm mã cổ đông"
                        clearable
                        :loading="loading"
                        @select="change"
                        value-key="displayText"
                       >
                    </el-autocomplete>
                </el-col>
                <el-col :span="12">
                    <div>Họ và tên <span class="text-red">*</span></div>
                    <el-input disabled v-model="currentCustomer.name"></el-input>
                </el-col>
            </el-row>
            <el-row :gutter="20" class="mt-5">
                <el-col :span="12">
                    <div>Số CCCD/ĐKSH <span class="text-red">*</span></div>
                    <el-input disabled v-model="currentCustomer.vn_id"></el-input>
                </el-col>
                <el-col :span="12">
                    <div>
                        Tổng số cổ phần sở hữu <span class="text-red">*</span>
                    </div>
                    <el-input disabled v-model="currentCustomer.tong_co_phan"></el-input>
                </el-col>
            </el-row>
            <div class="mt-5">
                <el-tabs type="card">
                    <el-tab-pane label="Bầu cử" lazy>
                        <el-table
                            :data="baucu"
                        >
                            <el-table-column label="STT" width="180">
                                <template slot-scope="scope">
                                    1
                                </template>
                            </el-table-column>
                            <el-table-column label="Nội dung" min-width="250">
                                <template slot-scope="scope">
                                    @{{scope.row.noi_dung}}
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Bầu dồn phiếu" width="350">
                                <template slot-scope="scope">
                                    <el-checkbox v-model="scope.row.bau_don_phieu" v-model="checked"></el-checkbox>
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Số phiếu bầu" width="300">
                                <template slot-scope="scope">
                                    <el-input-number v-model="scope.row.so_phieu_bau" :min="0" :max="1000000"></el-input-number>
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Không hợp lệ" width="180">
                                <template slot-scope="scope">
                                    <el-checkbox v-model="scope.row.khong_hop_le"></el-checkbox>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="mt-3 text-center">
                            <el-button @click="saveBauCu">Lưu</el-button>
                        </div>
                    </el-tab-pane>
                    <el-tab-pane label="Biểu quyết" lazy>
                        <el-table
                            :data="bieuquyet"
                        >
                            <el-table-column label="STT" width="180">
                                <template slot-scope="scope">
                                    @{{scope.$index + 1}}
                                </template>
                            </el-table-column>
                            <el-table-column label="Nội dung" min-width="250">
                                <template slot-scope="scope">
                                    @{{scope.row.noi_dung}}
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Tán thành" width="200">
                                <template slot-scope="scope">
                                    <input v-model="scope.row.ket_qua_bieu_quyet" :value="'TAN_THANH'" type="radio" :name="scope.row.id"
                                           class="radio radio-lg checked:border-[#1C9AD6] checked:text-[#1C9AD6" />
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Không tán thành" width="200">
                                <template slot-scope="scope">
                                    <input v-model="scope.row.ket_qua_bieu_quyet" :value="'KHONG_TAN_THANH'" type="radio" :name="scope.row.id"
                                           class="radio radio-lg checked:border-[#1C9AD6] checked:text-[#1C9AD6]" />
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Không có ý kiến" width="200">
                                <template slot-scope="scope">
                                    <input v-model="scope.row.ket_qua_bieu_quyet" :value="'KHONG_CO_Y_KIEN'" type="radio" :name="scope.row.id"
                                           class="radio radio-lg checked:border-[#1C9AD6] checked:text-[#1C9AD6]" />
                                </template>
                            </el-table-column>
                            <el-table-column align="center" label="Không hợp lệ" width="180">
                                <template slot-scope="scope">
                                    <el-checkbox v-model="scope.row.khong_hop_le"></el-checkbox>
                                </template>
                            </el-table-column>
                        </el-table>
                        <div class="mt-3 text-center">
                            <el-button @click="saveBieuQuyet">Lưu</el-button>
                        </div>
                    </el-tab-pane>
                </el-tabs>
{{--                <div class="mt-3 text-center">--}}
{{--                    <el-button @click="saveVote">Lưu</el-button>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
@endsection



@push('scripts')
    <script src="{{ asset('modules/cms/static/js/vue.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/element.js') }}"></script>
    <script src="{{ asset('modules/cms/static/js/element-en.js') }}"></script>

    <script>
        // Thiết lập ngôn ngữ cho Element UI
        ELEMENT.locale(ELEMENT.lang.en)
        $(document).ready(function() {
            new Vue({
                el: '#app',
                data: {
                    message: 'hello',
                    form: {},
                    options: [], // Danh sách gợi ý cổ đông khi tìm kiếm
                    baucu: [],   // Dữ liệu phiếu bầu cử
                    bieuquyet: [], // Dữ liệu phiếu biểu quyết
                    checked: true,
                    customers: [],
                    keyword: '', // Từ khóa tìm kiếm mã cổ đông
                    loading: false,
                    currentCustomer: {}, // Thông tin cổ đông đang chọn
                },
                async mounted() {
                    // Khi component được mount, lấy dữ liệu phiếu bầu cử và biểu quyết
                    this.getVote()
                    this.getBieuQuyet()
                },
                methods: {
                    // Lấy danh sách mục phiếu bầu cử
                    getVote() {
                        const url  = '{{route('cms.vote.list')}}'
                        axios.get(url, {params: {loai: 'BAU_CU'}})
                        .then((response) => {
                            this.baucu = response.data.data
                            // Đặt lại số phiếu bầu cho mỗi mục (nếu cần)
                            this.bieuquyet.forEach(item => {
                                item.so_phieu_bau = 0
                            })
                        })
                        .catch(function (error) {
                            // Xử lý lỗi nếu có
                        });
                    },
                    // Lấy danh sách mục phiếu biểu quyết
                    getBieuQuyet() {
                        const url  = '{{route('cms.vote.list')}}'
                        axios.get(url, {params: {loai: 'BIEU_QUYET'}})
                            .then((response) => {
                                this.bieuquyet = response.data.data
                                // Gán giá trị mặc định cho kết quả biểu quyết
                                this.bieuquyet.forEach(item => {
                                    item.ket_qua_bieu_quyet = 'TAN_THANH'
                                })
                            })
                            .catch(function (error) {
                                // Xử lý lỗi nếu có
                            });
                    },
                    // Hàm tìm kiếm mã cổ đông (autocomplete)
                    remoteMethod(queryString, cb) {
                        if (!queryString) {
                            cb([])
                            return
                        }
                        this.loading = true
                        const url  = '{{route('cms.vote.customer')}}'
                        axios.get(url, { params: { ma_co_dong: queryString } })
                            .then(res => {
                                // Map dữ liệu trả về thành options cho autocomplete
                                this.options = res.data.data.map(item => ({
                                    value: item,
                                    displayText: `${item.ma_co_dong} - ${item.name}`
                                }))
                                cb(this.options)
                            })
                            .catch(err => {
                                cb([])
                            })
                            .finally(() => {
                                this.loading = false
                            })
                    },
                    // Khi chọn một cổ đông từ autocomplete
                    change(selectItem) {
                        this.currentCustomer = selectItem.value
                        // Tính tổng cổ phần sở hữu (bao gồm được ủy quyền)
                        this.currentCustomer.tong_co_phan = this.currentCustomer.co_phan_so_huu + this.currentCustomer.tong_co_phan_duoc_uy_quyen
                    },
                    // Lưu phiếu biểu quyết
                    saveBieuQuyet() {
                        const data = {
                            ma_co_dong: this.currentCustomer.ma_co_dong,
                            loai: 'BIEU_QUYET',
                            bieu_quyet: this.bieuquyet.map(item => {
                                return {
                                    id: item.id,
                                    ket_qua_bieu_quyet: item.ket_qua_bieu_quyet || 'TAN_THANH',
                                    khong_hop_le: item.khong_hop_le ? 1:0
                                }
                            })
                        }
                        const url  = '{{route('cms.vote.import')}}'
                        axios.post(url, data)
                            .then(res => {
                                // Thông báo thành công
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
                                // Thông báo lỗi
                                this.$notify({
                                    title: 'Lỗi',
                                    message: error.response.data.meta.message,
                                    type: 'error'
                                });
                            })

                    },
                    // Lưu phiếu bầu cử
                    saveBauCu() {
                        const data = {
                            ma_co_dong: this.currentCustomer.ma_co_dong,
                            loai: 'BAU_CU',
                            bau_cu: this.baucu.map(item => {
                                return {
                                    id: item.id,
                                    bau_don_phieu: item.bau_don_phieu || false,
                                    so_phieu_bau: item.so_phieu_bau ? item.so_phieu_bau : 0,
                                    khong_hop_le: item.khong_hop_le ? 1:0
                                }
                            }),
                        }
                        const url  = '{{route('cms.vote.import')}}'
                        axios.post(url, data)
                            .then(res => {
                                // Thông báo thành công
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
                                // Thông báo lỗi
                                console.log(error)
                                this.$notify({
                                    title: 'Lỗi',
                                    message: error.response.data.meta.message,
                                    type: 'error'
                                });
                            })
                    },
                    // Lưu cả hai loại phiếu (nếu muốn dùng chung)
                    saveVote() {
                        const data = {
                            ma_co_dong: this.currentCustomer.ma_co_dong,
                            bau_cu: this.baucu.map(item => {
                                return {
                                    id: item.id,
                                    bau_don_phieu: item.bau_don_phieu || false,
                                    so_phieu_bau: item.so_phieu_bau ? item.so_phieu_bau : 0,
                                    khong_hop_le: item.khong_hop_le ? 1:0
                                }
                            }),
                            bieu_quyet: this.bieuquyet.map(item => {
                                return {
                                    id: item.id,
                                    ket_qua_bieu_quyet: item.ket_qua_bieu_quyet || 'TAN_THANH',
                                    khong_hop_le: item.khong_hop_le ? 1:0
                                }
                            })
                        }
                        const url  = '{{route('cms.vote.import')}}'
                        axios.post(url, data)
                            .then(res => {
                                // Thông báo thành công
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
                                // Thông báo lỗi
                                console.log(error)
                                this.$notify({
                                    title: 'Lỗi',
                                    message: error.response.data.meta.message,
                                    type: 'error'
                                });
                            })
                    }
                }
            })
        })
    </script>
@endpush
