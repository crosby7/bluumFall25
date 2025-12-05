export interface InventoryItem {
  id: number;
  name: string;
  category: 'Hat' | 'Eyewear' | 'Shirt' | 'Footwear';
  isEquipped: boolean;
    imageUrl: string;
}

export interface InventoryScreenProps {
    inventoryItems: InventoryItem[];
    userAvatarLayers: string[];
    onEquipItem: (itemId: number) => void;
}