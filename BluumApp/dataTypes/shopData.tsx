import { ImageSourcePropType } from 'react-native';
// import { InventoryItem } from './inventoryData'; // Uncomment if you have this file

export interface ShopItem {
  id: number;
  name: string;
  cat: 'Hat' | 'Eyewear' | 'Shirt' | 'Footwear' | 'acc' | 'shoes'; // Added categories from your mock data
  price: number;
  // Changed from 'string' to allow require() assets
  icon: ImageSourcePropType; 
}

export interface ShopScreenProps {
  shopItems: ShopItem[];
  // userInventory: InventoryItem[];
  userCurrency: number;
}