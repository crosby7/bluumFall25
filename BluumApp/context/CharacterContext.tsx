import React, { createContext, useContext, useState, useEffect } from 'react';
// Now this import works because we moved the type in Step 1
import { EquippedItems, SpeciesType } from '@/components/character/CharacterAssets';
import { apiClient } from '@/services/api';

type CharacterContextType = {
  species: SpeciesType;
  setSpecies: (species: SpeciesType) => void;
  equippedItems: EquippedItems;
  equipItem: (slot: keyof EquippedItems, itemUrl: string | null) => void;
  isOwned: (itemId: number) => boolean;
  addOwnedItem: (itemId: number) => void;
};

const CharacterContext = createContext<CharacterContextType | undefined>(undefined);

export const CharacterProvider = ({ children }: { children: React.ReactNode }) => {
  const [species, setSpecies] = useState<SpeciesType>('axolotl');
  
  const [equippedItems, setEquippedItems] = useState<EquippedItems>({
    Hat: null,
    Shirt: null,
    Footwear: null,
    Eyewear: null,
  });

  const [ownedItemIds, setOwnedItemIds] = useState<Set<number>>(new Set());

  const equipItem = (slot: keyof EquippedItems, itemUrl: string | null) => {
    // FIX: Typed 'prev' explicitly
    setEquippedItems((prev: EquippedItems) => ({
      ...prev,
      [slot]: itemUrl
    }));
  };

  const addOwnedItem = (itemId: number) => {
    // FIX: Typed 'prev' explicitly to Set<number>
    setOwnedItemIds((prev: Set<number>) => {
        const newSet = new Set(prev);
        newSet.add(itemId);
        return newSet;
    });
  };

  const isOwned = (itemId: number) => {
    return ownedItemIds.has(itemId);
  };

  return (
    <CharacterContext.Provider value={{ 
      species, 
      setSpecies, 
      equippedItems, 
      equipItem,
      isOwned,
      addOwnedItem
    }}>
      {children}
    </CharacterContext.Provider>
  );
};

export const useCharacter = () => {
  const context = useContext(CharacterContext);
  if (!context) throw new Error('useCharacter must be used within a CharacterProvider');
  return context;
};