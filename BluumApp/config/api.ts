// API Configuration

export const API_CONFIG = {
  // BASE_URL: 'http://bluum.test/api', //Base URL on Herd
  BASE_URL: 'http://10.25.202.84:8000/api', // Base URL on Herd
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
