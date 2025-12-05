import React, { useState } from 'react';
import { Button, Image, StyleSheet, Text, View } from 'react-native';

// 1. Define all your available character options
const characterAssets = {
  dog: {
    name: 'Rowdy',
    image: require('../assets/icons/rowdyPFP.svg'), 
  },
  axolotl: {
    name: 'Axolotl',
    image: require('../assets/icons/axolotlPFP.svg'),
  },
};

type CharacterKey = keyof typeof characterAssets;

export default function CharacterSelector() {
  
  // 2. Set up the state variable
  const [selectedCharKey, setSelectedCharKey] = useState<CharacterKey>('dog');

  // Get the full data object for the currently selected character
  const currentCharacter = characterAssets[selectedCharKey];

  return (
    <View style={styles.container}>
      <Image 
        source={currentCharacter.image} 
        style={styles.characterImage} 
      />
      
      <Text style={styles.characterName}>
        {currentCharacter.name}
      </Text>

      {/* 4. These buttons act as the "triggers" */}
      <View style={styles.buttonContainer}>
        <Button
          title="Select Rowdy"
          onPress={() => setSelectedCharKey('dog')}
          disabled={selectedCharKey === 'dog'} 
        />
        <Button
          title="Select Axolotl"
          onPress={() => setSelectedCharKey('axolotl')}
          disabled={selectedCharKey === 'axolotl'} 
        />
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  characterImage: {
    width: 200,
    height: 300,
    resizeMode: 'contain',
    marginBottom: 20,
  },
  characterName: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 20,
  },
  buttonContainer: {
    flexDirection: 'row',
    gap: 15, 
  },
});