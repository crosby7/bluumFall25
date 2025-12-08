import { Stack, useRouter, useSegments } from 'expo-router';
import React, { useEffect } from 'react';
import { AuthProvider, useAuth } from '../context/AuthContext';
import { CharacterProvider } from '@/context/CharacterContext';

function RootLayoutContent() {
  const { isLoggedIn } = useAuth();
  const segments = useSegments();
  const router = useRouter();

  useEffect(() => {
    const inTabsGroup = segments[0] === '(tabs)';

    if (isLoggedIn && !inTabsGroup) {

      // If the user is signed in and not in the protected '(tabs)' group,
      // redirect them to the home screen.
      router.replace('/(tabs)/home');
    } else if (!isLoggedIn && inTabsGroup) {
      
      // If the user is not signed in and tries to access a protected route,
      // redirect them to the login screen.
      router.replace('/');
    }
  }, [isLoggedIn, segments]); 

  return (
    <Stack screenOptions={{ headerShown: false }}>
        <Stack.Screen name="index" />
        <Stack.Screen name="(tabs)" />
    </Stack>
  );
}

// Wrap the whole app in the AuthProvider
export default function RootLayout() {
  return (
    <AuthProvider>
      <CharacterProvider>     
      <RootLayoutContent/>  
      </CharacterProvider>
    </AuthProvider>
  );
}