import { saveToLocalStorage, getFromLocalStorage, removeFromLocalStorage } from "./localStorage";

export const saveToken = (token) => {
    saveToLocalStorage('access_token', token);
};

export const getToken = () => {
    return getFromLocalStorage('access_token');
};

export const clearToken = () => {
    removeFromLocalStorage('access_token');
};

export const saveUser = (user) => {
    saveToLocalStorage('user', user);
}

export const removeUser = () => {
    removeFromLocalStorage('user')
}

export const getUser = () => {
    return getFromLocalStorage('user');
};