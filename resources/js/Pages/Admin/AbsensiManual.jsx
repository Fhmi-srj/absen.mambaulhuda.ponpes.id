import React, { useState, useEffect } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import Swal from 'sweetalert2';

export default function AdminAbsensiManual() {
    const { user } = useAuth();
    const [isLoading, setIsLoading] = useState(true);
    const [isSubmitting, setIsSubmitting] = useState(false);
    const [siswaList, setSiswaList] = useState([]);
    const [jadwalList, setJadwalList] = useState([]);
    const [recentAttendances, setRecentAttendances] = useState([]);

    const [formData, setFormData] = useState({
        siswa_id: '',
        jadwal_id: '',
        attendance_date: new Date().toISOString().split('T')[0],
        attendance_time: new Date().toLocaleTimeString('id-ID', { hour12: false, hour: '2-digit', minute: '2-digit' }).replace('.', ':'),
        status: 'hadir',
        notes: ''
    });

    useEffect(() => {
        document.title = 'Absensi Manual - Admin';
        fetchData();
    }, []);

    const fetchData = async () => {
        setIsLoading(true);
        try {
            const response = await fetch('/admin/absensi-manual', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setSiswaList(data.siswaList);
            setJadwalList(data.jadwalList);
            setRecentAttendances(data.recentAttendances);
        } catch (error) {
            console.error('Error fetching data:', error);
            Swal.fire('Error', 'Gagal mengambil data', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setFormData(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSubmitting(true);

        try {
            const response = await fetch('/admin/absensi-manual', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify(formData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Berhasil', result.message || 'Data absensi berhasil disimpan', 'success');
                setFormData(prev => ({
                    ...prev,
                    siswa_id: '',
                    notes: ''
                }));
                fetchData();
            } else {
                Swal.fire('Gagal', result.message || 'Terjadi kesalahan saat menyimpan data', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
            setIsSubmitting(false);
        }
    };

    const handleDelete = async (id, nama) => {
        const result = await Swal.fire({
            title: 'Hapus Data Absensi?',
            html: `Data absensi <strong>${nama}</strong> akan dihapus ke trash.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            confirmButtonText: '<i class="fas fa-trash me-1"></i> Ya, Hapus',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`/admin/absensi-manual/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    }
                });

                if (response.ok) {
                    Swal.fire('Dihapus', 'Data berhasil dipindahkan ke trash', 'success');
                    fetchData();
                } else {
                    Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus data', 'error');
                }
            } catch (error) {
                console.error('Error deleting data:', error);
                Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
            }
        }
    };

    if (isLoading && !recentAttendances.length) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
        );
    }

    return (
        <div className="pb-24">
            <div className="flex items-center gap-3 mb-6">
                <div className="bg-blue-500 w-10 h-10 rounded-xl flex items-center justify-center text-white shadow-lg">
                    <i className="fas fa-edit"></i>
                </div>
                <div>
                    <h5 className="font-bold text-gray-800 mb-0">Absensi Manual</h5>
                    <p className="text-xs text-gray-500">Input data kehadiran santri secara manual</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                {/* Form Column */}
                <div className="lg:col-span-5">
                    <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                        <h6 className="font-bold text-gray-700 mb-6 flex items-center gap-2">
                            <i className="fas fa-keyboard text-blue-500"></i>
                            Input Absensi
                        </h6>

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Siswa <span className="text-red-500">*</span></label>
                                <select
                                    name="siswa_id"
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                    value={formData.siswa_id}
                                    onChange={handleInputChange}
                                    required
                                >
                                    <option value="">-- Pilih Siswa --</option>
                                    {siswaList.map(s => (
                                        <option key={s.id} value={s.id}>{s.nama_lengkap} ({s.kelas})</option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Jadwal <span className="text-red-500">*</span></label>
                                <select
                                    name="jadwal_id"
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                    value={formData.jadwal_id}
                                    onChange={handleInputChange}
                                    required
                                >
                                    <option value="">-- Pilih Jadwal --</option>
                                    {jadwalList.map(j => (
                                        <option key={j.id} value={j.id}>{j.name}</option>
                                    ))}
                                </select>
                            </div>

                            <div className="grid grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">Tanggal <span className="text-red-500">*</span></label>
                                    <input
                                        type="date"
                                        name="attendance_date"
                                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                        value={formData.attendance_date}
                                        onChange={handleInputChange}
                                        required
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-bold text-gray-700 mb-2">Waktu <span className="text-red-500">*</span></label>
                                    <input
                                        type="time"
                                        name="attendance_time"
                                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                        value={formData.attendance_time}
                                        onChange={handleInputChange}
                                        required
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Status <span className="text-red-500">*</span></label>
                                <select
                                    name="status"
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                    value={formData.status}
                                    onChange={handleInputChange}
                                    required
                                >
                                    <option value="hadir">Hadir</option>
                                    <option value="terlambat">Terlambat</option>
                                    <option value="izin">Izin</option>
                                    <option value="sakit">Sakit</option>
                                    <option value="absen">Absen/Alpha</option>
                                    <option value="pulang">Pulang</option>
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-gray-700 mb-2">Catatan</label>
                                <textarea
                                    name="notes"
                                    className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-colors"
                                    rows="3"
                                    placeholder="Keterangan tambahan (opsional)..."
                                    value={formData.notes}
                                    onChange={handleInputChange}
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                disabled={isSubmitting}
                                className="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-blue-200 hover:shadow-blue-300 active:scale-95 transition-all disabled:opacity-50"
                            >
                                {isSubmitting ? (
                                    <><i className="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</>
                                ) : (
                                    <><i className="fas fa-save mr-2"></i>Simpan Absensi</>
                                )}
                            </button>
                        </form>
                    </div>
                </div>

                {/* List Column */}
                <div className="lg:col-span-7">
                    <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div className="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                            <h6 className="font-bold text-gray-700 mb-0 flex items-center gap-2">
                                <i className="fas fa-history text-blue-500"></i>
                                Data Terbaru
                            </h6>
                        </div>

                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead className="bg-gray-50/50">
                                    <tr>
                                        <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Waktu</th>
                                        <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Santri</th>
                                        <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Jadwal</th>
                                        <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Status</th>
                                        <th className="px-6 py-4 text-xs font-black text-gray-400 uppercase tracking-widest">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {recentAttendances.length === 0 ? (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-8 text-center text-gray-400">Belum ada data absensi terbaru</td>
                                        </tr>
                                    ) : (
                                        recentAttendances.map(a => (
                                            <tr key={a.id} className="hover:bg-gray-50/50 transition-colors">
                                                <td className="px-6 py-4">
                                                    <div className="font-bold text-gray-700 whitespace-nowrap">
                                                        {new Date(a.attendance_date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                                                    </div>
                                                    <div className="text-xs text-gray-400 font-medium">
                                                        {a.attendance_time.substring(0, 5)}
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <div className="font-bold text-gray-700">{a.nama_lengkap}</div>
                                                    <div className="text-xs text-gray-500">{a.kelas}</div>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <span className="text-sm font-medium text-gray-600 truncate max-w-[120px] block">
                                                        {a.jadwal_name || '-'}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <span className={`px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider ${a.status === 'hadir' ? 'bg-green-100 text-green-600' :
                                                            a.status === 'terlambat' ? 'bg-amber-100 text-amber-600' :
                                                                a.status === 'absen' ? 'bg-red-100 text-red-600' :
                                                                    'bg-blue-100 text-blue-600'
                                                        }`}>
                                                        {a.status}
                                                    </span>
                                                </td>
                                                <td className="px-6 py-4">
                                                    <button
                                                        onClick={() => handleDelete(a.id, a.nama_lengkap)}
                                                        className="w-10 h-10 flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all"
                                                    >
                                                        <i className="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
