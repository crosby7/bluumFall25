import React, { useState } from "react";
import {
  Image,
  ImageBackground,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from "react-native";

import { Colors } from "@/constants/theme";
import { useAuth } from "@/context/AuthContext";

const LoginScreen = () => {
  const [code, setCode] = useState("");
  const { signIn, isLoading, error } = useAuth();
  const [localError, setLocalError] = useState<string | null>(null);

  const handleLogin = async () => {
    setLocalError(null);

    if (!/^\d{6}$/.test(code)) {
      setLocalError("Please enter a valid 6-digit activation code");
      return;
    }

    try {
      await signIn(code);
    } catch (err) {
      console.error("Login error:", err);
    }
  };

  const displayError = localError || error;

  return (
    <ImageBackground
      source={require("@/app/assets/images/loginBG.png")}
      resizeMode="cover" // 'cover' fills the screen, 'contain' may leave borders
      style={styles.container}
    >
      {/* All content now goes INSIDE the ImageBackground */}
      <View style={styles.headerContainer}>
        <Image
          source={require("../assets/images/bluumLogo.png")}
          style={styles.logo}
          resizeMode="contain"
        />
        <Text style={styles.title}>Bluum</Text>
      </View>
      <Text style={styles.subtitle}>Inpatient Companion for Kids</Text>

      <View style={styles.inputWrapper}>
        <View style={styles.iconContainer}>
          <Image
            source={require("../assets/icons/lock.png")}
            style={styles.iconImage}
            resizeMode="contain"
          />
        </View>

        <TextInput
          style={styles.input}
          placeholder="Activation Code"
          placeholderTextColor={Colors.placeholder}
          value={code}
          onChangeText={(text) => {
            setCode(text);
            setLocalError(null);
          }}
          autoCapitalize="none"
          keyboardType="number-pad"
          maxLength={6}
          editable={!isLoading}
        />
      </View>

      {displayError && (
        <Text style={styles.errorText}>{displayError}</Text>
      )}

      <TouchableOpacity
        style={[styles.customButton, isLoading && styles.buttonDisabled]}
        activeOpacity={0.8}
        onPress={handleLogin}
        disabled={isLoading}
      >
        <Text style={styles.buttonText}>
          {isLoading ? "Logging in..." : "Log In"}
        </Text>
      </TouchableOpacity>
    </ImageBackground>
  );
};
const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: "center", // Centers content vertically
    alignItems: "center", // Centers content horizontally
    padding: 30, // Adds spacing from the screen edges
  },
  headerContainer: {
    flexDirection: "row",
    alignItems: "center",
    justifyContent: "center",
    marginBottom: 10,
  },
  logo: {
    width: 50,
    height: 50,
    marginRight: 10,
  },
  title: {
    fontSize: 50,
    fontWeight: "bold",
    marginBottom: 0,
    color: Colors.textLight,
  },
  subtitle: {
    fontSize: 30,
    marginBottom: 30,
    textAlign: "center",
    fontWeight: "bold",
    color: Colors.textLight,
  },
  inputWrapper: {
    width: "50%",
    alignSelf: "center",
    marginBottom: 20,
    position: "relative",
    justifyContent: "center",
  },
  input: {
    height: 50,
    borderWidth: 1,
    borderRadius: 8,
    paddingHorizontal: 15,
    backgroundColor: Colors.white,
    borderColor: Colors.placeholder,
    color: Colors.textPrimary,
    paddingLeft: 45,
    width: "100%",
  },
  iconContainer: {
    position: "absolute",
    top: 0,
    bottom: 0,
    left: 15,
    zIndex: 1,
    justifyContent: "center",
  },
  iconImage: {
    width: 25,
    height: 25,
  },
  customButton: {
    padding: 15,
    borderRadius: 8,
    alignItems: "center",
    marginTop: 10,
    width: "50%",
    alignSelf: "center",
    backgroundColor: Colors.lightPurple,
  },
  buttonText: {
    color: Colors.white,
    fontSize: 16,
    fontWeight: "bold",
  },
  buttonDisabled: {
    opacity: 0.6,
  },
  errorText: {
    color: "#ff4444",
    fontSize: 14,
    marginBottom: 10,
    textAlign: "center",
    backgroundColor: "rgba(255, 255, 255, 0.9)",
    padding: 10,
    borderRadius: 8,
    width: "50%",
    alignSelf: "center",
  },
});

export default LoginScreen;