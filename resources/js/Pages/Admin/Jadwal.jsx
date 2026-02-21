import React, { useState, useEffect } from 'react';
import { PageSkeleton } from '../../Components/Skeleton';
import Swal from 'sweetalert2';

export default function Jadwal() {
    const [loading, setLoading] = useState(true);
    const [jadwalList, setJadwalList] = useState([]);
    const [modalOpen, setModalOpen] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [formData, setFormData] = useState({
        id: '',
        name: '',
        type: 'absen',
        start_time: '',
        scheduled_time: '',
        end_time: '',
        late_tolerance_minutes: 15,
        is_active: true,
        disable_daily_reset: false,
        no_reset_start_date: '',
        no_reset_end_date: '',
    });

    useEffect(() => {
        document.title = 'Jadwal Absen - Aktivitas Santri';
        fetchJadwal();
    }, []);

    const fetchJadwal = async () => {
        try {
            const response = await fetch('/admin/jadwal', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            if (response.ok) {
                const data = await response.json();
                setJadwalList(data.jadwalList || []);
            }
        } catch (error) {
            console.error('Error fetching jadwal:', error);
        } finally {
            setLoading(false);
        }
    };

    const resetForm = () => {
        setFormData({
            id: '', name: '', type: 'absen',
            start_time: '', scheduled_time: '', end_time: '',
            late_tolerance_minutes: 15, is_active: true, disable_daily_reset: false,
            no_reset_start_date: '', no_reset_end_date: '',
        });
        setIsEditing(false);
    };

    const openAddModal = () => {
        resetForm();
        setModalOpen(true);
    };

    const openEditModal = (j) => {
        setFormData({
            id: j.id,
            name: j.name || '',
            type: j.type || 'absen',
            start_time: j.start_time || '',
            scheduled_time: j.scheduled_time || '',
            end_time: j.end_time || '',
            late_tolerance_minutes: j.late_tolerance_minutes || 15,
            is_active: j.is_active !== undefined ? (j.is_active ? true : false) : true,
            disable_daily_reset: j.disable_daily_reset ? true : false,
            no_reset_start_date: j.no_reset_start_date || '',
            no_reset_end_date: j.no_reset_end_date || '',
        });
        setIsEditing(true);
        setModalOpen(true);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage({ type: '', text: '' });

        try {
            const form = new FormData();
            Object.keys(formData).forEach(key => {
                if (key === 'is_active' || key === 'disable_daily_reset') {
                    form.append(key, formData[key] ? '1' : '0');
                } else if (formData[key] !== '') {
                    form.append(key, formData[key]);
                }
            });

            const url = isEditing
                ? `/api/admin/jadwal/${formData.id}`
                : '/api/admin/jadwal';

            const response = await fetch(url, {
                method: 'POST',
                body: form,
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
            });

            const data = await response.json();
            if (data.success) {
                setMessage({ type: 'success', text: data.message });
                setModalOpen(false);
                fetchJadwal();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (id, name) => {
        const result = await Swal.fire({
            title: 'Hapus Jadwal?',
            text: `Yakin ingin menghapus jadwal "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/api/admin/jadwal/${id}`, {
                method: 'DELETE',
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                setMessage({ type: 'success', text: data.message });
                fetchJadwal();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        }
    };

    const formatTime = (time) => time ? time.substring(0, 5) : '--:--';

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>
            {/* Header */}
            <div className="flex flex-wrap justify-between items-center gap-4 mb-6">

                <button
                    onClick={openAddModal}
                    className="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600"
                >
                    <i className="fas fa-plus mr-2"></i>Tambah Jadwal
                </button>
            </div>

            {/* Message */}
            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                    <button onClick={() => setMessage({ type: '', text: '' })} className="float-right font-bold">&times;</button>
                </div>
            )}

            {/* Grid Cards */}
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
                {jadwalList.length === 0 ? (
                    <div className="col-span-full bg-white rounded-xl shadow-sm p-8 text-center">
                        <i className="fas fa-clock fa-3x text-gray-300 mb-3"></i>
                        <h5 className="text-gray-500 font-semibold">Belum ada jadwal</h5>
                        <p className="text-gray-400">Tambahkan jadwal absen terlebih dahulu</p>
                    </div>
                ) : (
                    jadwalList.map((j) => (
                        <div key={j.id} className={`bg-white rounded-xl shadow-sm p-5 ${!j.is_active ? 'opacity-50' : ''}`}>
                            <div className="flex justify-between items-start mb-3">
                                <h5 className="font-bold text-gray-800">{j.name}</h5>
                                <div className="flex gap-1 shrink-0 ml-2">
                                    <button onClick={() => openEditModal(j)} className="p-2 text-blue-500 hover:bg-blue-50 rounded">
                                        <i className="fas fa-edit"></i>
                                    </button>
                                    <button onClick={() => handleDelete(j.id, j.name)} className="p-2 text-red-500 hover:bg-red-50 rounded">
                                        <i className="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            {(!j.is_active || j.disable_daily_reset) && (
                                <div className="flex flex-wrap gap-1.5 mb-3">
                                    {!j.is_active && <span className="text-[10px] bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">Non-Aktif</span>}
                                    {j.disable_daily_reset && <span className="text-[10px] bg-amber-100 text-amber-600 px-2 py-0.5 rounded-full font-bold"><i className="fas fa-infinity mr-1"></i>Tanpa Reset Harian</span>}
                                </div>
                            )}
                            {j.disable_daily_reset ? (
                                <div className="mb-4">
                                    <div className="grid grid-cols-2 text-center gap-2">
                                        <div>
                                            <small className="text-gray-400 block">Tanggal Mulai</small>
                                            <strong className="text-green-500 text-sm">{j.no_reset_start_date || '-'}</strong>
                                        </div>
                                        <div>
                                            <small className="text-gray-400 block">Tanggal Selesai</small>
                                            <strong className="text-red-500 text-sm">{j.no_reset_end_date || 'Tidak ditentukan'}</strong>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <>
                                    <div className="grid grid-cols-3 text-center gap-2 mb-4">
                                        <div>
                                            <small className="text-gray-400 block">Mulai</small>
                                            <strong className="text-green-500">{formatTime(j.start_time)}</strong>
                                        </div>
                                        <div>
                                            <small className="text-gray-400 block">Tepat</small>
                                            <strong className="text-blue-500">{formatTime(j.scheduled_time)}</strong>
                                        </div>
                                        <div>
                                            <small className="text-gray-400 block">Tutup</small>
                                            <strong className="text-red-500">{formatTime(j.end_time)}</strong>
                                        </div>
                                    </div>
                                    <div className="text-center border-t pt-3">
                                        <small className="text-gray-400">
                                            <i className="fas fa-hourglass-half mr-1"></i>
                                            Toleransi: <strong>{j.late_tolerance_minutes} menit</strong>
                                        </small>
                                    </div>
                                </>
                            )}
                        </div>
                    ))
                )}
            </div>

            {/* Modal */}
            {modalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50" onClick={() => setModalOpen(false)}></div>
                    <div className="relative bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
                        <form onSubmit={handleSubmit}>
                            <div className="px-6 py-4 border-b flex justify-between items-center">
                                <h5 className="font-bold text-gray-800">{isEditing ? 'Edit Jadwal' : 'Tambah Jadwal'}</h5>
                                <button type="button" onClick={() => setModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>
                            <div className="p-6 space-y-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nama Jadwal <span className="text-red-500">*</span></label>
                                    <input type="text" value={formData.name} onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                        className="w-full px-4 py-2 border border-gray-200 rounded-lg" placeholder="e.g. Absen Masuk" required />
                                </div>
                                {!formData.disable_daily_reset && (
                                    <>
                                        <div className="grid grid-cols-3 gap-3">
                                            <div>
                                                <label className="block text-sm font-medium text-gray-600 mb-1">Mulai <span className="text-red-500">*</span></label>
                                                <input type="time" value={formData.start_time} onChange={(e) => setFormData({ ...formData, start_time: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-200 rounded-lg" required />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-600 mb-1">Tepat <span className="text-red-500">*</span></label>
                                                <input type="time" value={formData.scheduled_time} onChange={(e) => setFormData({ ...formData, scheduled_time: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-200 rounded-lg" required />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium text-gray-600 mb-1">Tutup <span className="text-red-500">*</span></label>
                                                <input type="time" value={formData.end_time} onChange={(e) => setFormData({ ...formData, end_time: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-200 rounded-lg" required />
                                            </div>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-600 mb-1">Toleransi Terlambat (menit)</label>
                                            <input type="number" value={formData.late_tolerance_minutes} onChange={(e) => setFormData({ ...formData, late_tolerance_minutes: e.target.value })}
                                                className="w-full px-4 py-2 border border-gray-200 rounded-lg" min="0" />
                                            <small className="text-gray-400">Berapa menit setelah waktu tepat masih tidak terlambat</small>
                                        </div>
                                    </>
                                )}
                                {formData.disable_daily_reset && (
                                    <div className="grid grid-cols-2 gap-3">
                                        <div>
                                            <label className="block text-sm font-medium text-gray-600 mb-1">Tanggal Mulai <span className="text-red-500">*</span></label>
                                            <input type="date" value={formData.no_reset_start_date} onChange={(e) => setFormData({ ...formData, no_reset_start_date: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg" required />
                                            <small className="text-gray-400">Absensi hadir penuh pada tanggal ini</small>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-gray-600 mb-1">Tanggal Selesai</label>
                                            <input type="date" value={formData.no_reset_end_date} onChange={(e) => setFormData({ ...formData, no_reset_end_date: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                            <small className="text-gray-400">Opsional. Jadwal otomatis nonaktif setelah tanggal ini</small>
                                        </div>
                                    </div>
                                )}
                                <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Status Jadwal</label>
                                        <small className="text-gray-400">{formData.is_active ? 'Jadwal aktif dan muncul di kiosk' : 'Jadwal non-aktif, tidak muncul di kiosk'}</small>
                                    </div>
                                    <button type="button" onClick={() => setFormData({ ...formData, is_active: !formData.is_active })}
                                        className={`relative w-12 h-6 rounded-full transition-colors duration-200 ${formData.is_active ? 'bg-emerald-500' : 'bg-gray-300'}`}>
                                        <span className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 ${formData.is_active ? 'translate-x-6' : ''}`}></span>
                                    </button>
                                </div>
                                <div className="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <label className="block text-sm font-medium text-gray-700">Nonaktifkan Reset Harian</label>
                                        <small className="text-gray-400">{formData.disable_daily_reset ? 'Absensi tidak reset per hari (sekali absen selamanya)' : 'Absensi reset setiap hari (default)'}</small>
                                    </div>
                                    <button type="button" onClick={() => setFormData({ ...formData, disable_daily_reset: !formData.disable_daily_reset })}
                                        className={`relative w-12 h-6 rounded-full transition-colors duration-200 ${formData.disable_daily_reset ? 'bg-amber-500' : 'bg-gray-300'}`}>
                                        <span className={`absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform duration-200 ${formData.disable_daily_reset ? 'translate-x-6' : ''}`}></span>
                                    </button>
                                </div>
                            </div>
                            <div className="px-6 py-4 border-t flex gap-3">
                                <button type="button" onClick={() => setModalOpen(false)} className="flex-1 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200">Batal</button>
                                <button type="submit" disabled={saving} className="flex-1 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:opacity-50">
                                    {saving ? 'Menyimpan...' : 'Simpan'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    );
}
