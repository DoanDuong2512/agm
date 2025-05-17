import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './App.vue';
import router from './router';
import Toast from "vue-toastification";
import './style.css';
import "vue-toastification/dist/index.css";

const toastificationOptions = {
    position: "top-right", // Vị trí hiển thị
    timeout: 3000,        // Thời gian tự động đóng (ms)
    closeOnClick: true,   // Đóng khi click
    pauseOnFocusLoss: true,
    pauseOnHover: true,
    draggable: true,
    draggablePercent: 0.6,
    showCloseButtonOnHover: false,
    hideProgressBar: false,
    closeButton: "button",
    icon: true,
    rtl: false
};

const app = createApp(App)
const pinia = createPinia();

app.use(pinia)
app.use(router)
app.use(Toast, toastificationOptions)
app.mount('#app')
