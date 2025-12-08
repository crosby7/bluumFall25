import React, { createContext, ReactNode, useState, useEffect } from "react";
import { apiClient } from "@/services/api";
import { tokenStorage } from "@/services/tokenStorage";
import type { Patient, ApiError } from "@/types/api";

interface AuthContextType {
    isLoggedIn: boolean;
    isLoading: boolean;
    patient: Patient | null;
    error: string | null;
    signIn: (pairingCode: string) => Promise<void>;
    signOut: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({children}: {children: ReactNode}) => {
    const [isLoggedIn, setIsLoggedIn] = useState(false);
    const [isLoading, setIsLoading] = useState(true);
    const [patient, setPatient] = useState<Patient | null>(null);
    const [error, setError] = useState<string | null>(null);

    useEffect(() => {
        checkAutoLogin();
    }, []);

    const checkAutoLogin = async () => {
        try {
            const token = await tokenStorage.getToken();
            if (token) {
                const patientData = await apiClient.getCurrentPatient();
                setPatient(patientData);
                setIsLoggedIn(true);
            }
        } catch (err) {
            await tokenStorage.removeToken();
            console.log("Auto-login failed, token may be invalid");
        } finally {
            setIsLoading(false);
        }
    };

    const signIn = async (pairingCode: string) => {
        setIsLoading(true);
        setError(null);

        try {
            const response = await apiClient.login(pairingCode);
            setPatient(response.patient);
            setIsLoggedIn(true);
        } catch (err) {
            const apiError = err as ApiError;
            const errorMessage = apiError.errors?.pairing_code?.[0]
                || apiError.message
                || "Login failed. Please try again.";
            setError(errorMessage);
            throw err;
        } finally {
            setIsLoading(false);
        }
    };

    const signOut = async () => {
        setIsLoading(true);
        try {
            await apiClient.logout();
        } catch (err) {
            console.error("Logout error:", err);
        } finally {
            setPatient(null);
            setIsLoggedIn(false);
            setIsLoading(false);
        }
    };

    return (
        <AuthContext.Provider value={{isLoggedIn, isLoading, patient, error, signIn, signOut}}>
            {children}
        </AuthContext.Provider>
    );
};

export const useAuth = () => {
    const context = React.useContext(AuthContext);
    if (!context) {
        throw new Error("useAuth must be used within an AuthProvider");
    } 
    return context;
};