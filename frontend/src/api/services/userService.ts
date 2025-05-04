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

  async getAllUsers() {
    const response = await apiClient.get(API_ENDPOINTS.USERS.GET_ALL)
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

  async deleteUser(id: number) {
    const response = await apiClient.post(API_ENDPOINTS.USERS.DELETE, { id })
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
  }
}
