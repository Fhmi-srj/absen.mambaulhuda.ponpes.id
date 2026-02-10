import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function AdminLogAktivitas() {
    const [isLoading, setIsLoading] = useState(true);
    const [logs, setLogs] = useState([]);
    const [roles, setRoles] = useState([]);
    const [total, setTotal] = useState(0);
    const [totalPages, setTotalPages] = useState(0);
    const [page, setPage] = useState(1);
    const [selectedIds, setSelectedIds] = useState([]);

    const [filters, setFilters] = useState({
        role: '',
        action_type: '',
        date_from: '',
        date_to: ''
    });

    useEffect(() => {
        document.title = 'Log Aktivitas - Admin';
        fetchData();
    }, [page]);

    const fetchData = async (currentFilters = filters) => {
        setIsLoading(true);
        try {
            const queryParams = new URLSearchParams({ ...currentFilters, page }).toString();
            const response = await fetch(`/admin/log-aktivitas?${queryParams}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setLogs(data.logs);
            setRoles(data.roles);
            setTotal(data.total);
            setTotalPages(data.totalPages);
            setSelectedIds([]);
        } catch (error) {
            console.error('Error fetching data:', error);
            Swal.fire('Error', 'Gagal mengambil log aktivitas', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleFilterChange = (e) => {
        const { name, value } = e.target;
        setFilters(prev => ({ ...prev, [name]: value }));
    };

    const handleFilterSubmit = (e) => {
        e.preventDefault();
        setPage(1);
        fetchData();
    };

    const handleSelectAll = (e) => {
        if (e.target.checked) {
            setSelectedIds(logs.map(log => log.id));
        } else {
            setSelectedIds([]);
        }
    };

    const handleSelectRow = (id) => {
        setSelectedIds(prev =>
            prev.includes(id) ? prev.filter(item => item !== id) : [...prev, id]
        );
    };

    const handleDeleteSingle = async (id) => {
        const result = await Swal.fire({
            title: 'Hapus Log Ini?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/admin/log-aktivitas/${id}/delete`, {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire('Berhasil', data.message, 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Gagal menghapus log', 'error');
            }
        }
    };

    const handleBulkDelete = async () => {
        if (selectedIds.length === 0) return;

        const result = await Swal.fire({
            title: `Hapus ${selectedIds.length} log?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('/admin/log-aktivitas/bulk-delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({ ids: selectedIds })
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire('Berhasil', data.message, 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Gagal menghapus log', 'error');
            }
        }
    };

    const handleClearAll = async () => {
        const result = await Swal.fire({
            title: 'Hapus Semua Log?',
            text: 'Tindakan ini tidak dapat dibatalkan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Hapus Semua!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('/admin/log-aktivitas/clear-all', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();
                if (data.status === 'success') {
                    Swal.fire('Berhasil', data.message, 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Gagal mengosongkan log', 'error');
            }
        }
    };

    const getActionBadge = (action) => {
        const base = "px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider ";
        switch (action.toUpperCase()) {
            case 'LOGIN': return base + "bg-blue-100 text-blue-600";
            case 'LOGOUT': return base + "bg-slate-100 text-slate-600";
            case 'CREATE': return base + "bg-green-100 text-green-600";
            case 'UPDATE': return base + "bg-amber-100 text-amber-600";
            case 'DELETE': return base + "bg-red-100 text-red-600";
            case 'RESTORE': return base + "bg-purple-100 text-purple-600";
            default: return base + "bg-gray-100 text-gray-600";
        }
    };

    return (
        <div className="pb-24">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div className="flex items-center gap-3">
                    <div className="bg-slate-500 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i className="fas fa-history"></i>
                    </div>
                    <div>
                        <h5 className="font-bold text-gray-800 mb-0">Log Aktivitas</h5>
                        <p className="text-xs text-gray-500">Audit trail penggunaan sistem</p>
                    </div>
                </div>

                <button
                    onClick={handleClearAll}
                    className="flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 text-red-600 rounded-xl font-bold text-sm border border-red-100 hover:bg-red-100 active:scale-95 transition-all"
                >
                    <i className="fas fa-trash-alt"></i>
                    Hapus Semua
                </button>
            </div>

            {/* Filters */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6">
                <form onSubmit={handleFilterSubmit} className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Role</label>
                        <select
                            name="role"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.role}
                            onChange={handleFilterChange}
                        >
                            <option value="">Semua Role</option>
                            {roles.map(r => (
                                <option key={r} value={r}>{r.toUpperCase()}</option>
                            ))}
                        </select>
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Aktivitas</label>
                        <select
                            name="action_type"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.action_type}
                            onChange={handleFilterChange}
                        >
                            <option value="">Semua Aktivitas</option>
                            <option value="LOGIN">LOGIN</option>
                            <option value="LOGOUT">LOGOUT</option>
                            <option value="CREATE">CREATE</option>
                            <option value="UPDATE">UPDATE</option>
                            <option value="DELETE">DELETE</option>
                            <option value="RESTORE">RESTORE</option>
                        </select>
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Dari</label>
                        <input
                            type="date"
                            name="date_from"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.date_from}
                            onChange={handleFilterChange}
                        />
                    </div>
                    <div>
                        <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Sampai</label>
                        <input
                            type="date"
                            name="date_to"
                            className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                            value={filters.date_to}
                            onChange={handleFilterChange}
                        />
                    </div>
                    <div className="flex gap-2">
                        <button
                            type="submit"
                            className="flex-1 py-2.5 bg-blue-500 text-white rounded-xl font-bold text-sm shadow-sm hover:bg-blue-600 transition-all"
                        >
                            Filter
                        </button>
                        <button
                            type="button"
                            onClick={() => {
                                setFilters({ role: '', action_type: '', date_from: '', date_to: '' });
                                setPage(1);
                                fetchData({ role: '', action_type: '', date_from: '', date_to: '' });
                            }}
                            className="px-4 py-2.5 bg-gray-100 text-gray-500 rounded-xl font-bold text-sm hover:bg-gray-200 transition-all"
                        >
                            Reset
                        </button>
                    </div>
                </form>
            </div>

            {/* Bulk Actions Bar */}
            {selectedIds.length > 0 && (
                <div className="bg-red-50 border border-red-100 rounded-2xl p-4 mb-4 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                    <span className="text-red-600 font-bold tracking-tight px-2 flex items-center gap-2">
                        <i className="fas fa-check-circle"></i>
                        {selectedIds.length} baris dipilih
                    </span>
                    <button
                        onClick={handleBulkDelete}
                        className="bg-red-500 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm shadow-red-200 hover:bg-red-600 transition-all"
                    >
                        Hapus Terpilih
                    </button>
                </div>
            )}

            {/* Table Card */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-gray-50/50">
                            <tr>
                                <th className="px-6 py-4">
                                    <input
                                        type="checkbox"
                                        className="rounded border-gray-300 text-blue-500 focus:ring-blue-500"
                                        checked={selectedIds.length === logs.length && logs.length > 0}
                                        onChange={handleSelectAll}
                                    />
                                </th>
                                <th className="px-3 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">User</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Aktivitas</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest whitespace-nowrap">Detail / Rekaman</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                                <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-50">
                            {isLoading ? (
                                <tr>
                                    <td colSpan="6" className="px-6 py-12 text-center">
                                        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                                    </td>
                                </tr>
                            ) : logs.length === 0 ? (
                                <tr>
                                    <td colSpan="6" className="px-6 py-12 text-center text-gray-400">
                                        <i className="fas fa-history text-4xl mb-3 block opacity-20"></i>
                                        <span className="text-sm font-medium">Belum ada log aktivitas</span>
                                    </td>
                                </tr>
                            ) : (
                                logs.map((log) => (
                                    <tr key={log.id} className={`hover:bg-gray-50/50 transition-colors ${selectedIds.includes(log.id) ? 'bg-blue-50/30' : ''}`}>
                                        <td className="px-6 py-4">
                                            <input
                                                type="checkbox"
                                                className="rounded border-gray-300 text-blue-500 focus:ring-blue-500"
                                                checked={selectedIds.includes(log.id)}
                                                onChange={() => handleSelectRow(log.id)}
                                            />
                                        </td>
                                        <td className="px-3 py-4">
                                            <div className="font-bold text-gray-700">{log.username || '-'}</div>
                                            <div className="text-[10px] font-black text-gray-400 uppercase tracking-widest">{log.user_role}</div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={getActionBadge(log.action)}>
                                                {log.action}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-xs text-gray-600 font-medium max-w-[200px] truncate" title={log.description}>
                                                {log.description || log.record_name || '-'}
                                            </div>
                                            {log.device_name && (
                                                <div className="inline-flex items-center gap-1 mt-1 px-1.5 py-0.5 bg-gray-100 text-gray-500 rounded text-[9px] font-bold">
                                                    <i className="fas fa-mobile-alt"></i> {log.device_name}
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="text-xs font-bold text-gray-700 whitespace-nowrap">
                                                {new Date(log.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                            </div>
                                            <div className="text-[10px] text-gray-400 font-medium">
                                                {new Date(log.created_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <button
                                                onClick={() => handleDeleteSingle(log.id)}
                                                className="w-8 h-8 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all"
                                            >
                                                <i className="fas fa-trash-alt text-xs"></i>
                                            </button>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {totalPages > 1 && (
                    <div className="px-6 py-4 bg-gray-50/50 border-t border-gray-100 flex items-center justify-between">
                        <span className="text-xs font-bold text-gray-400 uppercase tracking-widest">
                            Total: {total.toLocaleString()} records
                        </span>
                        <div className="flex gap-2">
                            <button
                                disabled={page === 1}
                                onClick={() => setPage(page - 1)}
                                className="px-3 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 disabled:opacity-50"
                            >
                                Prev
                            </button>
                            <div className="flex gap-1 items-center px-2">
                                <span className="text-xs font-black text-blue-500">{page}</span>
                                <span className="text-xs text-gray-300">/</span>
                                <span className="text-xs font-bold text-gray-400">{totalPages}</span>
                            </div>
                            <button
                                disabled={page === totalPages}
                                onClick={() => setPage(page + 1)}
                                className="px-3 py-1 bg-white border border-gray-200 rounded-lg text-xs font-bold text-gray-600 disabled:opacity-50"
                            >
                                Next
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
}
