<template>
  <div class="voting-card bg-blue-800 text-white p-6 rounded-lg shadow-lg w-full mx-auto">

    <div class="flex justify-between">
      <h1 class="text-2xl font-bold mb-4">BIỂU QUYẾT BẦU CỬ</h1>
      <button @click="openPrintModal" class="btn btn-send">
        In Phiếu
      </button>
    </div>

    <!-- Voting Details -->
    <div class="voting-details flex flex-wrap gap-4 mb-6 mt-5">
      <div class="flex-1 min-w-[200px]">
        <label class="block mb-1">Họ và tên</label>
        <input type="text" :value="user.name" class="input input-bordered w-full bg-white text-black" readonly />
      </div>
      <div class="flex-1 min-w-[200px]">
        <label class="block mb-1">Số SHCK</label>
        <input type="text" :value="user.vn_id" class="input input-bordered w-full bg-white text-black" readonly />
      </div>
      <div class="flex-1 min-w-[200px]">
        <label class="block mb-1">Tổng số đại diện ủy quyền</label>
        <input type="text" :value="user.tong_so_co_dong_uy_quyen" class="input input-bordered w-full bg-white text-black" readonly />
      </div>
      <div class="flex-1 min-w-[200px]">
        <label class="block mb-1">Tổng số cổ phần có quyền biểu quyết</label>
        <input type="text" :value="user.co_phan_bieu_quyet" class="input input-bordered w-full bg-white text-black" readonly />
      </div>
    </div>
    <!-- MODAL -->
    <input type="checkbox" class="modal-toggle" v-model="isOpen" />
    <div class="modal" id="confirmModal">
      <div class="modal-box bg-white text-black max-w-md">
        <!-- Tiêu đề -->
        <h3 class="font-bold text-lg mb-4">XÁC NHẬN LƯU PHIẾU</h3>

        <!-- Nội dung -->
        <p class="mb-2">
          Bạn có muốn lưu kết quả phiếu.
        </p>
        <!-- Lưu ý -->
        <p class="text-sm mb-2 font-semibold">Lưu ý:</p>

        <p v-if="currentVote.loai === 'BIEU_QUYET'" class="text-sm font-bold">
          • Kết quả sẽ được tính theo số cổ phần cổ quyền biểu quyết
        </p>
        <p v-else class="text-sm font-bold">
          • Kết quả sẽ được tính theo số phiếu bầu cử
        </p>

        <!-- Nút hành động -->
        <div class="modal-action mt-6">
          <button class="btn bg-[#F0EEF5] border-[#F0EEF5] text-black" @click="closeModal">HỦY</button>
          <button
              :disabled="loading"
              class="btn bg-[#1C9AD6] border-[#1C9AD6]"
              @click="confirmSend">
            XÁC NHẬN
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL IN PHIEU -->
    <input type="checkbox" class="modal-toggle" v-model="isOpenPrintModal" />
    <div class="modal" id="printModal">
      <div class="modal-box bg-white text-black">
        <!-- Tiêu đề -->
        <div class="flex justify-between">
          <h3 class="font-bold text-lg mb-4">IN PHIẾU</h3>
          <button @click="closePrintModal" class="btn btn-sm btn-circle btn-ghost">✕</button>
        </div>

        <!-- Nội dung -->
        <table class="table text-center">
          <!-- head -->
          <thead class="text-black">
          <tr>
            <th>STT</th>
            <th>Tên phiếu in</th>
            <th>Thao tác</th>
          </tr>
          </thead>
          <tbody>
          <!-- row 1 -->
          <tr>
            <th>01</th>
            <td>Tư cách cổ đông</td>
            <td class="flex items-center gap-3">
<!--              <div class="cursor-pointer">-->
<!--                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                  <path d="M10.5535 14.5061C10.4114 14.6615 10.2106 14.75 10 14.75C9.78943 14.75 9.58857 14.6615 9.44648 14.5061L5.44648 10.1311C5.16698 9.82538 5.18822 9.35098 5.49392 9.07148C5.79963 8.79198 6.27402 8.81322 6.55352 9.11892L9.25 12.0682V1C9.25 0.585787 9.58579 0.25 10 0.25C10.4142 0.25 10.75 0.585787 10.75 1V12.0682L13.4465 9.11892C13.726 8.81322 14.2004 8.79198 14.5061 9.07148C14.8118 9.35098 14.833 9.82537 14.5535 10.1311L10.5535 14.5061Z" fill="#1C274C"/>-->
<!--                  <path d="M1.75 13C1.75 12.5858 1.41422 12.25 1 12.25C0.585788 12.25 0.250001 12.5858 0.250001 13V13.0549C0.24998 14.4225 0.249964 15.5248 0.366524 16.3918C0.487541 17.2919 0.746434 18.0497 1.34835 18.6516C1.95027 19.2536 2.70814 19.5125 3.60825 19.6335C4.47522 19.75 5.57754 19.75 6.94513 19.75H13.0549C14.4225 19.75 15.5248 19.75 16.3918 19.6335C17.2919 19.5125 18.0497 19.2536 18.6517 18.6516C19.2536 18.0497 19.5125 17.2919 19.6335 16.3918C19.75 15.5248 19.75 14.4225 19.75 13.0549V13C19.75 12.5858 19.4142 12.25 19 12.25C18.5858 12.25 18.25 12.5858 18.25 13C18.25 14.4354 18.2484 15.4365 18.1469 16.1919C18.0482 16.9257 17.8678 17.3142 17.591 17.591C17.3142 17.8678 16.9257 18.0482 16.1919 18.1469C15.4365 18.2484 14.4354 18.25 13 18.25H7C5.56459 18.25 4.56347 18.2484 3.80812 18.1469C3.07435 18.0482 2.68577 17.8678 2.40901 17.591C2.13225 17.3142 1.9518 16.9257 1.85315 16.1919C1.75159 15.4365 1.75 14.4354 1.75 13Z" fill="#1C274C"/>-->
<!--                </svg>-->
<!--              </div>-->
              <div @click="goToPrintPage('attendance')" class="cursor-pointer flex justify-center w-full">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 13H9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M22 17H8" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <circle cx="20" cy="13" r="1" fill="#1C274C"/>
                  <path d="M18 19.5H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M16 22H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M25 15C25 17.8284 25 19.2426 24.1213 20.1213C23.48 20.7626 22.5535 20.9359 21 20.9827M9 20.9827C7.44655 20.9359 6.51998 20.7626 5.87868 20.1213C5 19.2426 5 17.8284 5 15C5 12.1716 5 10.7574 5.87868 9.87868C6.75736 9 8.17157 9 11 9H19C21.8284 9 23.2426 9 24.1213 9.87868C24.4211 10.1785 24.6186 10.5406 24.7487 11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M20.9827 9C20.9359 7.44655 20.7626 6.51998 20.1213 5.87868C19.2426 5 17.8284 5 15 5C12.1716 5 10.7574 5 9.87868 5.87868C9.23738 6.51998 9.06413 7.44655 9.01732 9M21 18V19C21 21.8284 21 23.2426 20.1213 24.1213C19.48 24.7626 18.5535 24.9359 17 24.9827M9 18V19C9 21.8284 9 23.2426 9.87868 24.1213C10.52 24.7626 11.4466 24.9359 13 24.9827" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </div>
            </td>
          </tr>
          <!-- row 2 -->
          <tr>
            <th>02</th>
            <td>Phiếu biểu quyết</td>
            <td class="flex items-center gap-3">
<!--              <div class="cursor-pointer">-->
<!--                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                  <path d="M10.5535 14.5061C10.4114 14.6615 10.2106 14.75 10 14.75C9.78943 14.75 9.58857 14.6615 9.44648 14.5061L5.44648 10.1311C5.16698 9.82538 5.18822 9.35098 5.49392 9.07148C5.79963 8.79198 6.27402 8.81322 6.55352 9.11892L9.25 12.0682V1C9.25 0.585787 9.58579 0.25 10 0.25C10.4142 0.25 10.75 0.585787 10.75 1V12.0682L13.4465 9.11892C13.726 8.81322 14.2004 8.79198 14.5061 9.07148C14.8118 9.35098 14.833 9.82537 14.5535 10.1311L10.5535 14.5061Z" fill="#1C274C"/>-->
<!--                  <path d="M1.75 13C1.75 12.5858 1.41422 12.25 1 12.25C0.585788 12.25 0.250001 12.5858 0.250001 13V13.0549C0.24998 14.4225 0.249964 15.5248 0.366524 16.3918C0.487541 17.2919 0.746434 18.0497 1.34835 18.6516C1.95027 19.2536 2.70814 19.5125 3.60825 19.6335C4.47522 19.75 5.57754 19.75 6.94513 19.75H13.0549C14.4225 19.75 15.5248 19.75 16.3918 19.6335C17.2919 19.5125 18.0497 19.2536 18.6517 18.6516C19.2536 18.0497 19.5125 17.2919 19.6335 16.3918C19.75 15.5248 19.75 14.4225 19.75 13.0549V13C19.75 12.5858 19.4142 12.25 19 12.25C18.5858 12.25 18.25 12.5858 18.25 13C18.25 14.4354 18.2484 15.4365 18.1469 16.1919C18.0482 16.9257 17.8678 17.3142 17.591 17.591C17.3142 17.8678 16.9257 18.0482 16.1919 18.1469C15.4365 18.2484 14.4354 18.25 13 18.25H7C5.56459 18.25 4.56347 18.2484 3.80812 18.1469C3.07435 18.0482 2.68577 17.8678 2.40901 17.591C2.13225 17.3142 1.9518 16.9257 1.85315 16.1919C1.75159 15.4365 1.75 14.4354 1.75 13Z" fill="#1C274C"/>-->
<!--                </svg>-->
<!--              </div>-->
              <div @click="goToPrintPage('thebieuquyet')" class="cursor-pointer flex justify-center w-full">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 13H9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M22 17H8" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <circle cx="20" cy="13" r="1" fill="#1C274C"/>
                  <path d="M18 19.5H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M16 22H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M25 15C25 17.8284 25 19.2426 24.1213 20.1213C23.48 20.7626 22.5535 20.9359 21 20.9827M9 20.9827C7.44655 20.9359 6.51998 20.7626 5.87868 20.1213C5 19.2426 5 17.8284 5 15C5 12.1716 5 10.7574 5.87868 9.87868C6.75736 9 8.17157 9 11 9H19C21.8284 9 23.2426 9 24.1213 9.87868C24.4211 10.1785 24.6186 10.5406 24.7487 11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M20.9827 9C20.9359 7.44655 20.7626 6.51998 20.1213 5.87868C19.2426 5 17.8284 5 15 5C12.1716 5 10.7574 5 9.87868 5.87868C9.23738 6.51998 9.06413 7.44655 9.01732 9M21 18V19C21 21.8284 21 23.2426 20.1213 24.1213C19.48 24.7626 18.5535 24.9359 17 24.9827M9 18V19C9 21.8284 9 23.2426 9.87868 24.1213C10.52 24.7626 11.4466 24.9359 13 24.9827" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </div>
            </td>
          </tr>
          <!-- row 3 -->
          <tr>
            <th>03</th>
            <td>Phiếu bầu cử</td>
            <td class="flex items-center gap-3">
<!--              <div class="cursor-pointer">-->
<!--                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">-->
<!--                  <path d="M10.5535 14.5061C10.4114 14.6615 10.2106 14.75 10 14.75C9.78943 14.75 9.58857 14.6615 9.44648 14.5061L5.44648 10.1311C5.16698 9.82538 5.18822 9.35098 5.49392 9.07148C5.79963 8.79198 6.27402 8.81322 6.55352 9.11892L9.25 12.0682V1C9.25 0.585787 9.58579 0.25 10 0.25C10.4142 0.25 10.75 0.585787 10.75 1V12.0682L13.4465 9.11892C13.726 8.81322 14.2004 8.79198 14.5061 9.07148C14.8118 9.35098 14.833 9.82537 14.5535 10.1311L10.5535 14.5061Z" fill="#1C274C"/>-->
<!--                  <path d="M1.75 13C1.75 12.5858 1.41422 12.25 1 12.25C0.585788 12.25 0.250001 12.5858 0.250001 13V13.0549C0.24998 14.4225 0.249964 15.5248 0.366524 16.3918C0.487541 17.2919 0.746434 18.0497 1.34835 18.6516C1.95027 19.2536 2.70814 19.5125 3.60825 19.6335C4.47522 19.75 5.57754 19.75 6.94513 19.75H13.0549C14.4225 19.75 15.5248 19.75 16.3918 19.6335C17.2919 19.5125 18.0497 19.2536 18.6517 18.6516C19.2536 18.0497 19.5125 17.2919 19.6335 16.3918C19.75 15.5248 19.75 14.4225 19.75 13.0549V13C19.75 12.5858 19.4142 12.25 19 12.25C18.5858 12.25 18.25 12.5858 18.25 13C18.25 14.4354 18.2484 15.4365 18.1469 16.1919C18.0482 16.9257 17.8678 17.3142 17.591 17.591C17.3142 17.8678 16.9257 18.0482 16.1919 18.1469C15.4365 18.2484 14.4354 18.25 13 18.25H7C5.56459 18.25 4.56347 18.2484 3.80812 18.1469C3.07435 18.0482 2.68577 17.8678 2.40901 17.591C2.13225 17.3142 1.9518 16.9257 1.85315 16.1919C1.75159 15.4365 1.75 14.4354 1.75 13Z" fill="#1C274C"/>-->
<!--                </svg>-->
<!--              </div>-->
              <div @click="goToPrintPage('votecard')" class="cursor-pointer flex justify-center w-full">
                <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M12 13H9" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M22 17H8" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <circle cx="20" cy="13" r="1" fill="#1C274C"/>
                  <path d="M18 19.5H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M16 22H12" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M25 15C25 17.8284 25 19.2426 24.1213 20.1213C23.48 20.7626 22.5535 20.9359 21 20.9827M9 20.9827C7.44655 20.9359 6.51998 20.7626 5.87868 20.1213C5 19.2426 5 17.8284 5 15C5 12.1716 5 10.7574 5.87868 9.87868C6.75736 9 8.17157 9 11 9H19C21.8284 9 23.2426 9 24.1213 9.87868C24.4211 10.1785 24.6186 10.5406 24.7487 11" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M20.9827 9C20.9359 7.44655 20.7626 6.51998 20.1213 5.87868C19.2426 5 17.8284 5 15 5C12.1716 5 10.7574 5 9.87868 5.87868C9.23738 6.51998 9.06413 7.44655 9.01732 9M21 18V19C21 21.8284 21 23.2426 20.1213 24.1213C19.48 24.7626 18.5535 24.9359 17 24.9827M9 18V19C9 21.8284 9 23.2426 9.87868 24.1213C10.52 24.7626 11.4466 24.9359 13 24.9827" stroke="#1C274C" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </div>
            </td>
          </tr>
          </tbody>
        </table>

        <!-- Nút hành động -->
      </div>
    </div>

    <!-- Voting List and Status -->
    <div class="flex flex-col md:flex-row gap-6">
      <!-- Voting List -->
      <div class="flex-1">
        <div class="voting-list space-y-2">
          <div
            v-for="(vote,index) in votes"
            :key="index"
            class="collapse collapse-arrow join-item rounded-none join-item border-b border-gray-200 border-opacity-70"
          >
            <input type="radio" name="my-accordion-4" />
            <div class="collapse-title flex justify-between">
              <div>
                {{vote.ten_phieu}}
              </div>
              <div :class="getStatus(vote).class_css">
                <div class="text-status">
                  {{getStatus(vote).label}}
                </div>
              </div>
            </div>
            <div class="collapse-content">
              <div :style="authorize.length ? '':'display: none'" class="mb-10">
                <div class="flex items-center mb-5">
                  <input
                      type="checkbox"
                      v-model="vote.vote_all"
                      class="toggle toggle-sm checked:bg-[#1C9AD6] checked:border-[#1C9AD6]"
                      style="pointer-events: none"
                  />
                  <div class="ml-4">Bỏ phiếu chung cho tất cả cổ đông ủy quyền</div>
                </div>
                <Authority
                    :vote="vote"
                    :list-author="authorize"
                    :vote-all="vote.vote_all"
                    @select-customer="selectCustomer"
                >
                </Authority>
              </div>

              <div v-if="vote.loai === 'BAU_CU'">
                <div class="voting-card text-white rounded-lg w-full mx-auto">
                  <!-- Header -->
                  <div class="voting-header flex justify-between items-center mb-4">
                    <div class="flex gap-40">
                      <div class="flex items-center">
                        <label class="block text-sm font-semibold mr-3">Số phiếu bầu tối đa:</label>
                        <p class="text-lg font-bold">{{ user.so_phieu_bau_toi_da }}</p>
                      </div>
                      <div class="flex items-center">
                        <label class="block text-sm font-semibold mr-3">Số phiếu đã bầu:</label>
                        <p class="text-lg font-bold">{{vote.vote_customers_count}}</p>
                      </div>
                      <div class="flex items-center">
                        <label class="block text-sm font-semibold mr-3">Số phiếu còn lại:</label>
                        <p class="text-lg font-bold">{{ user.so_phieu_bau_toi_da - vote.vote_customers_count}}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Candidate List -->
                  <div class="flex justify-between border-t border-gray-400 border-opacity-50 pt-3">
                    <h2 class="text-sm font-bold mb-2">Danh sách ứng viên</h2>
                    <div class="text-sm text-gray-400">
                      Số lượng ứng viên được bầu tối đa: {{ vote.so_luong_nguoi_duoc_trung_cu }}
                    </div>
                  </div>
                  <div class="candidate-list">
                    <div
                        v-for="(item, index) in vote.vote_items"
                        :key="index + 'item1'"
                        class="candidate-item flex items-center justify-between p-2 border-b border-gray-400 border-opacity-50">
                      <div class="candidate-item-left flex items-center gap-2">
                        <input
                            v-if="vote.is_voted"
                            checked
                            type="checkbox"
                            class="checkbox bg-[#fff] checked:bg-[#1C9AD6] checkbox-sm"
                        />
                        <input
                            v-else
                            :style="vote.is_voted ? 'pointer-events: none' : ''"
                            @change="selectItemVote(vote)"
                            v-model="vote.item_vote"
                            :disabled="vote.item_vote.length >= vote.so_luong_nguoi_duoc_trung_cu && !vote.item_vote.includes(item.id)"
                            :value="item.id"
                            type="checkbox"
                            class="checkbox bg-[#fff] checked:bg-[#1C9AD6] checkbox-sm"
                        />
                        <div class="candidate-info flex items-center w-full">
                          <p class="text-sm w-[200px]">{{item.noi_dung}}</p>
                          <p class="text-sm">{{item.vi_tri_ung_cu}}</p>
                        </div>
                      </div>
                      <div class="votes-input flex items-center gap-2">
                        <div class="text-sm w-[100px]">Số phiếu:</div>
                        <input
                            type="number"
                            :readonly="vote.is_voted"
                            :disabled="vote.item_vote.length >= vote.so_luong_nguoi_duoc_trung_cu && !vote.item_vote.includes(item.id)"
                            :value="item.so_phieu_bau ? item.so_phieu_bau : user.so_phieu_bau_toi_da"
                            v-model="item.so_phieu_bau"
                            @change="changeSoPhieu(item)"
                            class="input input-bordered input-sm min-w-[200px] bg-white text-black"
                            :max="user.so_phieu_bau_toi_da"
                            min="1" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else-if="vote.loai === 'BIEU_QUYET'">
                <div class="voting-card text-white rounded-lg w-full mx-auto">
                  <!-- Header -->
                  <div class="voting-header flex justify-between items-center mb-4">
                    <div class="flex gap-40">
                      <div class="flex items-center">
                        <label class="block font-semibold mr-3">Số phần có quyền biểu quyết:</label>
                        <p class="text-lg font-bold">{{user.co_phan_bieu_quyet}}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Candidate List -->
                  <div
                      v-for="(item, index) in vote.vote_items"
                      :key="index + 'item2'"
                      class="voting-options-card text-white w-full mx-auto border-t border-gray-400 border-opacity-50 pt-3">
                    <!-- Description -->
                    <p class="voting-description mb-5">
                      {{item.noi_dung}}
                    </p>

                    <!-- Voting Options -->
                    <div class="voting-options flex justify-center gap-40 mb-5">
                      <div class="voting-option flex items-center gap-2">
                        <input v-model="item.ket_qua_bieu_quyet" :value="'TAN_THANH'" type="radio" checked :name="item.id" class="radio checked:border-[#1C9AD6] checked:text-[#1C9AD6] radio-sm" />
                        <label class="text-sm uppercase font-bold">TÁN THÀNH</label>
                      </div>
                      <div class="voting-option flex items-center gap-2">
                        <input v-model="item.ket_qua_bieu_quyet" :value="'KHONG_TAN_THANH'" type="radio" :name="item.id" class="radio checked:border-[#1C9AD6] checked:text-[#1C9AD6] radio-sm" />
                        <label class="text-sm uppercase font-bold">KHÔNG TÁN THÀNH</label>
                      </div>
                      <div class="voting-option flex items-center gap-2">
                        <input v-model="item.ket_qua_bieu_quyet" :value="'KHONG_CO_Y_KIEN'" type="radio" :name="item.id" class="radio checked:border-[#1C9AD6] checked:text-[#1C9AD6] radio-sm" />
                        <label class="text-sm uppercase font-bold">KHÔNG CÓ Ý KIẾN</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex justify-center mt-10 gap-3">
                <button
                    v-if="vote.is_voted"
                    @click="reSend(vote)"
                    :disabled="loading"
                    class="btn btn-resend">
                  Bỏ phiếu lại
                </button>
                <button
                    v-if="!vote.is_voted"
                    @click="send(vote)"
                    :disabled="loading"
                    class="btn btn-resend">
                  Lưu phiếu
                </button>
                <button
                    @click="goToPrintPage(vote.loai === 'BAU_CU' ? 'votecard':'thebieuquyet')"
                    class="btn btn-send">
                  In phiếu
                </button>
              </div>
              <div class="text-center text-white mt-4">
                Cổ đông gửi bản ảnh chụp/scan Phiếu biểu quyết, Phiếu bầu cử và Giấy xác nhận tham dự ĐÃ KÝ đến địa chỉ Email: agm@elcom.com.vn trước 12h ngày 23/04/2025
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div id="print" style="display: none; visibility: hidden">
      123123123
    </div>
  </div>
</template>

<script setup>
  import {useRouter} from "vue-router";
  import { ref, onMounted, onUnmounted } from 'vue';
  import Authority from '@/components/Authority.vue';
  import { getVotes, getAuthorize, vote, reVote } from '@/api/vote';
  import { getCurrentUserInfo } from '@/api/auth';
  import { showToast } from '@/utils/toast';


  const router = useRouter();

  const user = ref({})
  const votes = ref([]);
  const authorize = ref([]);
  const isOpen = ref(false)
  const isOpenPrintModal = ref(false)
  const currentVote = ref({})
  const currentData = ref({})
  const loading = ref(false)

  // Methods
  const fetchCurrentUserInfo = async () => {
    const response = await getCurrentUserInfo();
    user.value = response.data.data;
  };

  const fetchVotes = async () => {
    const response = await getVotes();
    votes.value = response.data.data;
  };

  const fetchAuthorize = async () => {
    const response = await getAuthorize();
    authorize.value = response.data.data;
  };

  fetchCurrentUserInfo()
  fetchVotes()
  fetchAuthorize()

  const openModal = () => {
    isOpen.value = true
  }
  const closeModal = () => {
    isOpen.value = false
  }

  const openPrintModal = () => {
    isOpenPrintModal.value = true
  }
  const closePrintModal = () => {
    isOpenPrintModal.value = false
  }

  const send = (vote) => {
    const data = {
      vote_id: vote.id,
      vote_customer_id: vote.vote_customer,
      is_vote_all: vote.vote_all
    }

    let vote_item_status = []
    if (vote.loai === 'BIEU_QUYET') {
      vote_item_status = vote.vote_items.map(item => {
        return {
          id: item.id,
          ket_qua_bieu_quyet: item.ket_qua_bieu_quyet || 'TAN_THANH'
        }
      })
    }
    else {
      vote_item_status = vote.vote_items.filter(item => vote.item_vote.includes(item.id)).map(item => {
        return {
          id: item.id,
          so_phieu_bau: item.so_phieu_bau || user.value.so_phieu_bau_toi_da
        }
      })
    }
    data.vote_item_status = vote_item_status

    if (vote.loai === 'BAU_CU' && data.vote_item_status.length <= 0) {
      showToast('error', 'Vui lòng chọn ứng viên bầu cử.');
      return
    }
    currentVote.value = vote
    currentData.value = data
    openModal()
  }

  const confirmSend = async () => {
    loading.value = true
    vote(currentData.value).then(res => {
      showToast('success', 'Thành công, Cổ đông vui lòng in phiếu và gửi kết quả về ban tổ chức');
      fetchVotes()
    }).catch(error => {
      showToast('error', error.error.response.data.meta.message);
      currentData.value = {}
      currentVote.value = {}
    }).finally(() => {
      loading.value = false
      closeModal()
    })
  }

  const selectCustomer = (payload) => {
    const vote = payload.vote
    const idCustomer = payload.id
    vote.vote_customer.push(idCustomer)
  }
  const selectItemVote = (vote) => {
  }

  const getStatus = (vote) => {
    if (vote.trang_thai === 'DONG_PHIEU') {
      return {
        class_css: 'close',
        label: 'Đóng phiếu'
      }
    } else if (vote.trang_thai === 'DANG_MO' && vote.is_voted) {
      return {
        class_css: 'voted',
        label: 'Đã gửi'
      }
    } else if (vote.trang_thai === 'DANG_MO' && !vote.is_voted) {
      return {
        class_css: 'opening',
        label: 'Đang mở'
      }
    }
  }

  const reSend = (vote) => {
    loading.value = true
    reVote({vote_id: vote.id}).then(res => {
      showToast('success', 'Đã hủy kết quả gửi phiếu')
      fetchVotes()
    }).catch(error => {
      showToast('error', error.error.response.data.meta.message)
    }).finally(() => {
      loading.value = false
    })
  }

  const goToPrintPage = (documentType) => {
    // Tạo URL với query parameters
    const route = router.resolve({
      path: '/print-confirmation',
      query: {
        autoprint: 'true',
        documentType: documentType,
      },
    });

    // Mở tab mới với URL đã tạo
    window.open(route.href, '_blank');
  }

  const changeSoPhieu = (item) => {
    if (item.so_phieu_bau > user.value.so_phieu_bau_toi_da) {
      item.so_phieu_bau = user.value.so_phieu_bau_toi_da
    }
  }

</script>

<style>
.collapse-arrow > .collapse-title:after {
  top: 1.6rem !important;
  left: 0;
}
.text-status {
  font-weight: 500;
  font-size: 14px;
}
.close {
  color: #9f9f9f;
}
.opening {
  color: #00BC37;
}
.remain {
  color: white;
}
.end {
  color: #FF152B;
}
.voted {
  color: #FEC318;
}
.btn-resend {
  background: white !important;
  color: #006FFF !important;
  text-transform: uppercase;
  min-width: 200px;
}
.btn-send {
  background-image: linear-gradient(#1C9AD6, #006FFF) !important;
  color: white !important;
  text-transform: uppercase;
  min-width: 200px;
}
.voting-option {
  flex-direction: column-reverse;
}
@media (max-width: 640px) {
  .btn-send,.btn-resend {
    min-width: 50%;
  }
  .voting-header {
    flex-direction: column; /* Xếp dọc các thông tin số phiếu */
    gap: 1rem;
    align-items: flex-start;
  }
  .voting-header .flex {
    flex-direction: column; /* Xếp dọc các mục trong header */
    gap: 0.5rem;
  }
  .candidate-item {
    flex-direction: column; /* Xếp dọc các phần tử trong danh sách ứng cử viên */
    align-items: flex-start;
    gap: 0.5rem;
    padding: 1rem 0; /* Tăng padding dọc để dễ nhìn */
  }
  .candidate-item-left {
    width: 100%;
  }
  .candidate-info {
    flex-direction: column; /* Xếp dọc tên và chức tịch */
    align-items: start !important;
    /*gap: 0.25rem;*/
  }
  .candidate-info p {
    width: auto; /* Xóa width cố định trên mobile để tự động co giãn */
  }
  .votes-input {
    width: 100%; /* Ô input chiếm toàn bộ chiều rộng */
    justify-content: space-between; /* Căn chỉnh nhãn và input */
  }
  .votes-input input {
    min-width: 120px; /* Giảm chiều rộng tối thiểu của ô input trên mobile */
  }
  .text-sm {
    font-size: 0.875rem; /* Giảm kích thước chữ trên mobile */
  }
  .voting-options {
    flex-direction: column; /* Xếp dọc các radio buttons */
    gap: 2rem !important; /* Giảm khoảng cách giữa các tùy chọn */
    align-items: center; /* Căn giữa */
    min-width: 0; /* Xóa min-width trên mobile để tự co giãn */
  }
}
</style>
