// API Types based on OpenAPI specification

export interface Patient {
  id: number;
  username: string;
  pairing_code?: string | null;
  paired_at?: string | null;
  avatar_id?: number | null;
  avatar?: Avatar;
  experience: number;
  gems: number;
  device_identifier?: string | null;
  created_at: string;
  updated_at: string;
}

export interface Avatar {
  id: number;
  name: string;
  description?: string | null;
  base_path: string;
  layer_count: number;
  is_default: boolean;
  layers?: AvatarLayer[];
}

export interface AvatarLayer {
  id: number;
  layer_number: number;
  layer_name: string;
  image_path: string;
}

export interface LoginRequest {
  pairing_code: string;
  device_identifier?: string | null;
}

export interface LoginResponse {
  token: string;
  patient: Patient;
}

export interface LogoutResponse {
  message: string;
}

export interface ValidationError {
  message: string;
  errors: {
    [key: string]: string[];
  };
}

export interface ApiError {
  message: string;
  errors?: {
    [key: string]: string[];
  };
}

export interface ApiResponse<T> {
  data?: T;
  error?: ApiError;
}

// Profile Types
export interface PatientProfile {
  id: number;
  username: string;
  avatar_id?: number | null;
  avatar?: Avatar;
  experience: number;
  gems: number;
  created_at: string;
  stats: {
    total_xp: number;
    total_gems: number;
    active_subscriptions: number;
    tasks_completed_today: number;
    tasks_completed_this_week: number;
    tasks_completed_total: number;
    current_streak: number;
  };
}

export interface UpdateProfileRequest {
  username?: string;
  avatar_id?: number;
}

// Task Types
export interface Task {
  id: number;
  name: string;
  description?: string | null;
  xp_value: number;
  gem_value: number;
  created_at: string;
  updated_at: string;
}

export interface TaskSummary {
  id: number;
  name: string;
  description?: string | null;
  xp_value: number;
  gem_value: number;
}

export interface Subscription {
  interval_days: number;
  timezone: string;
  is_active: boolean;
}

export interface TaskCompletion {
  id: number;
  subscription_id: number;
  scheduled_for: string;
  completed_at?: string | null;
  status: 'pending' | 'completed' | 'skipped' | 'failed';
  task: TaskSummary;
  subscription: Subscription;
}

export interface UpdateTaskCompletionRequest {
  status?: 'pending' | 'completed' | 'skipped' | 'failed';
  completed_at?: string | null;
}

// Shop & Inventory Types
export interface Item {
  id: number;
  name: string;
  description?: string | null;
  price: number;
  image?: string | null;
  category?: string | null;
}

export interface ItemSummary {
  id: number;
  name: string;
  description?: string | null;
  price: number;
  image?: string | null;
  category?: string | null;
}

export interface PatientItem {
  patient_id: number;
  item_id: number;
  equipped: boolean;
  item: ItemSummary;
}
