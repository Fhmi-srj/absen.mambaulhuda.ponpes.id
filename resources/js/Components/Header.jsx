import React, { useState, useRef, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function Header({ pageTitle, onMenuToggle }) {
    const { user, logout } = useAuth();
    const navigate = useNavigate();
    const [dropdownOpen, setDropdownOpen] = useState(false);
    const dropdownRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
                setDropdownOpen(false);
            }
        };
        document.addEventListener('mousedown', handleClickOutside);
        return () => document.removeEventListener('mousedown', handleClickOutside);
    }, []);

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    return (
        <header className="bg-white shadow-sm sticky top-0 z-30">
            <div className="px-4 lg:px-6 py-3 flex items-center justify-between">
                {/* Left side */}
                <div className="flex items-center gap-3">
                    <button
                        onClick={onMenuToggle}
                        className="hidden p-2 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <i className="fas fa-bars text-gray-600"></i>
                    </button>
                    <h1 className="text-lg font-bold text-gray-800">
                        {pageTitle || 'Aktivitas Santri'}
                    </h1>
                </div>

                {/* Right side - User dropdown */}
                <div className="relative" ref={dropdownRef}>
                    <button
                        onClick={() => setDropdownOpen(!dropdownOpen)}
                        className="w-10 h-10 flex items-center justify-center hover:bg-gray-100 rounded-full transition-colors overflow-hidden border border-gray-100"
                    >
                        <div className="w-full h-full bg-gradient-to-br from-blue-500 to-blue-400 flex items-center justify-center text-white text-lg">
                            <i className="fas fa-user"></i>
                        </div>
                    </button>

                    {/* Dropdown Menu */}
                    {dropdownOpen && (
                        <div className="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg py-2 z-50">
                            <div className="px-4 py-2 border-b border-gray-100/50">
                                <p className="font-medium text-gray-800">{user?.name}</p>
                                <p className="text-xs text-gray-500 capitalize">{user?.role}</p>
                            </div>
                            <button
                                onClick={() => { navigate('/profil'); setDropdownOpen(false); }}
                                className="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2"
                            >
                                <i className="fas fa-user w-4 text-center"></i>
                                Profil
                            </button>
                            <button
                                onClick={handleLogout}
                                className="w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-red-50 flex items-center gap-2"
                            >
                                <i className="fas fa-sign-out-alt w-4 text-center"></i>
                                Keluar
                            </button>
                        </div>
                    )}
                </div>
            </div>
        </header>
    );
}
