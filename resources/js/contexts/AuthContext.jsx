import React, { createContext, useState, useContext, useEffect } from 'react';
import axios from 'axios';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        // Configure axios defaults just in case bootstrap.js isn't loaded correctly
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        // Check if user is logged in on mount
        checkAuth();
    }, []);

    const checkAuth = async () => {
        try {
            const response = await axios.get('/api/profil');
            if (response.data && response.data.user) {
                setUser(response.data.user);
            } else {
                setUser(null);
            }
        } catch (error) {
            console.error('Auth check failed:', error);
            setUser(null);

            // If we get a 419 CSRF mismatch here, we might need a refresh
            if (error.response?.status === 419) {
                console.warn('CSRF token mismatch on auth check. Refreshing page...');
                window.location.reload();
            }
        } finally {
            setLoading(false);
        }
    };

    const login = async (email, password) => {
        try {
            // Optional: Call sanctum/csrf-cookie if using Sanctum
            // await axios.get('/sanctum/csrf-cookie');

            const response = await axios.post('/login', { email, password });

            if (response.status === 200 || response.data.status === 'success') {
                if (response.data.user) {
                    setUser(response.data.user);
                } else {
                    await checkAuth();
                }
                return { success: true };
            }
            return { success: false, message: response.data.message || 'Login gagal' };
        } catch (error) {
            console.error('Login error:', error);

            if (error.response?.status === 419) {
                return {
                    success: false,
                    message: 'Sesi kedaluwarsa (CSRF token mismatch). Silakan segarkan halaman dan coba lagi.'
                };
            }

            return {
                success: false,
                message: error.response?.data?.message || 'Email atau password salah'
            };
        }
    };

    const logout = async () => {
        try {
            await axios.post('/logout');
        } catch (error) {
            console.error('Logout error:', error);
        }
        setUser(null);
        window.location.href = '/login';
    };

    const value = {
        user,
        loading,
        login,
        logout,
        checkAuth,
        isAuthenticated: !!user,
    };

    return (
        <AuthContext.Provider value={value}>
            {children}
        </AuthContext.Provider>
    );
}

export function useAuth() {
    const context = useContext(AuthContext);
    if (!context) {
        throw new Error('useAuth must be used within an AuthProvider');
    }
    return context;
}

export default AuthContext;
