import { MiniTaskCard } from "@/components/miniTaskCard";
import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
import { useAuth } from "@/context/AuthContext";
import React, { useState, useCallback } from "react";
import {
  Image,
  ImageSourcePropType, // Ensure this is imported
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
  Alert,
  ImageBackground,
  RefreshControl,
  Dimensions, // Ensure Dimensions is imported or used correctly
  ViewStyle, // Import ViewStyle
} from "react-native";
import { SafeAreaView } from "react-native-safe-area-context";
import { apiClient } from '../../services/api'; 
import { useFocusEffect } from 'expo-router'; 
import { useCharacter } from "@/context/CharacterContext";
import { CharacterView } from "@/components/character/CharacterView";
import { CHARACTER_ASSETS } from "@/components/character/CharacterAssets";

// --- START: New Button Component for consistency ---
const HomeActionButton = ({ icon, onPress }: { icon: ImageSourcePropType, onPress: () => void }) => {
    // Get dimensions inside the functional component if needed dynamically
    const { width, height } = Dimensions.get('window'); 
    
    // FIX: Explicitly type the object as ViewStyle to satisfy TypeScript
    const iconButtonStyle: ViewStyle = {
      width: width * 0.09,
      height: width * 0.09,
      borderRadius: (width * 0.12) / 2,
      backgroundColor:  '#844AFF',
      justifyContent: 'center',
      alignItems: 'center',
    };
    
    return (
        <TouchableOpacity onPress={onPress} style={iconButtonStyle}>
            <Image 
                source={icon} 
                style={{ width: '60%', height: '60%', resizeMode: 'contain', tintColor: 'white'}} 
            />
        </TouchableOpacity>
    );
};
// --- END: New Button Component ---


const HomeScreen = () => {
  const { patient } = useAuth();

  const { species, equippedItems } = useCharacter();

  const styles = createStyles(effectiveWidth, effectiveHeight);

  const [tasks, setTasks] = useState<any[]>([]);
  const [userGems, setUserGems] = useState(0);
  const [userXP, setUserXP] = useState(0);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useFocusEffect(
    useCallback(() => {
      fetchTasks();
    }, [])
  );

  const fetchTasks = async () => {
    try {
      const [tasksResponse, userResponse] = await Promise.all([
        apiClient.getCurrentTasks(),
        apiClient.getCurrentPatient()
      ]);

      const taskList = Array.isArray(tasksResponse) ? tasksResponse : (tasksResponse.data || []);
      setTasks(taskList);

      if (userResponse && typeof userResponse.gems === 'number') {
        setUserGems(userResponse.gems);
      }
      if (userResponse && typeof userResponse.experience === 'number') {
        setUserXP(userResponse.experience);
      }
    } catch (error) {
      console.error("Error fetching tasks", error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchTasks();
  }, []);

  const handleToggleTask = async (taskId: number, isCurrentlyChecked: boolean) => {
    const newStatus = isCurrentlyChecked ? 'incomplete' : 'pending';
    
    const originalTasks = [...tasks];
    setTasks(prevTasks => prevTasks.map(t => 
      t.id === taskId ? { ...t, status: newStatus } : t
    ));

    try {
      await apiClient.updateTaskCompletion(taskId, {
        status: newStatus as any
      });
    } catch (error) {
      setTasks(originalTasks);
      Alert.alert("Error", "Could not update task status");
    }
  };

  const formatTime = (isoString: string) => {
    if(!isoString) return "Anytime";
    const date = new Date(isoString);
    return date.toLocaleTimeString([], { 
      hour: 'numeric', 
      minute: '2-digit', 
      timeZone: 'UTC' 
    });
  };

  const getTaskStyle = (task: any) => {
    const name = task.name.toLowerCase();
    const category = task.category ? task.category.toLowerCase() : '';

    if (name.includes('school') && name.includes('math')) {
        return { bg: '#d4f6d7ff', icon: require("../assets/icons/schoolMath.png") };
    }
    if (category.includes('therapy') || name.includes('bandage') || name.includes('pt ') || name.includes('exercise') || name.includes('walk')) {
      return { bg: '#ffcbe9ff', icon: require("../assets/icons/medical.png") };
    }
    if (category.includes('education') || name.includes('school') || name.includes('homework') || name.includes('read')) {
      return { bg: '#fdcdcdff', icon: require("../assets/icons/school.png") };
    }
    if (name.includes('breakfast')) return { bg: Colors.taskNutritionBreakfast, icon: require("../assets/icons/breakfast.png") };
    if (name.includes('lunch') || category.includes('meals')) return { bg: Colors.taskNutritionLunch, icon: require("../assets/icons/lunch.png") };
    if (name.includes('dinner')) return { bg: Colors.taskNutritionDinner, icon: require("../assets/icons/dinner.png") };
    if (category.includes('medical') || name.includes('medication') || name.includes('pill')) {
      return { bg: Colors.taskMedicine, icon: require("../assets/icons/medicine.png") };
    }
    if (name.includes('water') || name.includes('drink')) {
      return { bg: Colors.taskWater, icon: require("../assets/icons/medicine.png") };
    }
    return { bg: '#ffd093ff', icon: undefined };
  };

  const displayTasks = tasks
    .filter(t => t.status !== 'completed')
    .slice(0, 2);

  const currentPfpImage = CHARACTER_ASSETS[species].pfp;

  return (
    <SafeAreaView style={styles.bg}>
      <SafeAreaView style={styles.appContainer}>
        <ScrollView 
          showsVerticalScrollIndicator={false}
          refreshControl={
            <RefreshControl 
              refreshing={refreshing} 
              onRefresh={onRefresh} 
              tintColor={Colors.white} 
            />
          }
        >
          <View style={styles.roomView}>
            <ImageBackground
              source={require("../assets/images/room.png")}
              style={styles.roomBackground}
            />
            <View style={styles.wall}>
              
              <View style={styles.roomActions}>
                {/* UPATED BUTTONS: Now using the robust HomeActionButton */}
                <HomeActionButton icon={require("../assets/icons/closetButton.png")} onPress={() => console.log('Go to Shop/Closet')} />
                <HomeActionButton icon={require("../assets/icons/roomButton.png")} onPress={() => console.log('Go to Room/Background Shop')} />
                <HomeActionButton icon={require("../assets/icons/poseButton.png")} onPress={() => console.log('Go to Pose Shop')} />
              </View>

            </View>

            <CharacterView 
              species={species} 
              equippedItems={equippedItems} 
              style={styles.characterPlaceholder}
            />
          </View>

          <View style={styles.infoCards}>
            <View style={[styles.infoCard, styles.statsCard]}>
              <View style={styles.profileHeader}>
                <Text style={styles.username}>{patient?.username || 'Guest'}</Text>
                <View style={styles.currencyContainer}>
                  <View style={styles.currencyCount}>
                    <Image
                      source={require("../assets/icons/xpIcon.png")}
                      style={styles.currencyIcon}
                    />
                    <Text style={styles.currencyText}>{userXP} XP</Text>
                  </View>
                  <View style={[styles.currencyCount, styles.gemCount]}>
                    <Image
                      source={require("../assets/icons/currencyIcon.png")}
                      style={styles.currencyIcon}
                    />
                    <Text style={styles.currencyText}>{userGems}</Text>
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
              
              {displayTasks.length > 0 ? (
                displayTasks.map(item => {
                  const style = getTaskStyle(item.task);
                  return (
                    <MiniTaskCard
                      key={item.id}
                      id={item.id}
                      icon={style.icon}
                      iconBG={style.bg}
                      title={item.task.name}
                      time={formatTime(item.scheduled_for)}
                      energy={item.task.xp_value.toString()}
                      completed={item.status === 'pending'}
                      onToggle={handleToggleTask}
                    />
                  );
                })
              ) : (
                <Text style={{color: Colors.textSecondary, textAlign: 'center', padding: 10}}>
                  All caught up!
                </Text>
              )}
              
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
      // This style is deprecated but kept for reference
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