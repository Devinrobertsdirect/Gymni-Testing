import AsyncStorage from '@react-native-async-storage/async-storage';
import { BASE_API_URL } from '../config/api';

const API_CLIENT = {
  async get<T>(endpoint: string, requiresAuth: boolean = false): Promise<T> {
    return this.request<T>('GET', endpoint, null, requiresAuth);
  },

  async post<T>(endpoint: string, data: any, requiresAuth: boolean = false): Promise<T> {
    return this.request<T>('POST', endpoint, data, requiresAuth);
  },

  async put<T>(endpoint: string, data: any, requiresAuth: boolean = false): Promise<T> {
    return this.request<T>('PUT', endpoint, data, requiresAuth);
  },

  async delete<T>(endpoint: string, requiresAuth: boolean = false): Promise<T> {
    return this.request<T>('DELETE', endpoint, null, requiresAuth);
  },

  async request<T>(method: string, endpoint: string, data: any = null, requiresAuth: boolean = false): Promise<T> {
    const url = `${BASE_API_URL}/${endpoint}`;
    const headers: HeadersInit = {
      'Content-Type': 'application/json',
    };

    if (requiresAuth) {
      const token = await AsyncStorage.getItem('userToken');
      if (token) {
        headers['Authorization'] = `Bearer ${token}`;
      } else {
        throw new Error('Authentication token not found.');
      }
    }

    const config: RequestInit = {
      method,
      headers,
      ...(data && { body: JSON.stringify(data) }),
    };

    try {
      const response = await fetch(url, config);
      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.message || 'Something went wrong!');
      }

      return responseData;
    } catch (error) {
      console.error('API Request Error:', error);
      throw error;
    }
  },
};

export default API_CLIENT;