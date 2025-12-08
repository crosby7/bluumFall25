import { BottomTabBarProps } from "@react-navigation/bottom-tabs";
import React, { useState } from "react";
import {
  Dimensions,
  Image,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from "react-native";


export function Navbar({ state, navigation }: BottomTabBarProps) {
  const [isOpen, setIsOpen] = useState(true);

  const activeRouteName = state.routes[state.index].name;

  return (
    <View style={[styles.bottomNav, !isOpen && styles.bottomNavClosed]}>
      <TouchableOpacity style={[styles.navIcon, styles.navToggle]}
      onPress={() => setIsOpen(!isOpen)}>
        <Image
          style={[styles.navIcon, styles.navToggleIcon]}
          source={require("../app/assets/icons/navigation.png")}
        />
      </TouchableOpacity>
      {isOpen && (
        <>
          <TouchableOpacity 
            style={styles.navIcon}
            onPress={() => navigation.navigate('index')}
          >
            <Image
              style={[
                styles.navButtonIcon,
                activeRouteName === 'home' && { tintColor: '#DEC7FF' }
              ]}
              source={require("../app/assets/icons/homeIcon.png")}
            />
            <Text style={[
              styles.navIconText,
              activeRouteName === 'home' && { color: '#DEC7FF' }
            ]}>
              Home
            </Text>
          </TouchableOpacity>

          <TouchableOpacity 
            style={styles.navIcon}
            onPress={() => navigation.navigate('shop')}
          >
            <Image
              style={[
                styles.navButtonIcon,
                activeRouteName === 'shop' && { tintColor: '#DEC7FF' } 
              ]}
              source={require("../app/assets/icons/shopIcon.png")}
            />
            <Text style={[
              styles.navIconText,
              activeRouteName === 'shop' && { color: '#DEC7FF' }
            ]}>
              Shop
            </Text>
          </TouchableOpacity>

          <TouchableOpacity 
            style={styles.navIcon}
            onPress={() => navigation.navigate('tasks')}
          >
            <Image
              style={[
                styles.navButtonIcon,
                activeRouteName === 'tasks' && { tintColor: '#DEC7FF' }
              ]}
              source={require("../app/assets/icons/tasksIcon.png")}
            />
            <Text style={[
              styles.navIconText,
              activeRouteName === 'tasks' && { color: '#DEC7FF' }
            ]}>
              Tasks
            </Text>
          </TouchableOpacity>
          
          <TouchableOpacity 
            style={[styles.navClose, styles.navIcon]}
            onPress={() => setIsOpen(false)} 
          >
           <Image
            style={[styles.navButtonIcon, styles.navClose]}
            source={require("../app/assets/icons/closeX.png")}
           /> 
          </TouchableOpacity>
        </>
      )}
    </View>
  );
}

const { width, height } = Dimensions.get("window");

const styles = StyleSheet.create({
  bottomNav: {
    position: "absolute",
    bottom: height * 0.04,
    alignSelf: "center",
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "space-evenly",
    backgroundColor: "#5A3E9B",
    borderRadius: 100,
    paddingVertical: height * 0.01,
    width: width * 0.7,
    // Shadow
    shadowColor: "#000",
    shadowOffset: { width: 0, height: 4 },
    shadowOpacity: 0.3,
    shadowRadius: 4,
    elevation: 8,
  },

  bottomNavClosed: {
    width: width * 0.12,        
    height: width * 0.12,      
    alignSelf: "flex-start",    
    left: height * 0.04,        
    justifyContent: "center",   
    paddingVertical: 0,         
  },


  navIcon: {
    alignItems: "center",
    justifyContent: "center",
    flex: 1,
  },
  navToggleIcon: {
    resizeMode: "contain",
  },
  navToggle: {
    width: width * 0.12,
    height: width * 0.12,
    borderRadius: (width * 0.12) / 2,
  },
  navButtonIcon: {
    width: width * 0.08,
    height: width * 0.08,
    resizeMode: "contain",
    tintColor: "#FFFFFF",
  },
  navIconText: {
    color: "#FFFFFF",
    fontSize: width * 0.025,
    marginTop: height * 0.005,
  },
  navClose: {
    width: width * 0.04
  },
});
