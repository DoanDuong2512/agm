<template>
  <div class="attendance-confirmation hidden print:block bg-white p-6 text-black">
    <div class="flex justify-between mb-3">
      <div>
        <img src="@/assets/images/logo_elcom_2.svg" alt="Elcom Logo" class="h-12" />
      </div>
      <div class="text-center">
        <p class="text-lg font-bold">ĐẠI HỘI ĐỒNG CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</p>
        <p class="text-lg">CÔNG TY CP CÔNG NGHỆ - VIỄN THÔNG ELCOM</p>
      </div>
    </div>
    <div class="text-center">
      <h2 class="text-xl font-semibold mt-1">THẺ BIỂU QUYẾT</h2>
      <p>MCĐ: {{shareholder.code}}</p>
    </div>
    <div class="mt-1">
      <p>Họ tên cổ đông/đại diện: {{shareholder.name}}</p>
      <p>Số cổ phần sở hữu: {{formatNumberWithDots(shareholder.ownedVotes)}} cổ phần</p>
      <p>Số cổ phần uỷ quyền: {{formatNumberWithDots(shareholder.delegatedVotes)}} cổ phần</p>
      <p>Tổng số cổ phần biểu quyết:  {{formatNumberWithDots(shareholder.ownedVotes + shareholder.delegatedVotes)}} cổ phần</p>
    </div>

    <div class="mt-3">
      <table class="table bordered text-center">
        <tr>
          <th >STT</th>
          <th style="width: 60%">
            Nội dung
          </th>
          <th>
            Tán thành
          </th>
          <th>
            Không tán thành
          </th>
          <th>
            Không có ý kiến
          </th>
        </tr>
        <tr v-for="(item, index) in dataPrint.vote_items" :key="index">
          <td>{{index + 1}}</td>
          <td style="width: 55%" class="text-left">{{item.noi_dung}}</td>
          <td width="12%">
            <div class="w-full flex justify-center" v-if="item.ket_qua_bieu_quyet === 'TAN_THANH'">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 13L9 17L19 7" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </td>
          <td width="12%">
            <div class="w-full flex justify-center" v-if="item.ket_qua_bieu_quyet === 'KHONG_TAN_THANH'">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 13L9 17L19 7" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </td>
          <td width="12%">
            <div class="w-full flex justify-center" v-if="item.ket_qua_bieu_quyet === 'KHONG_CO_Y_KIEN'">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M5 13L9 17L19 7" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </td>
        </tr>
      </table>
    </div>
    <div class="flex flex-col items-end justify-center mt-3">
      <div class="text-center">
        <p class="italic">Hà Nội, ngày 24 tháng 04 năm 2025</p>
        <p class="font-bold">CỔ ĐÔNG/ĐẠI DIỆN</p>
        <p class="text-sm italic">(Ký và ghi rõ họ tên)</p>
      </div>
    </div>
    <div style="margin-top: 120px;">
      <p>
        <b style="text-decoration: underline">Ghi chú</b>: Cổ đông biểu quyết bằng cách lựa chọn <b>một</b> trong ba phương án: <b>Tán thành, Không tán thành, Không có ý kiến</b> cho từng nội dung biểu quyết.
      </p>
    </div>
  </div>
</template>


<script>
import {ref, onMounted, computed} from 'vue';
import { useAuthStore } from '@/store/auth';
import { printBauCu } from '@/api/vote';
import {printBieuQuyet, printCoDong} from "../api/vote";
import {useRoute} from "vue-router";

export default {
  name: 'VoteCard',
  setup() {
    const authStore = useAuthStore();
    const shareholder = ref({
      name: '',
      code: '',
      totalVotes: 0,
      ownedVotes: 0,
      delegatedVotes: 0,
      vnId: ''
    });
    const route = useRoute();

    const data = ref({})

    const dataPrint = computed(() => {
      return data.value
    });

    const printConfirmation = () => {
      window.print();
    };

    const loadUserData = async () => {
      // Lấy dữ liệu từ auth store
      if (authStore.user) {
        shareholder.value = {
          name: authStore.user.name || '---',
          code: authStore.user.ma_co_dong || '---',
          ownedVotes: authStore.user.co_phan_so_huu || 0,
          delegatedVotes: authStore.user.tong_co_phan_duoc_uy_quyen || 0,
          vnId: authStore.user.vn_id || '---'
        };
        shareholder.value.totalVotes = authStore.user.tong_so_co_dong_uy_quyen + 1;
      }
      await fetchDataPrint()
    };

    const fetchDataPrint = async () => {
      const ma_co_dong = route.query.ma_co_dong || null;
      if (ma_co_dong) {
        const response = await printCoDong({ma_co_dong});
        shareholder.value = {
          name: response.data.data.name || '---',
          code: response.data.data.ma_co_dong || '---',
          ownedVotes: response.data.data.co_phan_so_huu || 0,
          delegatedVotes: response.data.data.tong_co_phan_duoc_uy_quyen || 0,
          vnId: response.data.data.vn_id || '---'
        };
        shareholder.value.totalVotes = response.data.data.tong_so_co_dong_uy_quyen + 1;
      }
      const response = await printBieuQuyet({ma_co_dong});
      data.value = response.data.data;
    };

    onMounted(() => {
      loadUserData();
    });

    const formatNumberWithDots = (number) => {
      return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    return {
      shareholder,
      printConfirmation,
      loadUserData,
      dataPrint,
      formatNumberWithDots
    };
  }
};
</script>

<style>
table.bordered {
  border-collapse: collapse;
}
table.bordered td,  table.bordered th {
  border: 1px solid;
  padding: 2px;
  vertical-align: middle;
}
</style>
