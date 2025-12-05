import { MiniTaskCard } from "@/components/miniTaskCard";
import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
import React, { useState } from "react";
import {
  Image,
  ImageBackground,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";

const characterData = {
  dog: {
    pfp: require("../assets/icons/rowdyPFP.svg"),
    character: require("../assets/icons/rowdyCharacter.svg"),
  },
  axolotl: {
    pfp: require("../assets/icons/axolotlPFP.svg"),
    character: require("../assets/icons/axolotlCharacter.svg"),
  },
};

type characterKey = keyof typeof characterData;

const HomeScreen = () => {
  const styles = createStyles(effectiveWidth, effectiveHeight);
  const [selectedChar, setSelectedChar] = useState<characterKey>("axolotl");
  const currentCharData = characterData[selectedChar];
  const currentPfpImage = currentCharData.pfp;
  const currentCharacterImage = currentCharData.character;

  return (
    <SafeAreaView style={styles.bg}>
      <SafeAreaView style={styles.appContainer}>
        <ScrollView showsVerticalScrollIndicator={false}>
          <View style={styles.roomView}>
            <ImageBackground
              source={require("../assets/images/room.png")}
              style={styles.roomBackground}
            />
            <View style={styles.wall}>
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

            <View style={styles.characterPlaceholder} />
            <ImageBackground></ImageBackground>
            <Image
              source={currentCharacterImage}
              style={styles.characterPlaceholder}
            />
          </View>

          <View style={styles.infoCards}>
            <View style={[styles.infoCard, styles.statsCard]}>
              <View style={styles.profileHeader}>
                <Text style={styles.username}>[Rowdy#7890]</Text>
                <View style={styles.currencyContainer}>
                  <View style={styles.currencyCount}>
                    <Image 
                      source={require("../assets/icons/xpIcon.png")} 
                      style={styles.currencyIcon}
                    />
                    <Text style={styles.currencyText}>[800 XP]</Text>
                  </View>
                  <View style={[styles.currencyCount, styles.gemCount]}>
                    <Image
                      source={require("../assets/icons/currencyIcon.png")}
                      style={styles.currencyIcon}
                    />
                    <Text style={styles.currencyText}>[1000]</Text>
                  </View>
                </View>
              </View>

              <View style={styles.statsContent}>
                <View style={styles.avatar}>
                  <Image style={styles.avatarImg} source={currentPfpImage} />
                </View>

                <View style={styles.progressContainer}>
                  <View style={styles.progressBar}>
                    <Image 
                      source={require("../assets/icons/xpIcon.png")} 
                      style={styles.progressIcon}
                    />
                    <View style={[styles.progressFill, styles.xpFill]} />
                  </View>
                  <View style={styles.waterBarContainer}>
                    <View style={[styles.progressBar, styles.waterBar]}>
                      <Image 
                        source={require("../assets/icons/waterIcon.png")} 
                        style={styles.progressIcon}
                      />
                      <View style={[styles.progressFill, styles.waterFill]} />
                    </View>
                    <TouchableOpacity style={styles.plusIcon}>
                      <Image 
                        source={require("../assets/icons/addWaterIcon.png")} 
                        style={styles.plusIconImage}
                      />
                    </TouchableOpacity>
                  </View>
                </View>
              </View>
            </View>

            <View style={[styles.infoCard, styles.tasksCard]}>
              <Text style={styles.cardTitle}>Upcoming Tasks</Text>
              <MiniTaskCard
                icon={require("../assets/icons/breakfast.png")}
                iconBG= {Colors.taskNutritionBreakfast}
                title="Breakfast"
                time="8:00 am"
                duration="30 mins"
                energy="100"
                completed={false}
              />
              <MiniTaskCard
                icon={require("../assets/icons/medicine.png")}
                iconBG= {Colors.taskMedicine}
                title="Morning Medication"
                time="8:15 am"
                energy="50"
                completed={false}
              />
            </View>
          </View>

          <View style={{ height: 100 }} />
        </ScrollView>
      </SafeAreaView>
    </SafeAreaView>
  );
};

const createStyles = (effectiveWidth: number, effectiveHeight: number) =>
  StyleSheet.create({
    // --- Main Layout ---
    bg: {
      flex: 1,
      backgroundColor: "#3b1f6dff",
    },
    appContainer: {
      flex: 1,
      backgroundColor: Colors.floor,
      width: effectiveWidth * 1,
      alignSelf: "center",
    },

    // --- Room View ---
    roomView: {
      width: effectiveWidth * 1,
      alignSelf: "center",
      height: effectiveHeight * 0.5,
    },
    roomBackground: {
      width: effectiveWidth * 1,
      resizeMode: "stretch",
      flex: 1,
      marginBottom: -20,
    },
    wall: {},
    characterPlaceholder: {
      width: effectiveWidth * 0.5,
      height: effectiveHeight * 0.25,
      position: "absolute",
      bottom: effectiveHeight * 0.15,
      resizeMode: "contain",
      alignSelf: "center",
    },
    roomActions: {
      position: "absolute",
      bottom: effectiveHeight * 0.15,
      right: effectiveWidth * 0.05,
      flexDirection: "row",
      gap: effectiveWidth * 0.02,
    },
    iconButton: {
      width: effectiveWidth * 0.08,
      height: effectiveHeight * 0.07,
      borderRadius: (effectiveWidth * 0.1) / 2,
      backgroundColor: "rgba(88, 88, 88, 0.3)",
    },

    // --- Info Cards Container ---
    infoCards: {
      backgroundColor: Colors.floor,
      marginTop: -100,
      gap: 25,
    },
    infoCard: {
      backgroundColor: Colors.cardBg,
      borderRadius: 20,
      padding: 20,
      width: "90%",
      alignSelf: "center",
      gap: 15,
    },

    // --- Stats Card ---
    statsCard: {},
    profileHeader: {
      flexDirection: "row",
      justifyContent: "space-between",
      alignItems: "center",
    },
    username: {
      color: Colors.white,
      fontWeight: "bold",
      fontSize: effectiveWidth * 0.03,
    },
    currencyContainer: {
      flexDirection: "row",
      alignItems: "center",
      gap: effectiveWidth * 0.05,
    },
    currencyCount: {
      flexDirection: "row",
      alignItems: "center",
      gap: effectiveWidth * 0.01,
    },
    currencyIcon: {
      width: effectiveWidth * 0.03,
      height: effectiveWidth * 0.03,
      resizeMode: 'contain',
    },
    gemCount: {
      backgroundColor: "#27A36A",
      padding: 10,
      borderRadius: 10,
      marginLeft: -15,
      ...Platform.select({
        ios: {
          shadowColor: "#000",
          shadowOffset: { width: 0, height: 2 },
          shadowOpacity: 0.25,
          shadowRadius: 3.84,
        },
        android: {
          elevation: 5,
        },
      }),
    },
    currencyText: {
      color: Colors.white,
      fontSize: effectiveWidth * 0.02,
      fontWeight: 'bold',
    },
    statsContent: {
      flexDirection: "row",
      alignItems: "center",
      gap: effectiveWidth * 0.02,
    },
    avatar: {
      width: effectiveWidth * 0.1,
      height: effectiveWidth * 0.1,
      borderRadius: (effectiveWidth * 0.1) / 2,
    },
    avatarImg: {
      width: "100%",
      height: "100%",
      borderRadius: (effectiveWidth * 0.1) / 2,
    },
    progressContainer: {
      flex: 1,
      gap: 10,
    },
    progressBar: {
      width: "100%",
      height: effectiveHeight * 0.05,
      // backgroundColor: "rgba(0, 0, 0, 0.2)",
      backgroundColor: "#E1E1E1",
      borderRadius: 25,
      borderColor: Colors.white,
      borderWidth: 5,
      flexDirection: 'row',
      alignItems: 'center',
      paddingHorizontal: 5,
    },
    progressIcon: {
      width: effectiveWidth * 0.03,
      height: effectiveWidth * 0.03,
      resizeMode: 'contain',
      marginRight: 5,
    },
    progressFill: {
      position: 'absolute',
      left: 5,
      top: 0,
      bottom: 0,
      height: "100%",
      borderRadius: 10,
    },
    xpFill: { 
      width: "80%", 
      backgroundColor: Colors.progressXp, 
      left: effectiveWidth * 0.03 + 10, 
    },
    waterFill: { 
      width: "50%", 
      backgroundColor: Colors.progressWater, 
      left: effectiveWidth * 0.03 + 10, 
    },
    waterBarContainer: {
      flexDirection: "row",
      alignItems: "center",
      gap: 10,
    },
    waterBar: {
      flex: 1,
    },
    plusIcon: {
      width: effectiveHeight * 0.04,
      height: effectiveHeight * 0.04,
      justifyContent: "center",
      alignItems: "center",
    },
    plusIconImage: {
      width: "100%",
      height: "100%",
      resizeMode: 'contain',
    },
    plusIconText: {
      color: "#555",
      fontWeight: "bold",
    },

    // --- Tasks Card ---
    tasksCard: {},
    cardTitle: {
      color: Colors.white,
      fontSize: effectiveWidth * 0.045,
      fontWeight: "bold",
      marginBottom: effectiveHeight * 0.012,
    },
  });

export default HomeScreen;