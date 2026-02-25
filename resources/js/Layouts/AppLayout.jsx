import React, { useState } from 'react';
import { useLocation } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import Header from '../Components/Header';
import Sidebar from '../Components/Sidebar';
import BottomNav from '../Components/BottomNav';

export default function AppLayout({ children }) {
    const { user } = useAuth();
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const location = useLocation();

    return (
        <div className="min-h-screen bg-gray-50">

            {/* Sidebar - Desktop Only */}
            <div className="hidden lg:block">
                <Sidebar user={user} />
            </div>

            {/* Main Content */}
            <div className="lg:ml-60">
                {/* Header */}
                <Header
                    user={user}
                    pageTitle=""
                    onMenuToggle={() => setSidebarOpen(!sidebarOpen)}
                />

                {/* Page Content */}
                <main className="p-4 lg:p-6 pb-24 lg:pb-6">
                    <div key={location.pathname} className="page-enter flex flex-col flex-1">
                        {children}
                    </div>
                </main>
            </div>

            {/* Bottom Navigation - Mobile Only */}
            <div className="lg:hidden">
                <BottomNav user={user} />
            </div>
        </div>
    );
}

