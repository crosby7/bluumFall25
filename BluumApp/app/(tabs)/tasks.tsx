import React, { useState, useCallback } from 'react'; // 1. Remove useEffect, keep/add useCallback
import {
  ScrollView,
  StyleSheet,
  Text,
  View,
  ActivityIndicator,
  Alert,
  RefreshControl
} from 'react-native';
import { useFocusEffect } from 'expo-router'; // 2. Import this!

import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
import { TaskCard } from "../../components/taskCard";
import { apiClient } from '../../services/api';
import { useAuth } from '@/context/AuthContext';

export default function TasksScreen() {
  const styles = createStyles(effectiveWidth, effectiveHeight);
  const { refreshPatient } = useAuth();

  const [tasks, setTasks] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  const fetchTasks = async () => {
    try {
      const response = await apiClient.getCurrentTasks();
      const taskList = Array.isArray(response) ? response : (response.data || []);
      setTasks(taskList);
    } catch (error) {
      console.error("Error fetching tasks", error);
      setTasks([]); 
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  // 3. REPLACE useEffect WITH THIS:
  useFocusEffect(
    useCallback(() => {
      fetchTasks();
    }, [])
  );

  // 4. Added onRefresh handler
  const onRefresh = useCallback(() => {
    setRefreshing(true);
    fetchTasks();
  }, []);

  // FILTER: Only show tasks that are NOT fully confirmed by Admin
  const visibleTasks = tasks.filter(task => task.status !== 'completed');

  const handleToggleTask = async (taskId: number, isCurrentlyChecked: boolean) => {
    // LOGIC: If checked, go to 'pending' (Verify). If unchecked, go back to 'incomplete'.
    const newStatus = isCurrentlyChecked ? 'incomplete' : 'pending';

    // Optimistic Update
    const originalTasks = [...tasks];
    setTasks(prevTasks => prevTasks.map(t => 
      t.id === taskId ? { ...t, status: newStatus } : t
    ));

    try {
      await apiClient.updateTaskCompletion(taskId, {
        // 5. FIXED ERROR: Cast as 'any' because 'incomplete' isn't in the strict type definition yet
        status: newStatus as any
      });
      await refreshPatient();
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
    return { 
      bg: '#ffd093ff', 
      icon: undefined 
    };
  };

  return (
    <ScrollView 
      style={styles.container}
      // 6. Attached RefreshControl
      refreshControl={
        <RefreshControl 
          refreshing={refreshing} 
          onRefresh={onRefresh} 
          tintColor={Colors.primaryGreen} // Matches your loading spinner color
        />
      }
    >
      <Text style={styles.headerTitle}>Upcoming Tasks</Text>

      {loading && !refreshing ? (
        <ActivityIndicator size="large" color={Colors.primaryGreen} style={{marginTop: 50}} />
      ) : (
        <View style={styles.sectionContainer}>
          <Text style={styles.sectionTitle}>Today's Schedule</Text>
          <View style={styles.sectionContent}>
            
            {visibleTasks.length === 0 ? (
               <Text style={{textAlign: 'center', padding: 20, color: Colors.textSecondary}}>
                 No tasks to do! Great job!
               </Text>
            ) : (
              visibleTasks.map((item) => {
                const style = getTaskStyle(item.task);
                return (
                  <TaskCard
                    key={item.id}
                    id={item.id}
                    iconBG={style.bg}
                    icon={style.icon}
                    title={item.task.name}
                    time={formatTime(item.scheduled_for)}
                    energy={item.task.xp_value.toString()}
                    completed={item.status === 'pending'}
                    onToggle={handleToggleTask}
                  />
                );
              })
            )}

          </View>
        </View>
      )}

      <View style={styles.bottomSpacer} /> 
    </ScrollView>
  );
}

const createStyles = (effectiveWidth: number, effectiveHeight: number) =>
  StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: Colors.floor,
    paddingTop: effectiveHeight * 0.05, 
  },
  headerTitle: {
    fontSize: effectiveWidth * 0.07,
    fontWeight: 'bold',
    textAlign: 'center',
    marginBottom: effectiveHeight * 0.04,
    color: Colors.white,
  },
  sectionContainer: {
    marginBottom: effectiveHeight * 0.03,
    backgroundColor: Colors.cardBg,
    width: "90%",
    alignSelf: "center",
    borderRadius: 20
  },
  sectionTitle: {
    fontSize: effectiveWidth * 0.05,
    fontWeight: 'bold',
    padding: 5,
    marginTop: 10,
    color: Colors.white,
    alignSelf: "center"
  },
  sectionContent: {
    marginHorizontal: effectiveWidth * 0.03,
    borderRadius: 20,
    paddingVertical: effectiveHeight * 0.02,
    paddingHorizontal: effectiveWidth * 0.03,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3,
    elevation: 5,
  },
  bottomSpacer: {
    height: effectiveHeight * 0.014
  }
});