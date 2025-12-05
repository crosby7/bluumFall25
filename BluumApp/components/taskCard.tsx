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
import { Colors } from "../constants/theme";
 
type TaskCardProps = {
   icon?: ImageSourcePropType;
   iconBG: string;
   title: string;
   time: string;
   duration?: string; // <-- The ? makes this prop optional
   energy: string;
   completed: boolean;
 };
 
 export const TaskCard = ({ icon, iconBG, title, time, duration, energy, completed }: TaskCardProps) => {
  const styles = createStyles(effectiveWidth, effectiveHeight);
  return (
       <View style={styles.taskCard}>
         <View style={[styles.icon, { backgroundColor: iconBG }]} > 
          <Image
          source={icon}
          />
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
           <TouchableOpacity style={styles.checkbox}>
              <Image
              source={require("../app/assets/icons/checkBox.png")}
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
    width: effectiveWidth * 0.1,
    height: effectiveWidth * 0.1,
    borderRadius: 10,
    marginRight: effectiveWidth * 0.04,
    justifyContent: 'center',
    alignItems: 'center',
  },
  taskIcon: {
  },
  taskDetails: {
    flex: 1,
    marginLeft: -10,
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
  placeholderCheckIcon: {
    width: effectiveWidth * 0.07,
    height: effectiveWidth * 0.07,
    borderRadius: (effectiveWidth * 0.07) / 2,
    borderWidth: 2,
    borderColor: Colors.placeholder,
  },
  checkbox: {
    width: effectiveWidth * 0.05,
    height: effectiveWidth * 0.05,
    borderRadius: (effectiveWidth * 0.07) / 2,
    marginRight: 25,
  },
});