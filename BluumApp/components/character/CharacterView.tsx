import React from "react";
import { View, Image, StyleSheet, ViewStyle, ImageStyle } from "react-native";
import { CHARACTER_ASSETS, SpeciesType, EquippedItems } from "./CharacterAssets";

type CharacterViewProps = {
  species?: SpeciesType;
  equippedItems?: EquippedItems;
  style?: ViewStyle;
};

// --- 1. NEW: Slot Positioning Config ---
// Since the clothing images are cropped (not full canvas), we need to 
// manually position them on the body. Tweak these % numbers to fit your assets!
const SLOT_STYLES: Record<string, ImageStyle> = {
  Hat: {
    width: '30%',    // Reduce width so it doesn't cover whole body
    height: '25%',   // Limit height
    top: '15%',       // Position at top
    left: '31%',     // Center horizontally (50% - half width)
    zIndex: 10,
  },
  Eyewear: {
    width: '50%',
    height: '15%',
    top: '37%',      // Roughly eye level for Axolotl
    left: '24%',
    zIndex: 9,
  },
  Shirt: {
    width: '60%',
    height: '52.5%',
    top: '38%',      // Lower down for torso
    left: '20%',
    zIndex: 3,
  },
  Footwear: {
    width: '28%',
    height: '19%',
    top: undefined,  // Unset top
    bottom: '0.7%',    // Position at bottom
    left: '32%',
    zIndex: 5,
  }
};

// Helper to render a layer
const CharacterLayer = ({ 
  source, 
  zIndex, 
  isNetwork = false,
  customStyle = {} // Accept custom positioning
}: { 
  source: any, 
  zIndex: number, 
  isNetwork?: boolean,
  customStyle?: ImageStyle 
}) => {
  if (!source) return null;

  const imageSource = isNetwork ? { uri: source } : source;

  return (
    <Image
      source={imageSource}
      // Merge base layer style with specific slot positioning
      style={[styles.layer, { zIndex }, customStyle]}
      resizeMode="contain" 
    />
  );
};

// const BACKEND_URL = 'http://bluum.test'; //Backend URL for web
const BACKEND_URL = `http://10.25.202.84:8000`;
const getClothingUrl = (path?: string | null) => {
  if (!path) return null;
  if (path.startsWith('http')) return path;
  const cleanPath = path.startsWith('/') ? path.substring(1) : path;
  return `${BACKEND_URL}/${cleanPath}`;
};

export const CharacterView = ({ species = "axolotl", equippedItems = {}, style }: CharacterViewProps) => {
  const baseAssets = CHARACTER_ASSETS[species];
  if (!baseAssets) return null;

  return (
    <View style={[styles.container, style, { aspectRatio: 1 }]}>
      
      {/* BASE BODY PARTS (These are full canvas 1575x1575, so no custom styles needed) */}
      <CharacterLayer source={baseAssets.tail} zIndex={1} />
      <CharacterLayer source={baseAssets.torso} zIndex={2} />
      <CharacterLayer source={baseAssets.feet} zIndex={4} />
      <CharacterLayer source={baseAssets.head} zIndex={6} />
      <CharacterLayer source={baseAssets.features} zIndex={8} />

      {/* CLOTHING LAYERS (Pass the SLOT_STYLES to fix sizing/position) */}
      
      {/* Shirt */}
      <CharacterLayer 
        source={getClothingUrl(equippedItems.Shirt)} 
        zIndex={3} 
        isNetwork 
        customStyle={SLOT_STYLES.Shirt} 
      />

      {/* Shoes */}
      <CharacterLayer 
        source={getClothingUrl(equippedItems.Footwear)} 
        zIndex={5} 
        isNetwork 
        customStyle={SLOT_STYLES.Footwear}
      />

      {/* Hat */}
      <CharacterLayer 
        source={getClothingUrl(equippedItems.Hat)} 
        zIndex={7} 
        isNetwork 
        customStyle={SLOT_STYLES.Hat}
      />

      {/* Eyewear */}
      <CharacterLayer 
        source={getClothingUrl(equippedItems.Eyewear)} 
        zIndex={9} 
        isNetwork 
        customStyle={SLOT_STYLES.Eyewear}
      />

    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    justifyContent: 'center',
    alignItems: 'center',
    position: 'relative',
    aspectRatio: 1, 
  },
  layer: {
    position: 'absolute',
    width: '100%',
    height: '100%',
  },
});