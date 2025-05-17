<template>
  <div class="auth-layout">
    <div class="auth-container">
        <div class="auth-header">
            <img src="@/assets/images/logo_elcom.svg" alt="Logo" class="logo" />
            <h1 class="company-name">CÔNG TY CỔ PHẦN CÔNG NGHỆ - VIỄN THÔNG ELCOM</h1>
        </div>
        <slot></slot>
    </div>
  </div>
</template>

<script setup>
// Không cần logic ở đây
</script>

<style scoped>
.auth-layout {
  min-height: 100vh;
  display: flex;
  justify-content: center;
  background: url('@/assets/images/auth_background.png') no-repeat center center;
  background-size: cover;
  padding-top: 6rem;
}

.auth-container {
  text-align: center;
  width: 100%;
  max-width: 1000px;
  display: flex;
  flex-direction: column;
  align-items: center;
}
.auth-header {
  display: flex;
  flex-direction: column;
  align-items: center;
}
.logo {
  max-width: 200px;
  margin-bottom: 1rem;
}

.company-name {
  color: white;
  font-size: 1.2rem;
  margin-bottom: 2rem;
  text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
}

@media (max-width: 768px) {
  .company-name {
    font-size: 1rem;
  }
  .auth-layout {
    align-items: center;
    padding: 0 2rem;
  }
}
</style>