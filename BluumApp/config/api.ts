// API Configuration

/**
 * Get the base API URL dynamically based on the environment
 * - Development: Uses EXPO_PUBLIC_API_URL (set by dev script)
 * - Production: Uses production URL
 * - Fallback: localhost for web development
 */
const getBaseURL = (): string => {
  // Development - use environment variable set by dev script
  if (process.env.EXPO_PUBLIC_API_URL) {
    return process.env.EXPO_PUBLIC_API_URL;
  }

  // Production builds
  if (process.env.NODE_ENV === 'production') {
    return 'https://your-production-url.com/api';
  }

  // Fallback for web development
  return 'http://localhost:8000/api';
};

export const API_CONFIG = {
  BASE_URL: getBaseURL(),
  TIMEOUT: 10000, // 10 seconds
};

// Log the API URL in development
if (__DEV__) {
  console.log('[API] Base URL:', API_CONFIG.BASE_URL);
}

export const API_ENDPOINTS = {
  LOGIN: '/login',
  LOGOUT: '/logout',
  ME: '/me',
  PROFILE: '/patient/profile',
  TASKS_CURRENT: '/patient/tasks/current',
  INVENTORY: '/patient/inventory',
  ITEMS: '/items',
} as const;

/**
 * Get the base URL without the /api suffix for asset loading
 * @returns The base backend URL
 */
export const getAssetBaseURL = (): string => {
  const apiUrl = getBaseURL();
  // Remove /api suffix if present
  return apiUrl.replace(/\/api$/, '');
};
