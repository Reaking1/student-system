import { useState, useEffect, useCallback } from "react";
import * as authService from "../services/authService";

export function useAuth() {
  const [user, setUser] = useState(() => authService.getCurrentUser());
  const [loading, setLoading] = useState(false);

  // Login function
  const login = useCallback(async (username, password) => {
    setLoading(true);
    try {
      const loggedInUser = await authService.login(username, password);
      setUser(loggedInUser);
      setLoading(false);
      return loggedInUser;
    } catch (error) {
      setLoading(false);
      throw error;
    }
  }, []);

  // Logout function
  const logout = useCallback(async () => {
    setLoading(true);
    try {
      await authService.logout();
      setUser(null);
      setLoading(false);
    } catch (error) {
      setLoading(false);
      console.error("Logout failed", error);
    }
  }, []);

  // Check if user is admin
  const isAdmin = user?.role === "admin";

  // Check if user is logged in
  const isLoggedIn = !!user;

  // Optionally, keep state in sync with localStorage changes
  useEffect(() => {
    const handleStorageChange = () => {
      const currentUser = authService.getCurrentUser();
      setUser(currentUser);
    };

    window.addEventListener("storage", handleStorageChange);
    return () => window.removeEventListener("storage", handleStorageChange);
  }, []);

  return { user, login, logout, isAdmin, isLoggedIn, loading };
}
