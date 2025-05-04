import apiClient from '../index'
import { API_ENDPOINTS } from '../endpoints'
import type { User } from '../interfaces/User'

export const userService = {
  async getProfile(token: string) {
    const response = await apiClient.get(API_ENDPOINTS.USERS.GET_PROFILE, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    return response.data
  },

  async getUserById(id: number) {
    const response = await apiClient.get(API_ENDPOINTS.USERS.GET_ONE, {
      params: { id }
    })
    return response.data
  },

  async updateProfile(token: string, updatedData: User) {
    const response = await apiClient.put(API_ENDPOINTS.USERS.UPDATE, updatedData, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    return response.data
  },

  async toggleUserActive(id: number) {
    const response = await apiClient.post(API_ENDPOINTS.USERS.TOGGLE_ACTIVE, { id })
    return response.data
  },

  async changePassword(token: string, payload: { id_user: number, old_password: string, new_password: string }) {
    const response = await apiClient.put(API_ENDPOINTS.USERS.CHANGE_PASSWORD, payload, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    return response.data
  },

  // ======================== Gestion ADMIN =========================

  async getAllUsers(token: string) {
    const response = await apiClient.get(API_ENDPOINTS.USERS.GET_ALL, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    return response.data
  },

  async createUser(token: string, data: any) {
    const response = await apiClient.post(API_ENDPOINTS.USERS.CREATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    })
    return response.data
  },

  async updateUser(token: string, id_user: number, data: any) {
    const response = await apiClient.put(API_ENDPOINTS.USERS.UPDATE, data, {
      headers: { Authorization: `Bearer ${token}` }
    })
    return response.data
  },

  async deleteUser(token: string, id_user: number) {
    const response = await apiClient.delete(API_ENDPOINTS.USERS.DELETE, {
      headers: { Authorization: `Bearer ${token}` },
      data: { id_user }, 
    })
    return response.data
  },

  async toggleUser(token: string, id_user: number) {
    const response = await apiClient.put(
      API_ENDPOINTS.USERS.TOGGLE_ACTIVE,
      { id_user }, 
      {
        headers: { Authorization: `Bearer ${token}` }
      }
    )
    return response.data
  }
}
