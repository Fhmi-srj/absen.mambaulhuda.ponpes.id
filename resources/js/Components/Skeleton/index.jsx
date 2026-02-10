import LoadingSpinner from '../LoadingSpinner';

export function CardSkeleton() {
    return <LoadingSpinner size="medium" text="Memuat item..." />;
}

export function TableSkeleton() {
    return <LoadingSpinner size="medium" text="Memuat data tabel..." />;
}

export function StatSkeleton() {
    return (
        <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            {[...Array(4)].map((_, i) => (
                <div key={i} className="bg-white dark:bg-gray-800 rounded-2xl p-4 flex items-center justify-center min-h-[100px] border border-slate-100">
                    <div className="animate-spin rounded-full w-6 h-6 border-2 border-blue-500 border-t-transparent"></div>
                </div>
            ))}
        </div>
    );
}

export function PageSkeleton() {
    return (
        <div className="min-h-[60vh] flex items-center justify-center">
            <LoadingSpinner size="large" text="Menyiapkan halaman..." />
        </div>
    );
}

export default { CardSkeleton, TableSkeleton, StatSkeleton, PageSkeleton };
