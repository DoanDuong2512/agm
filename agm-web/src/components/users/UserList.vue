<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Danh sách người dùng</h1>
    <div class="mb-2">
      <input v-model="localParams.keywords" class="border border-gray-500 p-2 rounded" type="text" placeholder="Từ khoá">
    </div>
    <!-- Bảng hiển thị -->
    <table class="table-auto w-full border-collapse border border-gray-300">
      <thead>
        <tr class="bg-gray-200">
          <th class="border border-gray-300 p-2">ID</th>
          <th class="border border-gray-300 p-2">Tên</th>
          <th class="border border-gray-300 p-2">Email</th>
          <th class="border border-gray-300 p-2">Số điện thoại</th>
          <th class="border border-gray-300 p-2">Vị trí</th>
          <th class="border border-gray-300 p-2">Bộ phận</th>
          <th class="border border-gray-300 p-2">Ảnh</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="user in users"
          :key="user.id"
          class="hover:bg-gray-100"
        >
          <td class="border border-gray-300 p-2 text-center">{{ user.id }}</td>
          <td class="border border-gray-300 p-2">{{ user.name }}</td>
          <td class="border border-gray-300 p-2">{{ user.email }}</td>
          <td class="border border-gray-300 p-2">{{ user.phone }}</td>
          <td class="border border-gray-300 p-2">{{ user.position }}</td>
          <td class="border border-gray-300 p-2">{{ user.department }}</td>
          <td class="border border-gray-300 p-2">
              <img v-if="user.avatar !== null && user.avatar !== ''" :src="user.avatar" class="w-16 h-16 rounded-full" alt="" loading="lazy">
              <div v-else>No avatar</div>  
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Điều chỉnh số lượng hàng hiển thị -->
    <div class="mt-4 flex items-center justify-between">
      <div>
        <label for="per-page" class="font-medium mr-2">Số bản ghi mỗi trang:</label>
        <select
          id="per-page"
          v-model="localParams.per_page"
          @change="emitParams"
          class="border border-gray-300 p-1"
        >
          <option v-for="option in [5, 10, 15, 20, 50]" :key="option" :value="option">
            {{ option }}
          </option>
        </select>
      </div>

      <!-- Điều hướng phân trang -->
      <div class="flex items-center space-x-2">
        <button
          :disabled="!pagination.links.previous"
          @click="navigate('previous')"
          class="bg-gray-200 border border-gray-300 px-3 py-1 rounded disabled:opacity-50"
        >
          Trước
        </button>
        <span>
          Trang 
          <input 
              type="number" 
              v-model="localParams.page" 
              @blur="updateCurrentPage" 
              @keyup.enter="updateCurrentPage"
              class="border border-gray-300 text-center w-12 p-1 rounded"
              :min="1"
              :max="pagination.total_pages"
          /> 
          
          / {{ pagination.total_pages }}
        </span>
        <button
          :disabled="!pagination.links.next"
          @click="navigate('next')"
          class="bg-gray-200 border border-gray-300 px-3 py-1 rounded disabled:opacity-50"
        >
          Tiếp
        </button>
      </div>
    </div>
  </div>
</template>
  
<script>
import { defineComponent, ref, watch } from "vue";
import debounce from 'lodash-es/debounce';

export default defineComponent({
  props: {
    users: {
      type: Array,
      required: true,
    },
    pagination: {
      type: Object,
      required: true,
    },
    params: {
      type: Object,
      required: true,
    },
    keywords: {
      type: String,
      required: false
    }
  },
  emits: ["navigate", "updateParams"],
  setup(props, { emit }) {
      const localParams = ref({ ...props.params });
      const navigate = (direction) => {
        if (direction === "previous" && localParams.value.page > 1) {
          localParams.value.page--;
        } else if (
          direction === "next" &&
          localParams.value.page < props.pagination.total_pages
        ) {
          localParams.value.page++;
        }
        emitParams();
      };

      const emitParams = () => {
        emit("updateParams", localParams.value);
      };
      watch(
        () => props.params,
        (newParams) => {
          Object.assign(localParams.value, newParams);
        }
      );
      watch(() => localParams.value.keywords, () => {
          changeKeywords()
      });
      const updateCurrentPage = () => {
          let page = parseInt(localParams.value.page, 10);
          if (page < 1) page = 1;
          if (page > props.pagination.total_pages) page = props.pagination.total_pages;
          localParams.value.page = page;
          emitParams();
      };
      const changeKeywords = debounce(() => {
          localParams.value.page = 1;
          emitParams();
      }, 500)
      return {
          updateCurrentPage,
          localParams,
          navigate,
          emitParams,
          changeKeywords
      };
  },
});
</script>

<style scoped>
table {
  border-spacing: 0;
}
</style>
  