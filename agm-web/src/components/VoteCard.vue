<template>
  <div class="attendance-confirmation hidden print:block bg-white p-8 text-black">
    <div class="flex justify-between mb-10">
      <div>
        <img src="@/assets/images/logo_elcom_2.svg" alt="Elcom Logo" class="h-12" />
      </div>
      <div class="text-center">
        <p class="text-lg font-bold">ĐẠI HỘI ĐỒNG CỔ ĐÔNG THƯỜNG NIÊN NĂM 2025</p>
        <p class="text-lg">CÔNG TY CP CÔNG NGHỆ - VIỄN THÔNG ELCOM</p>
      </div>
    </div>
    <div class="text-center">
      <h2 class="text-xl font-semibold mt-4">PHIẾU BẦU</h2>
      <h3 class="text-lg font-bold uppercase">THÀNH VIÊN HỘI ĐỒNG QUẢN TRỊ</h3>
      <p>MCĐ: {{shareholder.code}}</p>
    </div>
    <div class="mt-5">
      <p>Họ tên cổ đông/đại diện: {{shareholder.name}}</p>
      <p>Số cổ phần sở hữu: {{ formatNumberWithDots(shareholder.ownedVotes)}} cổ phần</p>
      <p>Số cổ phần uỷ quyền: {{ formatNumberWithDots(shareholder.delegatedVotes)}} cổ phần</p>
      <p>Tổng số phiếu bầu (tối đa):  {{ formatNumberWithDots(shareholder.totalVotes)}} phiếu</p>
    </div>

    <div class="mt-5">
      <table class="table bordered text-center">
        <tr>
          <th>STT</th>
          <th>
            Họ tên ứng cử viên
            Hội đồng quản trị
          </th>
          <th>
            <div>BẦU DỒN</div>
            <div>HẾT PHIẾU</div>
          </th>
          <th>
            Số phiếu bầu
          </th>
        </tr>
        <tr v-for="(item, index) in dataPrint.vote_items" :key="index">
          <td style="padding: 20px;">{{index + 1}}</td>
          <td style="font-size: 18px;">{{item.noi_dung}}</td>
          <td>
            <input style="width: 25px; height: 25px;" type="checkbox" :checked="!!item.vote_customer && item.so_phieu_bau === shareholder.totalVotes">
          </td>
          <td>
            <span v-if="item.so_phieu_bau < shareholder.totalVotes">{{item.so_phieu_bau}}</span>
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
    <div style="margin-top: 150px;">
      <p class="font-bold text italic underline">Hướng dẫn ghi phiếu bầu:</p>
      <p>- Số lượng thành viên HĐQT bầu tại ĐHĐCĐ thường niên 2025: 01 thành viên</p>
      <p>- Cổ đông/ đại diện cổ đông chọn ứng viên muốn bầu trong Danh sách ứng viên.</p>
      <p>- Cổ đông có quyền lựa chọn bầu theo một trong các cách sau:</p>
      <p class="ml-3">(1) Bầu hết tổng số phiếu cho ứng cử viên (Đánh dấu vào ô BẦU DỒN HẾT PHIẾU)</p>
      <p class="ml-3">(2) Bầu cho ứng cử viên một số phiếu nhất định (Ghi rõ số phiếu vào ô Số phiếu bầu)</p>
      <p>- Tổng số phiếu bầu không được vượt quá tổng số phiếu bầu (tối đa) đã ghi ở trên.</p>
    </div>
  </div>
</template>


<script>
import {ref, onMounted, computed} from 'vue';
import { useAuthStore } from '@/store/auth';
import { printBauCu } from '@/api/vote';
import {useRoute} from "vue-router";
import {printCoDong} from "../api/vote";

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
        shareholder.value.totalVotes = authStore.user.so_phieu_bau_toi_da;
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
        // shareholder.value.totalVotes = response.data.data.tong_so_co_dong_uy_quyen + 1;
        shareholder.value.totalVotes = shareholder.value.ownedVotes + shareholder.value.delegatedVotes;
      }
      const response = await printBauCu({ma_co_dong});
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
  padding: 15px;
}
</style>
