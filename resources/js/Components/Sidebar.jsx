import React, { useState } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function Sidebar({ user }) {
    const navigate = useNavigate();
    const { logout } = useAuth();
    const role = user?.role || 'user';

    const handleLogout = async () => {
        await logout();
        navigate('/login');
    };

    const userMenuItems = [
        { to: '/beranda', icon: 'fa-home', label: 'Beranda' },
        { to: '/aktivitas', icon: 'fa-clipboard-list', label: 'Aktivitas' },
        { to: '/pemindai', icon: 'fa-qrcode', label: 'Scan QR' },
        { to: '/riwayat', icon: 'fa-history', label: 'Riwayat' },
        { to: '/absensi-langsung', icon: 'fa-broadcast-tower', label: 'Live Attendance' },
        { to: '/daftar-rfid', icon: 'fa-id-card', label: 'Daftar RFID' },
        { to: '/print-izin', icon: 'fa-print', label: 'Print Izin' },
        { to: '/profil', icon: 'fa-user', label: 'Profil' },
    ];

    const adminMenuItems = [
        { to: '/admin/pengguna', icon: 'fa-users-cog', label: 'Pengguna' },
        { to: '/admin/santri', icon: 'fa-user-graduate', label: 'Data Santri' },
        { to: '/admin/jadwal', icon: 'fa-calendar-alt', label: 'Jadwal Absen' },
        { to: '/admin/kehadiran', icon: 'fa-clipboard-check', label: 'Kehadiran' },
        { to: '/admin/absensi-manual', icon: 'fa-edit', label: 'Absensi Manual' },
        { to: '/admin/laporan', icon: 'fa-chart-bar', label: 'Laporan' },
        { to: '/admin/log-aktivitas', icon: 'fa-history', label: 'Log Aktivitas' },
        { to: '/admin/trash', icon: 'fa-trash-restore', label: 'Recycle Bin' },
        { to: '/admin/pengaturan', icon: 'fa-cog', label: 'Pengaturan' },
        { to: '/admin/santri-import', icon: 'fa-file-import', label: 'Import Santri' },
    ];

    return (
        <aside className="fixed left-0 top-0 h-full w-64 bg-white border-r border-gray-100/50 z-40 flex flex-col">
            {/* Header */}
            <div className="p-4 border-b border-gray-100/50">
                <div className="flex items-center gap-3">
                    <div className="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-400 rounded-full flex items-center justify-center text-white font-bold">
                        {user?.name?.charAt(0)?.toUpperCase() || 'U'}
                    </div>
                    <div className="flex-1 min-w-0">
                        <h3 className="font-semibold text-gray-800 truncate">{user?.name}</h3>
                        <span className="text-xs text-gray-500 capitalize">{user?.role}</span>
                    </div>
                </div>
            </div>

            {/* Navigation */}
            <nav className="flex-1 p-4 overflow-y-auto">
                {/* User Menu */}
                <div className="mb-6">
                    <p className="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 px-3">Menu</p>
                    <div className="space-y-1">
                        {userMenuItems.map((item) => (
                            <NavLink
                                key={item.to}
                                to={item.to}
                                className={({ isActive }) =>
                                    `flex items-center gap-3 px-3 py-2 rounded-lg transition-colors ${isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'}`
                                }
                            >
                                <i className={`fas ${item.icon} w-5 text-center`}></i>
                                <span className="font-medium">{item.label}</span>
                            </NavLink>
                        ))}
                    </div>
                </div>

                {/* Admin Menu */}
                {role === 'admin' && (
                    <div>
                        <p className="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2 px-3">Admin</p>
                        <div className="space-y-1">
                            {adminMenuItems.map((item) => (
                                <NavLink
                                    key={item.to}
                                    to={item.to}
                                    className={({ isActive }) =>
                                        `flex items-center gap-3 px-3 py-2 rounded-lg transition-colors ${isActive ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50'}`
                                    }
                                >
                                    <i className={`fas ${item.icon} w-5 text-center`}></i>
                                    <span className="font-medium">{item.label}</span>
                                </NavLink>
                            ))}
                        </div>
                    </div>
                )}
            </nav>

            {/* Footer */}
            <div className="p-4 border-t border-gray-100/50">
                <button
                    onClick={handleLogout}
                    className="w-full flex items-center gap-3 px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                >
                    <i className="fas fa-sign-out-alt w-5 text-center"></i>
                    <span className="font-medium">Keluar</span>
                </button>
            </div>
        </aside>
    );
}
