import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function AdminTrash() {
    const [isLoading, setIsLoading] = useState(true);
    const [trashData, setTrashData] = useState({ santri: [], aktivitas: [], absensi: [], users: [] });
    const [deleters, setDeleters] = useState({});
    const [autoPurgeEnabled, setAutoPurgeEnabled] = useState(false);
    const [autoPurgeDays, setAutoPurgeDays] = useState(30);
    const [activeTab, setActiveTab] = useState('santri');
    const [selectedIds, setSelectedIds] = useState([]);
    const [isSettingsModalOpen, setIsSettingsModalOpen] = useState(false);

    const tableMap = { santri: 'data_induk', aktivitas: 'catatan_aktivitas', absensi: 'attendances', users: 'users' };

    useEffect(() => {
        document.title = 'Trash - Admin';
        fetchData();
    }, []);

    const fetchData = async () => {
        setIsLoading(true);
        try {
            const response = await fetch('/admin/trash', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setTrashData(data.trashData);
            setDeleters(data.deleters);
            setAutoPurgeEnabled(data.autoPurgeEnabled);
            setAutoPurgeDays(data.autoPurgeDays);
            setSelectedIds([]);
        } catch (error) {
            console.error('Error fetching trash data:', error);
            Swal.fire('Error', 'Gagal mengambil data trash', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleAction = async (action, id, nama) => {
        const isRestore = action === 'restore';
        const result = await Swal.fire({
            title: isRestore ? 'Restore Data?' : 'Hapus Permanen?',
            html: isRestore ? `Kembalikan data <strong>${nama}</strong>?` : `Hapus permanen <strong>${nama}</strong>?<br/><small class="text-red-500">Tindakan ini tidak bisa dibatalkan!</small>`,
            icon: isRestore ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isRestore ? '#10b981' : '#ef4444',
            confirmButtonText: isRestore ? 'Ya, Restore' : 'Ya, Hapus Permanen',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            try {
                const url = isRestore ? '/admin/trash/restore' : '/admin/trash/permanent-delete';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({ table: tableMap[activeTab], id })
                });

                if (response.ok) {
                    Swal.fire('Berhasil', isRestore ? 'Data berhasil dikembalikan' : 'Data dihapus permanen', 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        }
    };

    const handleBulkAction = async (action) => {
        if (selectedIds.length === 0) return;

        const isRestore = action === 'restore';
        const result = await Swal.fire({
            title: isRestore ? `Restore ${selectedIds.length} Data?` : `Hapus Permanen ${selectedIds.length} Data?`,
            icon: isRestore ? 'question' : 'warning',
            showCancelButton: true,
            confirmButtonColor: isRestore ? '#10b981' : '#ef4444',
            confirmButtonText: isRestore ? 'Ya, Restore' : 'Ya, Hapus Permanen'
        });

        if (result.isConfirmed) {
            try {
                const url = isRestore ? '/admin/trash/bulk-restore' : '/admin/trash/bulk-delete';
                const response = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    },
                    body: JSON.stringify({ table: tableMap[activeTab], ids: selectedIds })
                });

                if (response.ok) {
                    Swal.fire('Berhasil', `${selectedIds.length} data berhasil diproses`, 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        }
    };

    const handleEmptyTrash = async () => {
        const result = await Swal.fire({
            title: 'Kosongkan Semua Trash?',
            text: 'Semua data di trash akan dihapus permanen!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: 'Ya, Kosongkan!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch('/admin/trash/empty', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    }
                });
                if (response.ok) {
                    Swal.fire('Berhasil', 'Trash telah dikosongkan', 'success');
                    fetchData();
                }
            } catch (error) {
                Swal.fire('Error', 'Gagal mengosongkan trash', 'error');
            }
        }
    };

    const handleSaveSettings = async (e) => {
        e.preventDefault();
        try {
            const formData = new FormData(e.target);
            const response = await fetch('/admin/trash/settings', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: formData
            });

            if (response.ok) {
                Swal.fire('Berhasil', 'Pengaturan berhasil disimpan', 'success');
                setIsSettingsModalOpen(false);
                fetchData();
            }
        } catch (error) {
            Swal.fire('Error', 'Gagal menyimpan pengaturan', 'error');
        }
    };

    const renderTable = () => {
        const data = trashData[activeTab] || [];

        return (
            <div className="overflow-x-auto">
                <table className="w-full text-left">
                    <thead className="bg-gray-50/50">
                        <tr>
                            <th className="px-6 py-4">
                                <input
                                    type="checkbox"
                                    className="rounded border-gray-300 text-blue-500"
                                    checked={selectedIds.length === data.length && data.length > 0}
                                    onChange={(e) => setSelectedIds(e.target.checked ? data.map(d => d.id) : [])}
                                />
                            </th>
                            <th className="px-3 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Informasi</th>
                            <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest text-center">Detail</th>
                            <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Dihapus Pada</th>
                            <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody className="divide-y divide-gray-50">
                        {data.length === 0 ? (
                            <tr>
                                <td colSpan="5" className="px-6 py-12 text-center text-gray-400">
                                    <i className="fas fa-trash-alt text-4xl mb-3 block opacity-20"></i>
                                    <span className="text-sm font-medium">Trash kosong di kategori ini</span>
                                </td>
                            </tr>
                        ) : (
                            data.map((item) => (
                                <tr key={item.id} className={`hover:bg-gray-50/50 transition-colors ${selectedIds.includes(item.id) ? 'bg-blue-50/30' : ''}`}>
                                    <td className="px-6 py-4">
                                        <input
                                            type="checkbox"
                                            className="rounded border-gray-300 text-blue-500"
                                            checked={selectedIds.includes(item.id)}
                                            onChange={() => setSelectedIds(prev => prev.includes(item.id) ? prev.filter(i => i !== item.id) : [...prev, item.id])}
                                        />
                                    </td>
                                    <td className="px-3 py-4">
                                        <div className="font-bold text-gray-700">{item.nama_lengkap || item.name || item.judul || '-'}</div>
                                        <div className="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                                            {activeTab === 'santri' ? `${item.nisn} | Kelas ${item.kelas}` :
                                                activeTab === 'users' ? `${item.role} | ${item.email}` :
                                                    activeTab === 'aktivitas' ? (
                                                        <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${item.kategori === 'sakit' ? 'bg-red-100 text-red-500' :
                                                            item.kategori === 'izin_keluar' ? 'bg-amber-100 text-amber-500' :
                                                                item.kategori === 'izin_pulang' ? 'bg-orange-100 text-orange-500' :
                                                                    item.kategori === 'sambangan' ? 'bg-emerald-100 text-emerald-500' :
                                                                        item.kategori === 'pelanggaran' ? 'bg-pink-100 text-pink-500' :
                                                                            'bg-blue-100 text-blue-500'
                                                            }`}>
                                                            {item.kategori?.replace('_', ' ')}
                                                        </span>
                                                    ) :
                                                        `${item.status}`}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        {activeTab === 'absensi' && <div className="text-xs font-medium text-gray-500">Pukul {item.attendance_time?.substring(0, 5)}</div>}
                                        {activeTab === 'aktivitas' && <div className="text-xs font-medium text-gray-500">{item.judul}</div>}
                                        <div className="text-[10px] text-gray-400">Oleh: {deleters[item.deleted_by] || 'System/Admin'}</div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="text-xs font-bold text-gray-700">
                                            {new Date(item.deleted_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                        </div>
                                        <div className="text-[10px] text-gray-400 font-medium">
                                            {new Date(item.deleted_at).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <div className="flex gap-1">
                                            <button
                                                onClick={() => handleAction('restore', item.id, item.nama_lengkap || item.name || item.judul)}
                                                className="w-8 h-8 flex items-center justify-center text-green-500 hover:bg-green-50 rounded-lg transition-all"
                                                title="Restore"
                                            >
                                                <i className="fas fa-undo"></i>
                                            </button>
                                            <button
                                                onClick={() => handleAction('delete', item.id, item.nama_lengkap || item.name || item.judul)}
                                                className="w-8 h-8 flex items-center justify-center text-red-400 hover:bg-red-50 rounded-lg transition-all"
                                                title="Hapus Permanen"
                                            >
                                                <i className="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            ))
                        )}
                    </tbody>
                </table>
            </div>
        );
    };

    return (
        <div className="pb-24">
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                <div className="flex items-center gap-3">
                    <div className="bg-red-500 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg">
                        <i className="fas fa-trash-restore"></i>
                    </div>
                    <div>
                        <h5 className="font-bold text-gray-800 mb-0">Recycle Bin</h5>
                        <p className="text-xs text-gray-500">Kelola data yang telah dihapus sementara</p>
                    </div>
                </div>

                <div className="flex gap-2">
                    <button
                        onClick={() => setIsSettingsModalOpen(true)}
                        className="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-xl text-sm font-bold shadow-sm hover:bg-gray-50 transition-all"
                    >
                        <i className="fas fa-cog mr-1"></i> Pengaturan
                    </button>
                    <button
                        onClick={handleEmptyTrash}
                        className="px-4 py-2 bg-red-500 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-red-600 transition-all"
                    >
                        <i className="fas fa-trash-alt mr-1"></i> Kosongkan
                    </button>
                </div>
            </div>

            {/* Tabs */}
            <div className="flex bg-white rounded-2xl p-1 shadow-sm border border-gray-100 mb-6 overflow-x-auto">
                {[
                    { id: 'santri', label: 'Santri', icon: 'fa-users', color: 'text-orange-500' },
                    { id: 'aktivitas', label: 'Aktivitas', icon: 'fa-clipboard-list', color: 'text-indigo-500' },
                    { id: 'absensi', label: 'Absensi', icon: 'fa-calendar-check', color: 'text-emerald-500' },
                    { id: 'users', label: 'Users', icon: 'fa-user-cog', color: 'text-red-500' },
                ].map((tab) => (
                    <button
                        key={tab.id}
                        onClick={() => { setActiveTab(tab.id); setSelectedIds([]); }}
                        className={`flex-1 flex items-center justify-center gap-2 py-3 px-4 rounded-xl text-xs font-black uppercase tracking-widest transition-all whitespace-nowrap ${activeTab === tab.id ? 'bg-gray-50 text-blue-600 shadow-sm' : 'text-gray-400 hover:text-gray-600'
                            }`}
                    >
                        <i className={`fas ${tab.icon} ${activeTab === tab.id ? tab.color : ''}`}></i>
                        {tab.label}
                        <span className={`px-1.5 py-0.5 rounded-full text-[9px] ${activeTab === tab.id ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-400'}`}>
                            {trashData[tab.id]?.length || 0}
                        </span>
                    </button>
                ))}
            </div>

            {/* Bulk Actions Bar */}
            {selectedIds.length > 0 && (
                <div className="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-4 flex items-center justify-between animate-in fade-in slide-in-from-top-2">
                    <span className="text-blue-600 font-bold tracking-tight px-2">
                        <i className="fas fa-check-circle mr-2"></i>
                        {selectedIds.length} terpilih
                    </span>
                    <div className="flex gap-2">
                        <button
                            onClick={() => handleBulkAction('restore')}
                            className="bg-green-500 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-green-600 transition-all"
                        >
                            Restore
                        </button>
                        <button
                            onClick={() => handleBulkAction('delete')}
                            className="bg-red-500 text-white px-5 py-2 rounded-xl text-sm font-bold shadow-sm hover:bg-red-600 transition-all"
                        >
                            Hapus Permanen
                        </button>
                    </div>
                </div>
            )}

            {/* Content Card */}
            <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                {isLoading ? (
                    <div className="py-20 flex flex-col items-center justify-center text-gray-400">
                        <div className="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-500 mb-4"></div>
                        <span className="text-sm font-medium">Melacak data terhapus...</span>
                    </div>
                ) : renderTable()}
            </div>

            {/* Settings Modal */}
            {isSettingsModalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 overflow-y-auto">
                    <div className="bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden animate-in zoom-in-95 duration-200">
                        <div className="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4 flex items-center justify-between text-white">
                            <h6 className="font-bold mb-0 flex items-center gap-2">
                                <i className="fas fa-cog"></i> Pengaturan Trash
                            </h6>
                            <button onClick={() => setIsSettingsModalOpen(false)} className="text-white/80 hover:text-white"><i className="fas fa-times"></i></button>
                        </div>
                        <form onSubmit={handleSaveSettings} className="p-6 space-y-5">
                            <div className="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                <div>
                                    <div className="text-sm font-bold text-gray-700">Auto-Hapus Permanen</div>
                                    <div className="text-[10px] text-gray-500">Hapus otomatis data lama</div>
                                </div>
                                <label className="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="auto_purge_enabled" className="sr-only peer" defaultChecked={autoPurgeEnabled} />
                                    <div className="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div>
                                <label className="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Hapus setelah (hari)</label>
                                <input
                                    type="number"
                                    name="auto_purge_days"
                                    defaultValue={autoPurgeDays}
                                    min="1"
                                    max="365"
                                    className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-blue-500 focus:ring-0 transition-colors"
                                />
                                <p className="text-[10px] text-gray-500 mt-2 px-1">Data yang sudah di trash lebih dari jumlah hari ini akan dihapus permanen secara otomatis oleh sistem.</p>
                            </div>

                            <div className="flex gap-3">
                                <button type="button" onClick={() => setIsSettingsModalOpen(false)} className="flex-1 py-3 bg-gray-100 text-gray-600 rounded-xl font-bold text-sm">Batal</button>
                                <button type="submit" className="flex-1 py-3 bg-blue-500 text-white rounded-xl font-bold text-sm shadow-md shadow-blue-100">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </div>
    );
}
