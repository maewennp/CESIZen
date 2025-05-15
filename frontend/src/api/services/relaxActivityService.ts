import apiClient from '../index';
import { API_ENDPOINTS } from '../endpoints';
import type { CreateRelaxActivityPayload, RelaxActivity, UpdateRelaxActivityPayload } from '../interfaces/RelaxActivity';

export const relaxActivityService = {
  async getAllActive(): Promise<RelaxActivity[]> {
    const response = await apiClient.get<RelaxActivity[]>(API_ENDPOINTS.RELAX_ACTIVITY.GET_ALL_ACTIVE);
    return response.data;
  },

   // Récupère une activité par son ID
  async getOne(id_activity: number): Promise<RelaxActivity> {
    const response = await apiClient.get<RelaxActivity>(
      API_ENDPOINTS.RELAX_ACTIVITY.GET_ONE,
      { params: { id_activity } }
    );
    return response.data;
  },

  // Récupère toutes les activités (mode admin)
  async adminGetAll(token: string): Promise<RelaxActivity[]> {
    const response = await apiClient.get<RelaxActivity[]>(API_ENDPOINTS.RELAX_ACTIVITY.ADMIN_GET_ALL, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  // Crée une nouvelle activité
  async create(data: CreateRelaxActivityPayload, token: string) {
    const response = await apiClient.post<RelaxActivity>(API_ENDPOINTS.RELAX_ACTIVITY.CREATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  // Met à jour une activité existante
  async update(data: UpdateRelaxActivityPayload, token: string) {
    const response = await apiClient.put<RelaxActivity>(API_ENDPOINTS.RELAX_ACTIVITY.UPDATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    });
    return response.data;
  },

  async delete(id_activity: number, token: string): Promise<void> {
    await apiClient.delete(API_ENDPOINTS.RELAX_ACTIVITY.DELETE, {
      headers: { Authorization: `Bearer ${token}` },
      data: { id_activity }
    });
  },

  // Active/désactive une activité
  async toggleActive(id_activity: number, token: string): Promise<RelaxActivity> {
    const response = await apiClient.put<RelaxActivity>(API_ENDPOINTS.RELAX_ACTIVITY.TOGGLE_ACTIVE, 
      { id_activity }, 
      {
        headers: { Authorization: `Bearer ${token}` }
      });
    return response.data;
  },
};
