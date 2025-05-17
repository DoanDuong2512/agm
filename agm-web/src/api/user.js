import apiClient from "./base";

export const getListUsers = (params = {}) => {
    return apiClient.get('/staffs', { params })
}