import * as SecureStore from 'expo-secure-store';
import { Platform } from 'react-native';

const TOKEN_KEY = 'auth_token';

const isWeb = Platform.OS === 'web';

export const tokenStorage = {
  async getToken(): Promise<string | null> {
    try {
      if (isWeb) {
        return localStorage.getItem(TOKEN_KEY);
      }
      return await SecureStore.getItemAsync(TOKEN_KEY);
    } catch (error) {
      console.error('Error getting token:', error);
      return null;
    }
  },

  async setToken(token: string): Promise<void> {
    try {
      if (isWeb) {
        localStorage.setItem(TOKEN_KEY, token);
        return;
      }
      await SecureStore.setItemAsync(TOKEN_KEY, token);
    } catch (error) {
      console.error('Error setting token:', error);
      throw error;
    }
  },

  async removeToken(): Promise<void> {
    try {
      if (isWeb) {
        localStorage.removeItem(TOKEN_KEY);
        return;
      }
      await SecureStore.deleteItemAsync(TOKEN_KEY);
    } catch (error) {
      console.error('Error removing token:', error);
    }
  },
};
