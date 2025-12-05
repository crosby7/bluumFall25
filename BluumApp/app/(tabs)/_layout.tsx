import { Tabs } from 'expo-router';
import React from 'react';
import { Text } from 'react-native';

import { Navbar } from '../../components/NavBar';

export default function TabLayout() {
  return (
    <Tabs screenOptions={{ headerShown: false}}
            tabBar={(props) => <Navbar {...props} />}
            >
      <Tabs.Screen
        name="home"
        options={{
          title: 'Home',
          tabBarIcon: ({ color }) => <Text style={{ color, fontSize: 20 }}>🏠</Text>, 
        }}
      />
      <Tabs.Screen
        name="profile"
        options={{
          title: 'Profile',
          tabBarIcon: ({ color }) => <Text style={{ color, fontSize: 20 }}>👤</Text>,
        }}
      />
       <Tabs.Screen
        name="shop"
        options={{
          title: 'Shop',
          tabBarIcon: ({ color }) => <Text style={{ color, fontSize: 20 }}>🛍️</Text>,
        }}
      />
    </Tabs>
  );
}