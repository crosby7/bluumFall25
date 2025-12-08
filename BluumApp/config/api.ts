// API Configuration

export const API_CONFIG = {
  BASE_URL: 'http://bluum.test/api',
  TIMEOUT: 10000, // 10 seconds
};

export const API_ENDPOINTS = {
  LOGIN: '/login',
  LOGOUT: '/logout',
  ME: '/me',
  PROFILE: '/patient/profile',
  TASKS_CURRENT: '/patient/tasks/current',
  INVENTORY: '/patient/inventory',
  ITEMS: '/items',
} as const;
