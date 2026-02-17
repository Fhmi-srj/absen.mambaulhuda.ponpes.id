import React, { useState, useMemo } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

export default function Menu() {
    const { user } = useAuth();
    const role = user?.role || 'user';
    const navigate = useNavigate();
    const [searchQuery, setSearchQuery] = useState('');

    const menuGroups = [
        {
            title: '',
            items: [
                { to: '/beranda', icon: 'fa-home', label: 'Beranda' },
            ]
        },
        {
            title: 'ABSENSI - SCAN QR',
            items: [
                { to: '/pemindai', icon: 'fa-qrcode', label: 'Pemindai QR Code' },
            ]
        },
        {
            title: 'KARTU RFID',
            items: [
                { to: '/daftar-rfid', icon: 'fa-id-card', label: 'Daftarkan Kartu' },
            ]
        },
        {
            title: 'MONITORING',
            items: [
                { to: '/absensi-langsung', icon: 'fa-broadcast-tower', label: 'Absensi Langsung' },
                { to: '/riwayat', icon: 'fa-history', label: 'Riwayat Absensi' },
            ]
        },
        {
            title: 'SANTRI',
            items: [
                { to: '/aktivitas', icon: 'fa-clipboard-list', label: 'Aktivitas Santri' },
            ]
        },
    ];

    if (role === 'admin') {
        menuGroups.push({
            title: 'MENU ADMIN',
            items: [
                { to: '/admin/pengguna', icon: 'fa-users-cog', label: 'Manajemen Pengguna' },
                { to: '/admin/santri', icon: 'fa-user-graduate', label: 'Data Santri' },
                { to: '/admin/santri-import', icon: 'fa-file-import', label: 'Import Santri' },
                { to: '/admin/jadwal', icon: 'fa-calendar-alt', label: 'Jadwal Absen' },
                { to: '/admin/absensi-manual', icon: 'fa-edit', label: 'Absensi Manual' },
                { to: '/admin/laporan', icon: 'fa-chart-bar', label: 'Laporan' },
                { to: '/admin/log-aktivitas', icon: 'fa-history', label: 'Log Aktivitas' },
                { to: '/admin/trash', icon: 'fa-trash-restore', label: 'Trash' },
            ]
        });
    }

    menuGroups.push({
        title: 'PENGATURAN',
        items: [
            { to: '/profil', icon: 'fa-user', label: 'Profil' },
            ...(role === 'admin' ? [{ to: '/admin/pengaturan', icon: 'fa-cog', label: 'Pengaturan Sistem' }] : []),
        ]
    });

    const filteredGroups = useMemo(() => {
        if (!searchQuery) return menuGroups;
        const q = searchQuery.toLowerCase();
        return menuGroups.map(group => ({
            ...group,
            items: group.items.filter(item =>
                item.label.toLowerCase().includes(q) ||
                (item.subtitle && item.subtitle.toLowerCase().includes(q))
            )
        })).filter(group => group.items.length > 0);
    }, [searchQuery, menuGroups]);

    return (
        <div className="bg-white min-h-screen pb-24 -mx-4 -mt-4">
            {/* Search Bar */}
            <div className="px-4 py-4">
                <div className="relative group">
                    <i className="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-500 transition-colors"></i>
                    <input
                        type="text"
                        value={searchQuery}
                        onChange={(e) => setSearchQuery(e.target.value)}
                        placeholder="Cari menu..."
                        className="w-full bg-gray-100 border-0 rounded-xl py-3 pl-12 pr-4 text-gray-900 focus:ring-2 focus:ring-blue-100 focus:bg-white transition-all outline-none"
                    />
                </div>
            </div>

            {/* Menu List */}
            <div className="space-y-6 mt-2">
                {filteredGroups.map((group, gIdx) => (
                    <div key={gIdx} className="space-y-1">
                        {group.title && (
                            <div className="px-4 mb-2 flex justify-between items-center">
                                <span className="text-xs font-bold text-gray-400 uppercase tracking-wider">{group.title}</span>
                            </div>
                        )}
                        <div className="divide-y divide-gray-50 border-y border-gray-50 bg-white">
                            {group.items.map((item, iIdx) => (
                                <NavLink
                                    key={iIdx}
                                    to={item.to}
                                    className="flex items-center gap-4 px-4 py-3.5 hover:bg-gray-50 active:bg-gray-100 transition-all group"
                                >
                                    <div className="w-8 h-8 flex items-center justify-center text-gray-700 text-lg group-hover:scale-110 transition-transform">
                                        <i className={`fas ${item.icon}`}></i>
                                    </div>
                                    <div className="flex-1">
                                        <p className="font-semibold text-gray-700 tracking-tight leading-none">{item.label}</p>
                                        {item.subtitle && (
                                            <p className="text-[10px] text-gray-400 mt-1 line-clamp-1">{item.subtitle}</p>
                                        )}
                                    </div>
                                    <i className="fas fa-chevron-right text-gray-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all text-sm"></i>
                                </NavLink>
                            ))}
                        </div>
                    </div>
                ))}

                {filteredGroups.length === 0 && (
                    <div className="px-4 py-12 text-center">
                        <i className="fas fa-search text-gray-200 text-4xl mb-4"></i>
                        <p className="text-gray-500 font-medium">Menu tidak ditemukan</p>
                    </div>
                )}
            </div>

            <div className="px-4 py-6 text-center">
                <p className="text-[10px] text-gray-300 font-bold uppercase tracking-widest">Versi 2.0.0-Beta</p>
            </div>
        </div>
    );
}
