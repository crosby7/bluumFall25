import React from 'react';
import {
  Image,
  ImageSourcePropType,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from 'react-native';

import { effectiveHeight, effectiveWidth } from '@/constants/dimensions';
import { Colors } from "@/constants/theme";

// 1. Defined the props interface
type TaskCardProps = {
   id: number; // Needed to identify which task to check off
   icon?: ImageSourcePropType;
   iconBG: string;
   title: string;
   time: string;
   duration?: string;
   energy: string;
   completed: boolean;
   // The function passed down from the parent screen
   onToggle: (id: number, currentStatus: boolean) => void; 
 };
 
 export const TaskCard = ({ 
    id, 
    icon, 
    iconBG, 
    title, 
    time, 
    duration, 
    energy, 
    completed, 
    onToggle 
  }: TaskCardProps) => {

  const styles = createStyles(effectiveWidth, effectiveHeight);

  return (
       <View style={styles.taskCard}>
         <View style={[styles.icon, { backgroundColor: iconBG }]} > 
          {icon && <Image source={icon} style={styles.taskIconImage} />}
         </View>
         
         <View style={styles.taskDetails}>
           <Text style={styles.taskTitle}>{title}</Text>
           <Text style={styles.taskTime}>
             {time} {duration && `· ${duration}`}
           </Text>
         </View>
         
         <View style={styles.taskEnergy}>
           <Image style={styles.energyIcon} source={require("../app/assets/icons/xpIcon.png")}/>
           <Text style={styles.taskEnergyText}>{energy}</Text>
         </View>
         
           {/* 2. Connected the onPress to the onToggle function */}
             <TouchableOpacity 
              style={styles.checkbox} 
              onPress={() => onToggle(id, completed)}
             >
              <Image
              source={require("../app/assets/icons/checkBox.png")}
              style={{ tintColor: completed ? Colors.primaryGreen : Colors.textSecondary }}
              />
             </TouchableOpacity>
       </View>
     );
   };

const createStyles = (effectiveWidth: number, effectiveHeight: number) =>
  StyleSheet.create({
  taskCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.white, 
    borderRadius: 15,
    padding: effectiveWidth * 0.025,
    marginBottom: effectiveHeight * 0.015,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
  },
  icon: {
    // INCREASED SIZE: Changed from 0.1 to 0.13
    width: effectiveWidth * 0.1, 
    height: effectiveWidth * 0.1,
    borderRadius: 12, // Slightly larger radius to match new size
    marginRight: effectiveWidth * 0.04,
    justifyContent: 'center',
    alignItems: 'center',
  },
  // Add this specific style for the inner Image if needed
  taskIconImage: {
    width: '60%', // Takes up 60% of the colored box
    height: '60%',
    resizeMode: 'contain'
  },
  taskDetails: {
    flex: 1,
    marginLeft: -5, // Adjusted spacing slightly
  },
  taskTitle: {
    fontSize: effectiveWidth * 0.03,
    fontWeight: '700',
    color: Colors.textPrimary, 
  },
  taskTime: {
    fontSize: effectiveWidth * 0.025,
    color: Colors.textSecondary,
    marginTop: effectiveHeight * 0.003,
  },
  taskEnergy: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: effectiveWidth * 0.03,
  },
  energyIcon: {
    width: effectiveWidth * .03,
    height: effectiveHeight * .03,
    marginRight: 4,
  },
  taskEnergyText: {
    fontSize: effectiveWidth * 0.04,
    color: Colors.textSecondary, 
  },
  checkbox: {
    width: effectiveWidth * 0.05,
    height: effectiveWidth * 0.05,
    borderRadius: (effectiveWidth * 0.07) / 2,
    marginRight: 25,
    marginTop: -20,
  },
});