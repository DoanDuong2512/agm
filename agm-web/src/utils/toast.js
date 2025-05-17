import { useToast } from "vue-toastification";

const toast = useToast();

export const showSuccess = (message = "Success!", timeout = 3000) => {
  toast.success(message, { timeout });
};

export const showError = (message = "Error!", timeout = 3000) => {
  toast.error(message, { timeout });
};

export const showToast = (type, message = "Toast!", timeout = 3000) => {
  if (type === "success") {
    showSuccess(message, timeout);
  }
  else if (type === "error") {
    showError(message, timeout);
  }
  else {
    toast(message, { timeout });
  }
}