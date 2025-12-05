import { ImageSourcePropType } from 'react-native';

// 1. Define the body parts (Slots)
// The order here matters! The first item is drawn first (back), 
// the last item is drawn last (front).
export const LAYER_ORDER = [
  'tail',    // Behind the body
  'feet',    // Shoes or paws
  'legs',    // Pants or legs
  'torso',   // Shirt or chest
  'head',    // Head (No facial features)
  'features',// Face (Called Features in Figma)
  'hat',     // Hat sits on top of head
  'glasses', // Glasses sit on top of face
  'hand',    // Held items (Eventually)
] as const;

export type CharacterSlot = typeof LAYER_ORDER[number];

// 2. Define your Base Characters (The "Default" look)
type CharacterDefinition = {
  [key in CharacterSlot]?: ImageSourcePropType;
};

export const BASE_CHARACTERS: Record<string, CharacterDefinition> = {
  dog: {
    tail: require('../assets/characters/dog/tail_default.png'),
    feet: require('../assets/characters/dog/feet_default.png'),
    torso: require('../assets/characters/dog/torso_default.png'),
    head: require('../assets/characters/dog/head_default.png'),
  },
  axolotl: {
    tail: require('../assets/characters/axolotl/axolotl-tail.png'),
    feet: require('../assets/characters/axolotl/axolotl-feet.png'),
    torso: require('../assets/characters/axolotl/axolotl-torso.png'),
    head: require('../assets/characters/axolotl/axolotl-head.png'),
  },
};

// 3. Define your Cosmetics (The items that override defaults)
// This maps an Item ID from the DB to the specific asset
export const COSMETIC_ASSETS: Record<string, Partial<CharacterDefinition>> = {
  
    // Example (When Database is linked)
    // ITEM ID: { SLOT_TO_AFFECT: ASSET }
  
  // -- Shirts --
  'item_tuxedo': { torso: require('../assets/cosmetics/tuxedo_torso.png') },
  'item_dress': { torso: require('../assets/cosmetics/dress_torso.png') },
  
  // -- Hats --
  'item_beanie': { hat: require('../assets/cosmetics/beanie.png') },
  'item_bow': { hat: require('../assets/cosmetics/bow.png') },
  
  // -- Glasses --
  'item_glasses': { glasses: require('../assets/cosmetics/cosmetic-glasses.png') },
  
  // -- Shoes --
  'item_sneakers': { feet: require('../assets/cosmetics/sneakers.png') },
};