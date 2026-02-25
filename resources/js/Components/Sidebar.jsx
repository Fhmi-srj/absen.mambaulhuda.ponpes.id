import React from 'react';
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
        { to: '/kios', icon: 'fa-tablet-alt', label: 'Absensi' },
        { to: '/aktivitas', icon: 'fa-clipboard-list', label: 'Aktivitas' },
        { to: '/pemindai', icon: 'fa-qrcode', label: 'Scan QR' },
        { to: '/riwayat', icon: 'fa-history', label: 'Riwayat' },
        { to: '/absensi-langsung', icon: 'fa-broadcast-tower', label: 'Live Attendance' },
        { to: '/daftar-rfid', icon: 'fa-id-card', label: 'Daftar RFID' },
        { to: '/profil', icon: 'fa-user', label: 'Profil' },
    ];

    const adminMenuItems = [
        { to: '/admin/pengguna', icon: 'fa-users-cog', label: 'Pengguna' },
        { to: '/admin/santri', icon: 'fa-user-graduate', label: 'Data Santri' },
        { to: '/admin/jadwal', icon: 'fa-calendar-alt', label: 'Jadwal Absen' },
        { to: '/admin/absensi-manual', icon: 'fa-edit', label: 'Absensi Manual' },
        { to: '/admin/laporan', icon: 'fa-chart-bar', label: 'Laporan' },
        { to: '/admin/log-aktivitas', icon: 'fa-history', label: 'Log Aktivitas' },
        { to: '/admin/trash', icon: 'fa-trash-restore', label: 'Recycle Bin' },
        { to: '/admin/pengaturan', icon: 'fa-cog', label: 'Pengaturan' },
        { to: '/admin/santri-import', icon: 'fa-file-import', label: 'Import Santri' },
    ];

    const navClass = ({ isActive }) =>
        `flex items-center gap-3 px-3 py-2 rounded-md text-sm font-medium transition-all duration-150 group
        ${isActive
            ? 'bg-blue-50 text-blue-600 border-l-2 border-blue-500'
            : 'text-gray-500 hover:bg-gray-100 hover:text-gray-800 border-l-2 border-transparent'
        }`;

    return (
        <aside className="fixed left-0 top-0 h-full w-60 bg-gray-50 border-r border-gray-200 z-40 flex flex-col">

            {/* Branding */}
            <div className="px-5 py-4 border-b border-gray-200">
                <div className="flex items-center gap-3 mb-4">
                    <img src="/logo-pondok.png" alt="Logo Mambaul Huda" className="w-10 h-10 object-contain flex-shrink-0" />
                    <div>
                        <p className="text-sm font-extrabold text-gray-800 leading-tight tracking-tight">MAMBAUL HUDA</p>
                        <p className="text-[11px] text-gray-400">Aktivitas Santri</p>
                    </div>
                </div>

            </div>

            {/* Navigation */}
            <nav className="flex-1 px-3 py-4 overflow-y-auto space-y-5">

                {/* Menu */}
                <div>
                    <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 px-3">Menu</p>
                    <div className="space-y-0.5">
                        {userMenuItems.map((item) => (
                            <NavLink key={item.to} to={item.to} className={navClass}>
                                <i className={`fas ${item.icon} w-4 text-center`}></i>
                                <span>{item.label}</span>
                            </NavLink>
                        ))}
                    </div>
                </div>

                {/* Admin */}
                {role === 'admin' && (
                    <div>
                        <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 px-3">Admin</p>
                        <div className="space-y-0.5">
                            {adminMenuItems.map((item) => (
                                <NavLink key={item.to} to={item.to} className={navClass}>
                                    <i className={`fas ${item.icon} w-4 text-center`}></i>
                                    <span>{item.label}</span>
                                </NavLink>
                            ))}
                        </div>
                    </div>
                )}
            </nav>

            {/* Logout */}
            <div className="px-3 py-3 border-t border-gray-200">
                <button
                    onClick={handleLogout}
                    className="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-500 hover:bg-red-50 hover:text-red-600 rounded-md transition-all duration-150"
                >
                    <i className="fas fa-sign-out-alt w-4 text-center"></i>
                    <span>Keluar</span>
                </button>
            </div>
        </aside>
    );
}
