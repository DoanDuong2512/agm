<template>
  <div class="auth-card text-white rounded-lg shadow-lg w-full mx-auto ">
    <h1 class="text-sm mb-4 border-gray-400 border-opacity-50">Nguời ủy quyền</h1>
    <!-- Authorized Persons Grid -->
    <div class="max-h-60 overflow-y-auto rounded-lg">
      <div id="authority" class="bg-white rounded-lg p-3 pb-8 pt-8 auth-grid grid grid-cols-8 gap-4">
        <div
          v-for="(item, index) in data"
          :key="index + vote.id"
          @click="selectAuthor(item)"
          :class="{
            'cursor-not bg-white border text-center text-black p-2 rounded-lg cursor-pointer hover:bg-gray-100 active:scale-95 transition-transform duration-150': true,
            'disabled cursor-not bg-white border text-center text-black p-2 rounded-lg cursor-pointer hover:bg-gray-100 active:scale-95 transition-transform duration-150': voteAll || item.votes.includes(vote.id),
          }">
          <label class="block text-sm text-gray-700">MÃ CỔ ĐÔNG:</label>
          <p class="text-sm font-bold">{{item.ma_co_dong}}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch } from 'vue';

const props = defineProps({
  vote: {
    required: true,
    type: Object
  },
  listAuthor: {
    type: Array,
    required: true
  },
  voteAll: {
    required: true,
    type: Boolean
  }
});
const emit = defineEmits(['selectCustomer']);

const data = ref(props.listAuthor)

watch(() => props.listAuthor, (newValue) => {
  data.value = newValue;
}, { deep: true });

const selectAuthor = (item) => {
  item.votes.push(props.vote.id)
  emit('selectCustomer', {id: item.id, vote: props.vote})
}

</script>

<style>
@media (max-width: 640px) {
  .auth-card {
    /*padding: 1rem;*/
  }
  .auth-grid {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  }
}
.disabled {
  opacity: 0.5; /* Làm mờ phần tử */
  background-color: #e5e7eb; /* Màu xám nhạt (gray-200) */
  cursor: not-allowed; /* Con trỏ chuột hiển thị "không được phép" */
  pointer-events: none; /* Vô hiệu hóa các sự kiện tương tác (hover, click) */
}
</style>
