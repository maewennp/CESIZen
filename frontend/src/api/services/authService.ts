import apiClient from '../index';
import { API_ENDPOINTS } from '../endpoints';

export const authService = {
  async login(email: string, password: string) {
    const response = await apiClient.post(API_ENDPOINTS.AUTH.LOGIN, { email, password });
    return response.data;
  },

  async register(username: string, email: string, password: string) {
    const response = await apiClient.post(API_ENDPOINTS.AUTH.REGISTER, { 
      username, 
      email, 
      password 
    });
    return response.data;
  },

  async forgotPassword(email: string) {
    const response = await apiClient.post(API_ENDPOINTS.AUTH.FORGOT_PASSWORD, { email });
    return response.data;
  },

  async resetPassword(newPassword: string, token: string) {
    const response = await apiClient.post(API_ENDPOINTS.AUTH.RESET_PASSWORD, {
      new_password: newPassword,
      reset_token: token,
    });
    return response.data;
  }
};
