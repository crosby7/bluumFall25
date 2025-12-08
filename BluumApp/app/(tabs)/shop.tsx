import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
import React, { useEffect, useState } from "react";
import {
  Image,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
  ActivityIndicator,
  Alert
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";

import ItemCard, { ShopItemProps } from "@/components/ItemCard";
import { apiClient } from '../../services/api'; 
import { CharacterView } from "@/components/character/CharacterView"; 
import { EquippedItems } from "@/components/character/CharacterAssets"; 
import { useCharacter } from "@/context/CharacterContext"; 


// --- Constants for Button Colors ---
const INACTIVE_BUTTON_COLOR = "#9E89E3"; 
const ACTIVE_BUTTON_COLOR = "#5A429B";
const PURCHASE_BUTTON_COLOR = "#27A36A"; 

const CATEGORY_MAP: Record<string, string[]> = {
  "All": [],
  "Shirts": ["shirt", "top", "blouse", "t-shirt"], 
  "Accessories": ["eyewear", "glasses", "acc", "accessory", "tie", "bow", "scarf"],
  "Shoes": ["footwear", "sneakers", "shoes", "boots"],
  "Hats": ["hat", "beanie", "cap", "headwear"],
  "Jackets": ["jacket", "coat", "outerwear", "hoodie"]
};

const CLOTHING_CATEGORIES = [
  { id: "All", icon: require("../assets/icons/closetButton.png") },
  { id: "Shirts", icon: require("../assets/icons/catShirts.png") },
  { id: "Accessories", icon: require("../assets/icons/catGlasses.png") },
  { id: "Shoes", icon: require("../assets/icons/catShoes.png") },
  { id: "Hats", icon: require("../assets/icons/catHats.png") },
  { id: "Jackets", icon: require("../assets/icons/catJackets.png") },
];

type TabType = 'clothes' | 'background' | 'pose';

type PreviewItemsMap = {
  Hat?: string | null;
  Shirt?: string | null;
  Footwear?: string | null;
  Eyewear?: string | null;
};

const ShopScreen = () => {
  const styles = createStyles(effectiveWidth, effectiveHeight);

  const { 
    species, 
    equippedItems: globalEquipped, 
    equipItem: globalEquip, 
    isOwned, 
    addOwnedItem 
  } = useCharacter();

  const [shopItems, setShopItems] = useState<ShopItemProps[]>([]);
  const [userGems, setUserGems] = useState(0); 
  const [loading, setLoading] = useState(true);
  
  const [activeTab, setActiveTab] = useState<TabType>('clothes');
  const [selectedClothingCategory, setSelectedClothingCategory] = useState("All");

  // LOCAL PREVIEW STATE (Initialized with global outfit)
  const [previewItems, setPreviewItems] = useState<PreviewItemsMap>(globalEquipped);

  useEffect(() => {
    setPreviewItems(globalEquipped);
  }, [globalEquipped]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        setLoading(true);

        const [itemsResponse, userResponse] = await Promise.all([
          apiClient.getShopItems(),
          apiClient.getCurrentPatient()
        ]);

        const rawItems = Array.isArray(itemsResponse) ? itemsResponse : (itemsResponse.data || []);
        
        const mappedItems = rawItems.map((item: any) => ({
          id: item.id,
          name: item.name,
          price: item.price,
          icon: item.image,
          cat: item.category 
        }));
        
        setShopItems(mappedItems);

        if (userResponse && typeof userResponse.gems === 'number') {
          setUserGems(userResponse.gems);
        }

      } catch (error) {
        console.error("Error fetching shop data:", error);
      } finally {
        setLoading(false);
      }
    };

    fetchData();
  }, []);

  // --- PREVIEW LOGIC ---
  const handleItemPress = (item: ShopItemProps) => {
    if (!item.cat) return;
    const cat = item.cat.toLowerCase();
    const newItemUrl = typeof item.icon === 'string' ? item.icon : null;

    let slot: keyof EquippedItems | null = null;
    if (cat.includes('hat') || cat.includes('beanie') || cat.includes('cap')) slot = 'Hat';
    else if (cat.includes('shirt') || cat.includes('top')) slot = 'Shirt';
    else if (cat.includes('shoe') || cat.includes('foot') || cat.includes('sneaker')) slot = 'Footwear';
    else if (cat.includes('glass') || cat.includes('eye')) slot = 'Eyewear';

    if (slot) {
      setPreviewItems(prev => {
        // If clicking the item that is currently equipped in the preview, take it off
        if (prev[slot] === newItemUrl) {
          // If unowned, we clear the preview. If owned, the unequip logic must happen elsewhere.
          return { ...prev, [slot]: null };
        }
        // Otherwise, put the new item on
        return { ...prev, [slot]: newItemUrl };
      });
    }
  };

  // --- PURCHASE & EQUIP LOGIC ---
  const handlePurchase = (item: ShopItemProps) => {
    // 1. Add item to owned list
    addOwnedItem(item.id); 

    // 2. Determine the slot of the purchased item (e.g., 'Shirt')
    let purchasedSlot: keyof EquippedItems | null = null;
    const cat = item.cat?.toLowerCase() || '';

    if (cat.includes('hat') || cat.includes('beanie') || cat.includes('cap')) purchasedSlot = 'Hat';
    else if (cat.includes('shirt') || cat.includes('top')) purchasedSlot = 'Shirt';
    else if (cat.includes('shoe') || cat.includes('foot') || cat.includes('sneaker')) purchasedSlot = 'Footwear';
    else if (cat.includes('glass') || cat.includes('eye')) purchasedSlot = 'Eyewear';
    
    // 3. Call the equip functions to save all currently previewed items globally.
    //    We loop through the current preview state and push everything to the global context.
    
    // FIX: Ensure all items currently in the preview state are saved globally.
    (Object.keys(previewItems) as Array<keyof EquippedItems>).forEach(slot => {
        const itemUrl = previewItems[slot];
        
        // Only save the slot if it has a URL (i.e., something is equipped there)
        if (itemUrl) {
            globalEquip(slot, itemUrl); 
        }
    });


    Alert.alert("Success!", `You bought and equipped the ${item.name}.`);
  };
  const handleEquip = (item: ShopItemProps) => {
    const cat = item.cat?.toLowerCase() || '';
    let slot: keyof EquippedItems | null = null;
    
    if (cat.includes('hat') || cat.includes('beanie')) slot = 'Hat';
    else if (cat.includes('shirt') || cat.includes('top')) slot = 'Shirt';
    else if (cat.includes('shoe') || cat.includes('foot')) slot = 'Footwear';
    else if (cat.includes('glass')) slot = 'Eyewear';

    if (slot) {
      const itemUrl = typeof item.icon === 'string' ? item.icon : null;
      globalEquip(slot, itemUrl); 
    }
  };

  // --- FILTERING LOGIC ---
  const getFilteredItems = () => {
    if (activeTab === 'pose' || activeTab === 'background') return [];
    const clothingItems = shopItems.filter((item) => {
      const cat = item.cat ? item.cat.toLowerCase() : '';
      return cat !== 'background' && cat !== 'pose';
    });
    if (selectedClothingCategory === "All") return clothingItems;
    const validKeywords = CATEGORY_MAP[selectedClothingCategory] || [];
    return clothingItems.filter((item) => {
      const itemCat = item.cat ? item.cat.toLowerCase() : '';
      return validKeywords.some(keyword => itemCat.includes(keyword));
    });
  };
  const filteredItems = getFilteredItems();

  // --- BUTTON RENDERER ---
  const getItemButton = (item: ShopItemProps) => {
    const userHasItem = isOwned(item.id);
    const itemUrl = typeof item.icon === 'string' ? item.icon : null;
    const isEquippedGlobally = Object.values(globalEquipped).includes(itemUrl);
    // Is this item currently equipped in the local PREVIEW map?
    const isPreviewing = Object.values(previewItems).includes(itemUrl); 

    // 1. OWNED ITEM LOGIC: Show Label/Equip Button
    if (userHasItem) {
        // Owned and currently wearing it
        if (isEquippedGlobally) {
            return (
                <View style={[styles.ownedLabel, styles.equippedLabel]}>
                    <Text style={styles.ownedLabelText}>Equipped</Text>
                </View>
            );
        } else {
            // Owned but not wearing it -> Show EQUIP button
            return (
                <TouchableOpacity 
                    style={[styles.purchaseButton, { backgroundColor: INACTIVE_BUTTON_COLOR }]} 
                    onPress={() => handleEquip(item)}
                >
                    <Text style={styles.purchaseButtonText}>Equip</Text>
                </TouchableOpacity>
            );
        }
    } 
    
    // 2. UNOWNED ITEM LOGIC: Show Purchase Button OR Price
    else {
        if (isPreviewing) {
            // If the item is being PREVIEWED, show the "Buy Now" button
            return (
                <TouchableOpacity 
                    style={styles.purchaseButton} 
                    onPress={() => handlePurchase(item)}
                >
                    <Text style={styles.purchaseButtonText}>Buy Now</Text>
                </TouchableOpacity>
            );
        } else {
            // If NOT owned and NOT previewing, return null to show default price/gem count
            return null;
        }
    }
  };

  const getPanelTitle = () => {
    if (activeTab === 'background') return "Room • Backgrounds";
    if (activeTab === 'pose') return "Poses";
    return `Clothes • ${selectedClothingCategory}`;
  };

  const renderRoomActionButton = (tab: TabType, iconSource: any) => (
    <TouchableOpacity 
      onPress={() => setActiveTab(tab)}
      style={[
        styles.roomActionButton,
        {
          backgroundColor: activeTab === tab ? ACTIVE_BUTTON_COLOR : INACTIVE_BUTTON_COLOR
        }
      ]}
    >
      <Image source={iconSource} style={styles.roomActionIcon} />
    </TouchableOpacity>
  );

  return (
    <SafeAreaView style={styles.appContainer} edges={["top"]}>
      <View style={styles.roomView}>
        <View style={styles.wall} />
        <View style={styles.floor} />
        <View style={styles.spotlight}>
            <Image source={require("../assets/images/spotlight.png")} />
        </View>
        
        <CharacterView 
          species={species} 
          equippedItems={previewItems as EquippedItems} 
          style={styles.characterPlaceholder}
        />

        {/* Currency */}
        <View style={styles.currencyContainer}>
          <Image 
            source={require("../assets/icons/gem.png")} 
            style={styles.currencyIcon} 
          />
          <Text style={styles.currencyText}>{userGems}</Text>
        </View>

        {/* Tab Buttons */}
       <View style={styles.roomActions}>
           {renderRoomActionButton('clothes', require("../assets/icons/closetButton.png"))}
           {renderRoomActionButton('background', require("../assets/icons/roomButton.png"))}
           {renderRoomActionButton('pose', require("../assets/icons/poseButton.png"))}
       </View>
      </View>

      <View style={styles.shopPanel}>
        <Text style={styles.panelTitle}>{getPanelTitle()}</Text>

        <View style={styles.categoriesAndItems}>
          
          {/* Categories */}
          {activeTab === 'clothes' && (
            <View style={styles.categories}>
              {CLOTHING_CATEGORIES.map((cat) => (
                <TouchableOpacity
                  key={cat.id}
                  style={[
                    styles.categoryIcon,
                    { backgroundColor: selectedClothingCategory === cat.id ? ACTIVE_BUTTON_COLOR : INACTIVE_BUTTON_COLOR },
                  ]}
                  onPress={() => setSelectedClothingCategory(cat.id)}
                >
                  <Image source={cat.icon} style={styles.categoryIconImage} />
                </TouchableOpacity>
              ))}
            </View>
          )}

          {/* Grid */}
          {loading ? (
            <ActivityIndicator size="large" color={Colors.primaryGreen} style={{marginTop: 50}} />
          ) : (
            <>
              {activeTab === 'pose' || activeTab === 'background' ? (
                <View style={styles.comingSoonContainer}>
                  <Text style={styles.comingSoonText}>Coming Soon!</Text>
                  <Text style={styles.comingSoonSubtext}>
                    {activeTab === 'pose' ? "Check back later for new poses." : "New rooms are under construction."}
                  </Text>
                </View>
              ) : (
                <ScrollView style={styles.itemGridContainer} showsVerticalScrollIndicator={false}>
                  <View style={styles.itemGrid}>
                    {filteredItems.length === 0 ? (
                      <Text style={styles.emptyText}>No items found in {selectedClothingCategory}.</Text>
                    ) : (
                      filteredItems.map((item) => {
                        const actionBtn = getItemButton(item);
                        const isEquippedInPreview = Object.values(previewItems).includes(item.icon);
                        
                        return (
                          <ItemCard 
                            key={item.id} 
                            item={item} 
                            onPress={() => handleItemPress(item)}
                            // Pass the custom button (or null for default price)
                            actionButton={actionBtn}
                            // Highlight border if currently previewing
                            style={isEquippedInPreview ? { borderWidth: 2, borderColor: ACTIVE_BUTTON_COLOR } : undefined}
                          />
                        );
                      })
                    )}
                  </View>
                </ScrollView>
              )}
            </>
          )}
        </View>
      </View>
    </SafeAreaView>
  );
};

const createStyles = (effectiveWidth: number, effectiveHeight: number) =>
  StyleSheet.create({
    appContainer: {
      flex: 1,
      backgroundColor: Colors.wall,
      width: effectiveWidth * 1,
      alignSelf: "center",
    },
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
      width: effectiveWidth * 0.6, 
      height: effectiveHeight * 0.3,
      position: "absolute",
      bottom: effectiveHeight * 0.05,
      alignSelf: "center",
      zIndex: 2,
    },
    currencyContainer: {
      position: "absolute",
      bottom: effectiveHeight * 0.06,
      left: effectiveWidth * 0.05,
      zIndex: 2,
      flexDirection: 'row',
      alignItems: 'center',
      backgroundColor: INACTIVE_BUTTON_COLOR, 
      borderRadius: 20,
      paddingVertical: 8,
      paddingHorizontal: 12,
      borderWidth: 2,
      borderColor: Colors.white,
      shadowColor: "#000",
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.2,
      shadowRadius: 3,
      elevation: 4,
    },
    currencyIcon: {
      width: 20,
      height: 20,
      resizeMode: 'contain',
      marginRight: 6
    },
    currencyText: {
      color: Colors.white,
      fontWeight: 'bold',
      fontSize: 16
    },
    roomActions: {
      position: "absolute",
      bottom: effectiveHeight * 0.06,
      right: effectiveWidth * 0.05,
      flexDirection: "row",
      gap: effectiveWidth * 0.02,
      zIndex: 2,
    },
    roomActionButton: {
      width: effectiveWidth * 0.1, 
      height: effectiveWidth * 0.1,
      borderRadius: (effectiveWidth * 0.1) / 2,
      justifyContent: "center",
      alignItems: "center",
      shadowColor: "#000",
      shadowOffset: { width: 0, height: 2 },
      shadowOpacity: 0.2,
      shadowRadius: 3,
      elevation: 4,
    },
    roomActionIcon: {
      width: "60%",
      height: "60%",
      resizeMode: "contain",
      tintColor: "white", 
    },
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
      width: effectiveWidth * 0.08, 
      height: effectiveWidth * 0.08,
      borderRadius: (effectiveWidth * 0.08) / 2,
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
    emptyText: {
      width: '100%', 
      textAlign: 'center', 
      marginTop: 20, 
      color: '#999'
    },
    comingSoonContainer: {
      flex: 1,
      justifyContent: 'center',
      alignItems: 'center',
      opacity: 0.7
    },
    comingSoonText: {
      fontSize: 24,
      fontWeight: 'bold',
      color: Colors.lightPurpleBackground,
      marginBottom: 8
    },
    comingSoonSubtext: {
      fontSize: 16,
      color: '#666'
    },
    purchaseButton: {
      backgroundColor: PURCHASE_BUTTON_COLOR,
      paddingVertical: 6,
      paddingHorizontal: 12,
      borderRadius: 15,
      width: '100%',
      alignItems: 'center',
    },
    purchaseButtonText: {
      color: Colors.white,
      fontWeight: 'bold',
      fontSize: 12,
    },
    ownedLabel: {
      backgroundColor: INACTIVE_BUTTON_COLOR,
      paddingVertical: 6,
      paddingHorizontal: 12,
      borderRadius: 15,
      width: '100%',
      alignItems: 'center',
    },
    equippedLabel: {
      backgroundColor: ACTIVE_BUTTON_COLOR,
    },
    ownedLabelText: {
      color: Colors.white,
      fontWeight: 'bold',
      fontSize: 12,
    },
  });

export default ShopScreen;