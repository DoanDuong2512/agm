<template>
  <footer class="bg-[#0A2476] text-white py-4">
    <div class="container mx-auto px-4">
      <!-- Mobile layout: stacked columns -->
      <div class="md:hidden">
        <div class="flex flex-col items-center mb-4">
          <!-- Logo -->
          <div class="mb-3">
            <img src="@/assets/images/logo_elcom.png" alt="Elcom" class="h-8" />
          </div>
          
          <!-- Company Name -->
          <h3 class="font-bold text-sm text-center">
            CÔNG TY CỔ PHẦN CÔNG NGHỆ - VIỄN THÔNG ELCOM
          </h3>
        </div>
        
        <div class="flex flex-col items-center mb-4">
          <!-- Phone -->
          <div class="flex items-center mb-2 gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span class="text-sm whitespace-nowrap">+84 2438 359 359 / +84 24 38 355 884</span>
          </div>
          
          <!-- Email -->
          <div class="flex items-center gap-2 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-sm">contact@elcom.com.vn</span>
          </div>
        </div>
        
        <div class="flex items-center justify-center">
          <span class="text-sm">Developed by <b>Synexis</b></span>
        </div>
      </div>

      <!-- Desktop layout: 2 rows with 3 columns -->
      <div class="hidden md:block">
        <!-- First row: Logo and Phone -->
        <div class="grid grid-cols-3 gap-4 mb-4">
          <!-- Logo in first column -->
          <div class="flex justify-start items-center">
            <img src="@/assets/images/logo_elcom.png" alt="Elcom" class="h-8" />
          </div>
          
          <!-- Phone in second column -->
          <div class="flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
            </svg>
            <span class="text-sm whitespace-nowrap">+8424 38359359</span>
          </div>
          
          <!-- Empty third column -->
          <div></div>
        </div>
        
        <!-- Second row: Company name, Email, Developer -->
        <div class="grid grid-cols-3 gap-4">
          <!-- Company name in first column -->
          <div>
            <h3 class="font-bold text-base text-left">
              CÔNG TY CỔ PHẦN CÔNG NGHỆ - VIỄN THÔNG ELCOM
            </h3>
          </div>
          
          <!-- Email in second column -->
          <div class="flex items-center justify-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
            <span class="text-sm">agm@elcom.com.vn</span>
          </div>
          
          <!-- Developer in third column -->
          <div class="flex items-center justify-end">
            <span class="text-sm">Developed by <b>Synexis</b></span>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  name: 'Footer'
}
</script>
