import { Button, Text, View } from 'react-native';
import { useAuth } from '../../context/AuthContext';

export default function ProfileScreen() {
  
  const { signOut } = useAuth();

  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
      <Text>Profile Settings Go Here</Text>

        <Button 
        title="Sign Out Now"
        color="red"
        onPress={signOut}
      />
    </View>
  );
}