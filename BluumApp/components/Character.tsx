import React from 'react';
import { View, Image, StyleSheet, ViewStyle } from 'react-native';
import { 
  BASE_CHARACTERS, 
  COSMETIC_ASSETS, 
  LAYER_ORDER, 
  CharacterSlot 
} from '../data/characterAssets'; // Adjust path

type CharacterProps = {
  characterId: 'dog' | 'axolotl'; // Which base character?
  equippedItems?: Record<string, boolean>; // ex: { 'item_beanie': true, 'item_tuxedo': true }
  style?: ViewStyle;
  scale?: number;
};

export const Character = ({ 
  characterId, 
  equippedItems = {}, 
  style, 
  scale = 1 
}: CharacterProps) => {

  const baseData = BASE_CHARACTERS[characterId];

  // 1. "Compile" the current look
  // We start with the base character, then overlay equipped items
  const compiledLayers: Partial<Record<CharacterSlot, any>> = { ...baseData };

  // 2. Loop through equipped item IDs to find overrides
  Object.keys(equippedItems).forEach((itemId) => {
    const cosmeticData = COSMETIC_ASSETS[itemId];
    if (cosmeticData) {
      // If the user has 'item_tuxedo', this merges { torso: tuxedo_img } 
      // into our compiled layers, replacing the dog's default torso.
      Object.assign(compiledLayers, cosmeticData);
    }
  });

  return (
    <View style={[styles.container, { transform: [{ scale }] }, style]}>
      {/* 3. Render Layers in Order (Back to Front) */}
      {LAYER_ORDER.map((slot) => {
        const imageSource = compiledLayers[slot];

        // If this slot is empty (e.g., no hat equipped), render nothing
        if (!imageSource) return null;

        return (
          <Image
            key={slot}
            source={imageSource}
            style={styles.layerImage}
          />
        );
      })}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    width: 200, // Define a standard base size
    height: 300,
    position: 'relative', // Necessary for absolute children
  },
  layerImage: {
    position: 'absolute', // Stack them on top of each other
    top: 0,
    left: 0,
    width: '100%',
    height: '100%',
    resizeMode: 'contain',
  },
});