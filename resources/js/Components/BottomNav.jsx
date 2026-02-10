import React from 'react';
import { NavLink } from 'react-router-dom';

export default function BottomNav({ user }) {
    const role = user?.role || 'user';

    const mainNavItems = [
        { to: '/beranda', icon: 'fa-home', label: 'Beranda' },
        { to: '/pemindai', icon: 'fa-qrcode', label: 'Scan' },
        { type: 'fab' }, // Placeholder for FAB
        { to: '/riwayat', icon: 'fa-history', label: 'Riwayat' },
        { to: '/menu', icon: 'fa-compass', label: 'Menu' },
    ];

    return (
        <nav className="fixed bottom-0 left-0 right-0 z-40">
            <div className="max-w-md mx-auto bg-white shadow-[0_-2px_10px_rgba(0,0,0,0.1)] border-t border-gray-100">
                <div className="flex items-center justify-around h-16 px-2">
                    {mainNavItems.map((item, idx) => {
                        if (item.type === 'fab') {
                            return (
                                <NavLink
                                    key="fab"
                                    to="/aktivitas"
                                    className={({ isActive }) =>
                                        `-mt-6 w-14 h-14 rounded-full flex items-center justify-center shadow-xl transition-all duration-300 cursor-pointer border-4 border-white ${isActive ? 'bg-blue-600' : 'bg-blue-500'}`
                                    }
                                >
                                    <i className="fas fa-clipboard-list text-white text-lg"></i>
                                </NavLink>
                            );
                        }

                        return (
                            <NavLink
                                key={item.to}
                                to={item.to}
                                className={({ isActive }) =>
                                    `flex flex-col items-center justify-center py-2 px-3 transition-colors ${isActive ? 'text-blue-600' : 'text-gray-400 hover:text-blue-500'}`
                                }
                            >
                                <i className={`fas ${item.icon} text-lg`}></i>
                                <span className="text-[10px] mt-1 font-medium">{item.label}</span>
                            </NavLink>
                        );
                    })}
                </div>
            </div>
        </nav>
    );
}
