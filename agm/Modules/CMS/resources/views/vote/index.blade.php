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
                        @select="selectShareholder"
                        @clear="clearShareholder"
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
        ELEMENT.locale(ELEMENT.lang.en)
        $(document).ready(function() {
            new Vue({
                el: '#app',
                data: {
                    message: 'hello',
                    form: {

                    },
                    options: [],
                    baucu: [],
                    bieuquyet: [],
                    checked: true,
                    customers: [],
                    keyword: '',
                    loading: false,
                    currentCustomer: {},
                },
                async mounted() {
                    this.getVote()
                    this.getBieuQuyet()
                },
                methods: {
                    getVote() {
                        const url  = '{{route('cms.vote.list')}}'
                        axios.get(url, {params: {loai: 'BAU_CU'}})
                        .then((response) => {
                            this.baucu = response.data.data
                            this.bieuquyet.forEach(item => {
                                item.so_phieu_bau = 0
                            })
                        })
                        .catch(function (error) {

                        });
                    },
                    getBieuQuyet() {
                        const url  = '{{route('cms.vote.list')}}'
                        axios.get(url, {params: {loai: 'BIEU_QUYET'}})
                            .then((response) => {
                                this.bieuquyet = response.data.data
                                this.bieuquyet.forEach(item => {
                                    item.ket_qua_bieu_quyet = 'TAN_THANH'
                                })
                            })
                            .catch(function (error) {

                            });
                    },
                    remoteMethod(queryString, cb) {
                        if (!queryString) {
                            cb([])
                            return
                        }
                        this.loading = true
                        const url  = '{{route('cms.vote.customer')}}'
                        axios.get(url, { params: { keywords: queryString } })
                            .then(res => {
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
                    selectShareholder(selectItem) {
                        this.currentCustomer = selectItem.value
                        this.currentCustomer.tong_co_phan = this.currentCustomer.co_phan_so_huu + this.currentCustomer.tong_co_phan_duoc_uy_quyen
                    },
                    clearShareholder(selectItem) {
                        this.currentCustomer = {}
                    },
                    saveBieuQuyet() {
                        if (!this.currentCustomer.ma_co_dong) {
                            this.$notify({
                                title: 'Lỗi',
                                message: 'Vui lòng chọn cổ đông',
                                type: 'error'
                            });
                            return
                        }
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
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
                                this.$notify({
                                    title: 'Lỗi',
                                    message: error.response.data.meta.message,
                                    type: 'error'
                                });
                            })

                    },
                    saveBauCu() {
                        if (!this.currentCustomer.ma_co_dong) {
                            this.$notify({
                                title: 'Lỗi',
                                message: 'Vui lòng chọn cổ đông',
                                type: 'error'
                            });
                            return
                        }
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
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
                                console.log(error)
                                this.$notify({
                                    title: 'Lỗi',
                                    message: error.response.data.meta.message,
                                    type: 'error'
                                });
                            })
                    },
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
                                this.$notify({
                                    title: 'Thành công',
                                    message: 'Lưu thành công',
                                    type: 'success'
                                });
                            })
                            .catch(error => {
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

        