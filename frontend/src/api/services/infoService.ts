import apiClient from '../index';
import { API_ENDPOINTS } from '../endpoints';
import type { Info, CreateInfoPayload, UpdateInfoPayload }  from '../interfaces/Info';

export const infoService = {
  async getAllVisible(): Promise<Info[]> {
    const response = await apiClient.get<Info[]>(API_ENDPOINTS.INFO.GET_ALL_VISIBLE);
    return response.data;
  },

  async getOne(id_content: number): Promise<Info> {
    const response = await apiClient.get<Info>(API_ENDPOINTS.INFO.GET_ONE, {
      params: { id_content }
    });
    return response.data;
  },

  async adminGetAll(token: string): Promise<Info[]> {
    const response = await apiClient.get<Info[]>(API_ENDPOINTS.INFO.ADMIN_GET_ALL, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async create(data: CreateInfoPayload, token: string): Promise<Info> {
    const response = await apiClient.post<Info>(API_ENDPOINTS.INFO.CREATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async update(data: UpdateInfoPayload, token: string): Promise<Info> {
    const response = await apiClient.put<Info>(API_ENDPOINTS.INFO.UPDATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async delete(id_content: number, token: string): Promise<void> {
    await apiClient.delete(API_ENDPOINTS.INFO.DELETE, {
      headers: { Authorization: `Bearer ${token}` },
      data: { id_content }
    });
  },

  async toggleVisibility(id_content: number, token: string): Promise<Info> {
    const response = await apiClient.put<Info>(API_ENDPOINTS.INFO.TOGGLE_VISIBILITY, { id_content }, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },
};
