import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
// import { useCharacter } from "@/context/CharacterContext";
import React, { useState } from "react";
import {
  Image,
  ImageBackground,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";

import ItemCard from "@/components/ItemCard";
import { ShopItem } from "@/dataTypes/shopData";

const MOCK_SHOP_ITEMS: ShopItem[] = [
  { id: 1, cat: "Hat", name: "Beanie", icon: require("../assets/icons/shop-beanie.png"), price: 600 },
  { id: 2, name: "Bow", cat: "Hat", icon: require("../assets/icons/shop-bow.png"), price: 600 },
  { id: 3, name: "Glasses", cat: "Eyewear", icon: require("../assets/icons/shop-glasses.png"), price: 600 },
  { id: 4, name: "Dress", cat: "Shirt", icon: require("../assets/icons/shop-dress.png"), price: 600 },
  { id: 5, name: "Sneakers", cat: "Footwear", icon: require("../assets/icons/shop-sneakers.png"), price: 600 },
  { id: 6, name: "Jacket", cat: "Shirt", icon: require("../assets/icons/shop-jacket.png"), price: 600 },
  { id: 7, name: "Bow Tie", cat: "acc", icon: require("../assets/icons/shop-bowtie.png"), price: 600 },
  { id: 8, name: "Tuxedo", cat: "Shirt", icon: require("../assets/icons/shop-tuxedo.png"), price: 600 },
];

const CATEGORIES = [
  { id: "All", icon: require("../assets/icons/closetButton.png") },
  { id: "Shirts", icon: require("../assets/icons/catShirts.png") },
  { id: "Accessories", icon: require("../assets/icons/catGlasses.png") },
  { id: "Shoes", icon: require("../assets/icons/catShoes.png") },
  { id: "Hats", icon: require("../assets/icons/catHats.png") },
  { id: "Jackets", icon: require("../assets/icons/catJackets.png") },
];



// Styles
const createStyles = (effectiveWidth: number, effectiveHeight: number) =>
  StyleSheet.create({
    appContainer: {
      flex: 1,
      backgroundColor: Colors.wall,
      width: effectiveWidth * 1,
      alignSelf: "center",
    },

    // --- Top Preview Area ---
    roomView: {
      width: "100%",
      height: effectiveHeight * 0.45,
      position: "absolute",
      top: 0,
    },
    wall: {
      flex: 1,
      backgroundColor: Colors.wall,
    },
    floor: {
      height: effectiveHeight * 0.1,
      backgroundColor: Colors.floor,
      borderTopColor: "#000",
      borderTopWidth: 2,
    },

    spotlight: {
      position: "absolute",
      zIndex: 2,
      alignSelf: "center",
    },

    characterPlaceholder: {
      width: effectiveWidth * 0.5,
      height: effectiveHeight * 0.25,
      position: "absolute",
      bottom: effectiveHeight * 0.05,
      resizeMode: "contain",
      alignSelf: "center",
      zIndex: 2,
    },
    roomActions: {
      position: "absolute",
      bottom: effectiveHeight * 0.06,
      right: effectiveWidth * 0.05,
      flexDirection: "row",
      gap: effectiveWidth * 0.02,
      zIndex: 2,
    },
    iconButton: {
      width: effectiveWidth * 0.08,
      height: effectiveHeight * 0.07,
      borderRadius: (effectiveWidth * 0.1) / 2,
    },

    // --- Bottom Shop Panel ---
    shopPanel: {
      position: "absolute",
      bottom: 0,
      width: "100%", 
      height: effectiveHeight * 0.6,
      backgroundColor: Colors.lightPurpleBackground , 
      borderTopLeftRadius: 30,
      borderTopRightRadius: 30,
      paddingHorizontal: effectiveWidth * 0.05,
      paddingTop: effectiveHeight * 0.03,

      ...Platform.select({
        ios: {
          shadowColor: "#000",
          shadowOffset: { width: 0, height: -5 },
          shadowOpacity: 0.1,
          shadowRadius: 10,
        },
        android: {
          elevation: 20,
        },
      }),
    },
    panelTitle: {
      fontSize: effectiveWidth * 0.04,
      fontWeight: "bold",
      color: Colors.white,
      marginBottom: effectiveHeight * 0.02,
    },
    categoriesAndItems: {
      backgroundColor: Colors.white,
      padding: 20,
      borderRadius: 20,
      width: "100%",
      flex: 1,
    },
    categories: {
      flexDirection: "row",
      width: "100%",
      gap: 25,
      justifyContent: "center",
      alignItems: "center",
      marginBottom: 20,
    },
    categoryIcon: {
      width: effectiveWidth * 0.07,
      height: effectiveWidth * 0.07,
      borderRadius: (effectiveWidth * 0.07) / 2,
      justifyContent: "center",
      alignItems: "center",
    },
    categoryIconImage: {
      width: "60%",
      height: "60%",
      resizeMode: "contain",
      tintColor: "white",
    },
    itemGridContainer: {
      flex: 1, 
    },

    itemGrid: {
      flexDirection: "row",
      flexWrap: "wrap",
      justifyContent: "space-between",
      paddingBottom: 20,
    },
  });

const ShopScreen = () => {
  const styles = createStyles(effectiveWidth, effectiveHeight);
  // const { currentCharacterImage } = useCharacter();

  const [shopItems, setShopItems] = useState<ShopItem[]>(MOCK_SHOP_ITEMS);
  const [selectedCategory, setSelectedCategory] = useState("All");

  const selectedCategoryObject = CATEGORIES.find(
  (cat) => cat.id === selectedCategory
);

  return (
    <SafeAreaView style={styles.appContainer} edges={["top"]}>
      <View style={styles.roomView}>
        <View style={styles.wall} />
        <View style={styles.floor} />
        <View style={styles.spotlight}>
            <Image 
            source={require("../assets/images/spotlight.png")}
            />
        </View>
        <Image
          source={require("../assets/icons/rowdyCharacter.svg")}
          style={styles.characterPlaceholder}
        />
       <View style={styles.roomActions}>
                       <TouchableOpacity>
                         <ImageBackground
                           source={require("../assets/icons/closetButton.svg")}
                           style={styles.iconButton}
                         />
                       </TouchableOpacity>
       
                       <TouchableOpacity>
                         <ImageBackground
                           source={require("../assets/icons/roomButton.svg")}
                           style={styles.iconButton}
                         />
                       </TouchableOpacity>
                       <TouchableOpacity>
                         <ImageBackground
                           source={require("../assets/icons/poseButton.svg")}
                           style={styles.iconButton}
                         />
                       </TouchableOpacity>
                     </View>
      </View>

      <View style={styles.shopPanel}>
        <Text style={styles.panelTitle}>Clothes • {selectedCategory}</Text>

        <View style={styles.categoriesAndItems}>
          <View style={styles.categories}>
            {CATEGORIES.map((cat) => (
              <TouchableOpacity
                key={cat.id}
                style={[
                  styles.categoryIcon,
                  {
                    backgroundColor:
                      selectedCategory === cat.id
                        ? "#A592D8"
                        : "#7B5FBF", 
                  },
                ]}
                onPress={() => setSelectedCategory(cat.id)}
              >
                <Image source={cat.icon} style={styles.categoryIconImage} />
              </TouchableOpacity>
            ))}
          </View>

          <ScrollView
            style={styles.itemGridContainer}
            showsVerticalScrollIndicator={false}
          >
            <View style={styles.itemGrid}>
              {shopItems.map((item) => (
                <ItemCard 
                  key={item.id} 
                  item={item} 
                  onPress={() => console.log("Pressed", item.name)}
                />
              ))}
            </View>
          </ScrollView>
        </View>
      </View>
    </SafeAreaView>
  );
};

export default ShopScreen;