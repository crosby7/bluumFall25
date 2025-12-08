export const CHARACTER_ASSETS = {
  axolotl: {
    pfp: require("../../app/assets/characters/axolotl/axolotlPfp.png"),
    // Bottom Layer
    tail: require("../../app/assets/characters/axolotl/axolotl-tail.png"),
    
    // Middle Layer
    torso: require("../../app/assets/characters/axolotl/axolotl-torso.png"),
    feet: require("../../app/assets/characters/axolotl/axolotl-feet.png"),
    
    // Top Layer
    head: require("../../app/assets/characters/axolotl/axolotl-head.png"),
    features: require("../../app/assets/characters/axolotl/axolotl-features.png"), // Eyes/Mouth
  },
  
  dog: {
    pfp: require("../../app/assets/characters/dog/dogPfp.png"),
    // Bottom Layer
    tail: require("../../app/assets/characters/dog/dog-tail.png"),
    
    // Middle Layer
    torso: require("../../app/assets/characters/dog/dog-torso.png"),
    feet: require("../../app/assets/characters/dog/dog-feet.png"),
    
    // Top Layer
    head: require("../../app/assets/characters/dog/dog-head.png"),
    features: require("../../app/assets/characters/dog/dog-features.png"),
  },

  cat: {
    pfp: require("../../app/assets/characters/cat/catPfp.png"),
    // Bottom Layer
    tail: require("../../app/assets/characters/cat/cat-tail.png"),
    
    // Middle Layer
    torso: require("../../app/assets/characters/cat/cat-torso.png"),
    feet: require("../../app/assets/characters/cat/cat-feet.png"),
    
    // Top Layer
    head: require("../../app/assets/characters/cat/cat-head.png"),
    features: require("../../app/assets/characters/cat/cat-features.png"),
  }
};

export type SpeciesType = keyof typeof CHARACTER_ASSETS;

export type EquippedItems = {
  Hat?: string | null;
  Shirt?: string | null;
  Footwear?: string | null;
  Eyewear?: string | null;
};