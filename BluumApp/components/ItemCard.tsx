import { Colors } from '@/constants/theme';
import React, { useState } from 'react';
import {
  Image,
  Platform,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
  ViewStyle
} from 'react-native';

export type ShopItemProps = {
  id: number;
  name: string;
  price: number;
  icon: string | any;
  cat?: string;
};

type ItemCardProps = {
  item: ShopItemProps;
  onPress?: () => void;
  style?: ViewStyle; // Added style prop for external styling if needed
  actionButton?: React.ReactNode; // NEW: Optional button to render below
};

const BACKEND_URL = 'http://bluum.test';
const PLACEHOLDER_IMAGE = require("../app/assets/icons/clothingPlaceholder.png");

const getImageSource = (icon: string | any) => {
  if (!icon) return PLACEHOLDER_IMAGE;
  if (typeof icon === 'string') {
    const cleanPath = icon.startsWith('/') ? icon.substring(1) : icon;
    if (cleanPath.startsWith('http')) {
      return { uri: cleanPath };
    }
    return { uri: `${BACKEND_URL}/${cleanPath}` };
  }
  return icon;
};

const ItemCard = ({ item, onPress, style, actionButton }: ItemCardProps) => {
  const [imageError, setImageError] = useState(false);

  return (
    <TouchableOpacity 
      style={[styles.shopItem, style]} 
      onPress={onPress} 
      activeOpacity={0.7}
    >
      
      <View style={styles.imageContainer}>
        <Image 
          source={imageError ? PLACEHOLDER_IMAGE : getImageSource(item.icon)} 
          style={styles.itemIcon} 
          onError={() => setImageError(true)}
        />
      </View>

      {/* NEW: If actionButton exists, render it. Otherwise, render the price. */}
      {actionButton ? (
        <View style={styles.actionContainer}>
          {actionButton}
        </View>
      ) : (
        <View style={styles.priceContainer}>
          <Image
            source={require("../app/assets/icons/gem.png")}
            style={styles.gemIcon}
          />
          <Text style={styles.priceText}>{item.price}</Text>
        </View>
      )}

    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  shopItem: {
    width: "23%", 
    aspectRatio: 0.9, // Made slightly taller to fit buttons
    marginBottom: 15,
    padding: 8, // Reduced padding slightly
    backgroundColor: Colors.lightPurpleCardBackground, 
    borderRadius: 20,
    flexDirection: 'column',
    justifyContent: 'space-between',
    ...Platform.select({
      ios: {
        shadowColor: "#000",
        shadowOffset: { width: 0, height: 2 },
        shadowOpacity: 0.05,
        shadowRadius: 4,
      },
      android: {
        elevation: 3,
      },
    }),
  },
  imageContainer: {
    flex: 1, 
    width: '100%',
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 5,
  },
  itemIcon: {
    width: '80%',
    height: '80%',
    resizeMode: 'contain', 
  },
  priceContainer: {
    height: 30, 
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
  },
  actionContainer: {
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    width: '100%',
  },
  gemIcon: {
    width: 20,
    height: 20,
    resizeMode: "contain",
    marginRight: 4,
  },
  priceText: {
    color: Colors.textPrimary,
    fontWeight: "bold",
    fontSize: 14,
  },
});

export default ItemCard;