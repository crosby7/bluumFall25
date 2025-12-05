import React from 'react';
import {
    Dimensions,
    Image,
    ImageSourcePropType,
    StyleSheet,
    Text,
    TouchableOpacity,
    View
} from 'react-native';

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
 
 export const MiniTaskCard = ({ icon, iconBG, title, time, duration, energy, completed }: TaskCardProps) => {
   return (
     <View style={styles.taskCard}>
       <View style={[styles.placeholderImage, { backgroundColor: iconBG }]} > 
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

 const { width, height } = Dimensions.get('window');
 const styles = StyleSheet.create({
  taskCard: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: Colors.white, 
    borderRadius: 15,
    padding: width * 0.01,
    marginBottom: height * 0.015,
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.05,
    shadowRadius: 2,
    elevation: 2,
  },
  placeholderImage: {
    width: width * 0.08,
    height: width * 0.08,
    borderRadius: 10,
    marginRight: width * 0.04,
    justifyContent: 'center',
    alignItems: 'center',
  },
  taskDetails: {
    flex: 1,
  },
  taskTitle: {
    fontSize: width * 0.025,
    fontWeight: '600',
    color: Colors.textPrimary, 
  },
  taskTime: {
    fontSize: width * 0.02,
    color: Colors.textSecondary,
    marginTop: height * 0.003,
  },
  taskEnergy: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: width * 0.03,
  },
  energyIcon: {
    width: width * .03,
    height: height * .03,
    marginRight: 4,
  },
  taskEnergyText: {
    fontSize: width * 0.03,
    fontWeight: 'bold',
    color: Colors.textSecondary, 
  },
  checkbox: {
    width: width * 0.05,
    height: width * 0.05,
    borderRadius: (width * 0.07) / 2,
    marginRight: 25,
  },
});