import { API_CONFIG, API_ENDPOINTS } from '@/config/api';
import type {
  LoginRequest,
  LoginResponse,
  LogoutResponse,
  Patient,
  ApiError,
  PatientProfile,
  UpdateProfileRequest,
  TaskCompletion,
  UpdateTaskCompletionRequest,
  PatientItem,
  Item,
} from '@/types/api';
import { tokenStorage } from './tokenStorage';

class ApiClient {
  private baseURL: string;
  private timeout: number;

  constructor() {
    this.baseURL = API_CONFIG.BASE_URL;
    this.timeout = API_CONFIG.TIMEOUT;
  }

  private async fetchWithTimeout(
    url: string,
    options: RequestInit = {},
  ): Promise<Response> {
    const controller = new AbortController();
    const timeoutId = setTimeout(() => controller.abort(), this.timeout);

    try {
      const response = await fetch(url, {
        ...options,
        signal: controller.signal,
      });
      clearTimeout(timeoutId);
      return response;
    } catch (error) {
      clearTimeout(timeoutId);
      if ((error as Error).name === 'AbortError') {
        throw new Error('Request timeout');
      }
      throw error;
    }
  }

  private async request<T>(
    endpoint: string,
    options: RequestInit = {},
  ): Promise<T> {
    const url = `${this.baseURL}${endpoint}`;
    const token = await tokenStorage.getToken();

    const headers: HeadersInit = {
      'Content-Type': 'application/json',
      ...options.headers,
    };

    if (token) {
      headers['Authorization'] = `Bearer ${token}`;
    }

    try {
      const response = await this.fetchWithTimeout(url, {
        ...options,
        headers,
      });

      const data = await response.json();

      if (!response.ok) {
        const error: ApiError = {
          message: data.message || 'An error occurred',
          errors: data.errors,
        };
        throw error;
      }

      return data as T;
    } catch (error) {
      if ((error as ApiError).message) {
        throw error;
      }
      throw new Error('Network error. Please check your connection.');
    }
  }

  async login(pairingCode: string): Promise<LoginResponse> {
    const body: LoginRequest = {
      pairing_code: pairingCode,
    };

    const response = await this.request<LoginResponse>(API_ENDPOINTS.LOGIN, {
      method: 'POST',
      body: JSON.stringify(body),
    });

    await tokenStorage.setToken(response.token);
    return response;
  }

  async logout(): Promise<void> {
    try {
      await this.request<LogoutResponse>(API_ENDPOINTS.LOGOUT, {
        method: 'POST',
      });
    } finally {
      await tokenStorage.removeToken();
    }
  }

  async getCurrentPatient(): Promise<Patient> {
    return this.request<Patient>(API_ENDPOINTS.ME, {
      method: 'GET',
    });
  }

  // Profile Methods
  async getProfile(): Promise<PatientProfile> {
    return this.request<PatientProfile>('/patient/profile', {
      method: 'GET',
    });
  }

  async updateProfile(data: UpdateProfileRequest): Promise<{ message: string; patient: Patient }> {
    return this.request<{ message: string; patient: Patient }>('/patient/profile', {
      method: 'PATCH',
      body: JSON.stringify(data),
    });
  }

  // Task Methods
  async getCurrentTasks(): Promise<{ data: TaskCompletion[] }> {
    return this.request<{ data: TaskCompletion[] }>('/patient/tasks/current', {
      method: 'GET',
    });
  }

  async updateTaskCompletion(
    taskCompletionId: number,
    data: UpdateTaskCompletionRequest
  ): Promise<{ data: TaskCompletion }> {
    return this.request<{ data: TaskCompletion }>(
      `/patient/tasks/completions/${taskCompletionId}`,
      {
        method: 'PATCH',
        body: JSON.stringify(data),
      }
    );
  }

  async completeTask(taskCompletionId: number): Promise<{ data: TaskCompletion }> {
    return this.updateTaskCompletion(taskCompletionId, {
      status: 'completed',
      completed_at: new Date().toISOString(),
    });
  }

  async skipTask(taskCompletionId: number): Promise<{ data: TaskCompletion }> {
    return this.updateTaskCompletion(taskCompletionId, {
      status: 'skipped',
    });
  }

  async failTask(taskCompletionId: number): Promise<{ data: TaskCompletion }> {
    return this.updateTaskCompletion(taskCompletionId, {
      status: 'failed',
    });
  }

  // Inventory Methods
  async getInventory(): Promise<{ data: PatientItem[] }> {
    return this.request<{ data: PatientItem[] }>('/patient/inventory', {
      method: 'GET',
    });
  }

  // Shop Methods
  async getShopItems(): Promise<{ data: Item[] }> {
    return this.request<{ data: Item[] }>('/items', {
      method: 'GET',
    });
  }

  // Generic HTTP methods for testing/flexibility
  async get<T = any>(endpoint: string): Promise<T> {
    return this.request<T>(endpoint, {
      method: 'GET',
    });
  }

  async post<T = any>(endpoint: string, body?: any): Promise<T> {
    return this.request<T>(endpoint, {
      method: 'POST',
      body: body ? JSON.stringify(body) : undefined,
    });
  }

  async put<T = any>(endpoint: string, body?: any): Promise<T> {
    return this.request<T>(endpoint, {
      method: 'PUT',
      body: body ? JSON.stringify(body) : undefined,
    });
  }

  async patch<T = any>(endpoint: string, body?: any): Promise<T> {
    return this.request<T>(endpoint, {
      method: 'PATCH',
      body: body ? JSON.stringify(body) : undefined,
    });
  }

  async delete<T = any>(endpoint: string): Promise<T> {
    return this.request<T>(endpoint, {
      method: 'DELETE',
    });
  }
}

export const apiClient = new ApiClient();
