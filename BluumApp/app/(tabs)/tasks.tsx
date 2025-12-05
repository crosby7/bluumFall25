import React from 'react';
import {
  ScrollView,
  StyleSheet,
  Text,
  View
} from 'react-native';

import { effectiveHeight, effectiveWidth } from "@/constants/dimensions";
import { Colors } from "@/constants/theme";
import { TaskCard } from "../../components/taskCard";








export default function TasksScreen() {
  const styles = createStyles(effectiveWidth, effectiveHeight);
  return (
    <ScrollView style={styles.container}>
      <Text style={styles.headerTitle}>Upcoming Tasks</Text>

      {/* Nursing Section */}
      <View style={styles.sectionContainer}>
        <Text style={styles.sectionTitle}>Nursing</Text>
        <View style={styles.sectionContent}>
          <TaskCard
            iconBG={Colors.taskMedicine}
            icon={require("../assets/icons/medicine.png")}
            title="Morning Medication"
            time="6:00 am"
            energy="50"
            completed={true}
          />
          <TaskCard
            iconBG={Colors.taskNursing}
            icon={require("../assets/icons/medical.png")}
            title="Bandage Change"
            time="6:00 am"
            duration='20 mins'
            energy="25"
            completed={false}
          />
          <TaskCard
            iconBG={Colors.taskMedicine}
            icon={require("../assets/icons/medicine.png")}
            title="Night Medication"
            time="6:00 am"
            energy="50"
            completed={false}
          />
        </View>
      </View>

      {/* Nutrition Section */}
      <View style={styles.sectionContainer}>
        <Text style={styles.sectionTitle}>Nutrition</Text>
        <View style={styles.sectionContent}>
          <TaskCard
            iconBG={Colors.taskNutritionBreakfast} 
            icon={require("../assets/icons/breakfast.png")}
            title="Breakfast"
            time="8:00 am"
            duration="30 mins"
            energy="40"
            completed={true}
          />
          <TaskCard
            iconBG={Colors.taskNutritionLunch}
            icon={require("../assets/icons/lunch.png")}
            title="Lunch"
            time="12:00 pm"
            duration="1 hr"
            energy="50"
            completed={false}
          />
          <TaskCard
            iconBG={Colors.taskNutritionDinner}
            // icon={require("../assets/icons/dinner.png")}
            title="Dinner"
            time="6:00 pm"
            duration="45 mins"
            energy="40"
            completed={false}
          />
        </View>
      </View>

      {/* Leisure Section - Extrapolated */}
      <View style={styles.sectionContainer}>
        <Text style={styles.sectionTitle}>Leisure</Text>
        <View style={styles.sectionContent}>
          <TaskCard
            iconBG={Colors.taskWater} 
            title="Drink Water"
            time="All Day"
            energy="00"
            completed={false}
          />
          <TaskCard
            iconBG={'#F8BBD0'} 
            title="Take a Walk"
            time="3:00 pm"
            duration="30 mins"
            energy="00"
            completed={false}
          />
          <TaskCard
            iconBG={'#D1C4E9'} 
            title="Read a Book"
            time="Anytime"
            energy="00"
            completed={true}
          />
          <TaskCard
            iconBG={'#B2DFDB'} 
            title="Make a Friend"
            time="Today"
            energy="00"
            completed={false}
          />
           <TaskCard
            iconBG={'#C5CAE9'} 
            title="Meditate"
            time="10:00 pm"
            duration="15 mins"
            energy="00"
            completed={false}
          />
        </View>
      </View>

      {/* Add some padding at the bottom so the nav bar doesn't overlap content */}
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