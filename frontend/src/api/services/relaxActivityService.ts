import apiClient from '../index';
import { API_ENDPOINTS } from '../endpoints';

export const relaxActivityService = {
  async getAllActive() {
    const response = await apiClient.get(API_ENDPOINTS.RELAX_ACTIVITY.GET_ALL_ACTIVE);
    return response.data;
  },

  async getOne(id_activity: number) {
    const response = await apiClient.get(API_ENDPOINTS.RELAX_ACTIVITY.GET_ONE + `?id_activity=${id_activity}`);
    return response.data;
  },

  async adminGetAll(token: string) {
    const response = await apiClient.get(API_ENDPOINTS.RELAX_ACTIVITY.ADMIN_GET_ALL, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async create(data: any, token: string) {
    const response = await apiClient.post(API_ENDPOINTS.RELAX_ACTIVITY.CREATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async update(data: any, token: string) {
    const response = await apiClient.put(API_ENDPOINTS.RELAX_ACTIVITY.UPDATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async delete(id_activity: number, token: string) {
    const response = await apiClient.delete(API_ENDPOINTS.RELAX_ACTIVITY.DELETE, {
      headers: { Authorization: `Bearer ${token}` },
      data: { id_activity }
    });
    return response.data;
  },

  async toggleActive(id_activity: number, token: string) {
    const response = await apiClient.put(API_ENDPOINTS.RELAX_ACTIVITY.TOGGLE_ACTIVE, { id_activity }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },
};
