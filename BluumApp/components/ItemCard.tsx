import { Colors } from '@/constants/theme';
import React from 'react';
import {
  Image,
  Platform,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from 'react-native';
import { ShopItem } from '../dataTypes/shopData';

type ItemCardProps = {
  item: ShopItem;
  onPress?: () => void;
};

const ItemCard = ({ item, onPress }: ItemCardProps) => {
  return (
    <TouchableOpacity style={styles.shopItem} onPress={onPress} activeOpacity={0.7}>
      
      <View style={styles.imageContainer}>
        <Image source={item.icon} style={styles.itemIcon} />
      </View>

      <View style={styles.priceContainer}>
        <Image
          source={require("../app/assets/icons/gem.png")}
          style={styles.gemIcon}
        />
        <Text style={styles.priceText}>{item.price}</Text>
      </View>

    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  shopItem: {

    // Layout
    width: "23%",
    aspectRatio: 1, 
    marginBottom: 15,
    padding: 10,
    
    // Styling
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
    height: 25, 
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
  },
  
  gemIcon: {
    width: 25,
    height: 25,
    resizeMode: "contain",
  },
  
  priceText: {
    color: Colors.textPrimary,
    fontWeight: "bold",
    fontSize: 20,
  },
});

export default ItemCard;