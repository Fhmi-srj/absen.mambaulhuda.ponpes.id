import React from 'react';

export default function StandaloneLayout({ children }) {
    return (
        <div className="min-h-screen bg-slate-900 text-slate-100 font-sans selection:bg-indigo-500/30">
            <main>
                {children}
            </main>
        </div>
    );
}
