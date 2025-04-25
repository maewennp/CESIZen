import apiClient from '../index'
import { API_ENDPOINTS } from '../endpoints'

export const userService = {
  async getProfile(token: string) {
    const response = await apiClient.get(API_ENDPOINTS.USERS.GET_PROFILE, {
      headers: {
        Authorization: `Bearer ${token}`
      }
    })
    return response.data
  }
}
