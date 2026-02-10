import React from 'react';

export default function LoadingSpinner({ size = 'medium', color = 'blue', text = 'Memuat data...' }) {
    const sizeClasses = {
        small: 'w-6 h-6 border-2',
        medium: 'w-10 h-10 border-4',
        large: 'w-16 h-16 border-4'
    };

    const colorClasses = {
        blue: 'border-blue-500 border-t-transparent text-blue-500',
        emerald: 'border-emerald-500 border-t-transparent text-emerald-500',
        indigo: 'border-indigo-500 border-t-transparent text-indigo-500',
        slate: 'border-slate-500 border-t-transparent text-slate-500'
    };

    return (
        <div className="flex flex-col items-center justify-center p-12 w-full min-h-[200px] animate-in fade-in duration-500">
            <div className={`animate-spin rounded-full ${sizeClasses[size]} ${colorClasses[color]}`}></div>
            {text && (
                <p className="mt-4 text-sm font-medium text-slate-500 animate-pulse uppercase tracking-widest">
                    {text}
                </p>
            )}
        </div>
    );
}
