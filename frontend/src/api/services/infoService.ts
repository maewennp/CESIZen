import apiClient from '../index';
import { API_ENDPOINTS } from '../endpoints';

export const infoService = {
  async getAllVisible() {
    const response = await apiClient.get(API_ENDPOINTS.INFO.GET_ALL_VISIBLE);
    return response.data;
  },

  async getOne(id_content: number) {
    const response = await apiClient.get(API_ENDPOINTS.INFO.GET_ONE, {
      params: { id_content }
    });
    return response.data;
  },

  async adminGetAll(token: string) {
    const response = await apiClient.get(API_ENDPOINTS.INFO.ADMIN_GET_ALL, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async create(data: any, token: string) {
    const response = await apiClient.post(API_ENDPOINTS.INFO.CREATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async update(data: any, token: string) {
    const response = await apiClient.put(API_ENDPOINTS.INFO.UPDATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async delete(id_content: number, token: string) {
    const response = await apiClient.delete(API_ENDPOINTS.INFO.DELETE, {
      headers: { Authorization: `Bearer ${token}` },
      data: { id_content }
    });
    return response.data;
  },

  async toggleVisibility(id_content: number, token: string) {
    const response = await apiClient.put(API_ENDPOINTS.INFO.TOGGLE_VISIBILITY, { id_content }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },
};
