import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function TahunAjaranManager() {
    const [tahunAjarans, setTahunAjarans] = useState([]);
    const [existingClasses, setExistingClasses] = useState([]);
    const [newTahun, setNewTahun] = useState('');
    const [isLoading, setIsLoading] = useState(false);

    const [showModal, setShowModal] = useState(false);
    const [targetTahun, setTargetTahun] = useState(null);
    const [mappings, setMappings] = useState({});

    useEffect(() => {
        fetchTahunAjarans();
        fetchClasses();
    }, []);

    const fetchTahunAjarans = async () => {
        try {
            const res = await fetch('/api/admin/tahun-ajaran');
            const data = await res.json();
            setTahunAjarans(data);
        } catch (e) {
            console.error(e);
        }
    };

    const fetchClasses = async () => {
        try {
            // Kita bisa menggunakan fetch santri endpoint yang sudah ada untuk mendapat kelasList
            const res = await fetch('/admin/santri', {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();
            setExistingClasses(data.kelasList || []);
        } catch (e) {
            console.error(e);
        }
    };

    const handleAdd = async (e) => {
        e.preventDefault();
        if (!newTahun) return;

        setIsLoading(true);
        try {
            const res = await fetch('/api/admin/tahun-ajaran', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ nama: newTahun })
            });

            if (res.ok) {
                setNewTahun('');
                fetchTahunAjarans();
                Swal.fire('Berhasil', 'Tahun ajaran ditambahkan', 'success');
            } else {
                const data = await res.json();
                Swal.fire('Gagal', data.message || 'Nama tahun ajaran mungkin sudah ada.', 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleDelete = async (id) => {
        const { isConfirmed } = await Swal.fire({
            title: 'Hapus Tahun Ajaran?',
            text: 'Data yang dihapus tidak bisa dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus'
        });

        if (isConfirmed) {
            try {
                const res = await fetch(`/api/admin/tahun-ajaran/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    fetchTahunAjarans();
                    Swal.fire('Dihapus!', '', 'success');
                } else {
                    const data = await res.json();
                    Swal.fire('Gagal', data.message || 'Gagal menghapus', 'error');
                }
            } catch (e) {
                console.error(e);
            }
        }
    };

    const confirmActivate = (tahun) => {
        setTargetTahun(tahun);
        const defaultMaps = {};
        existingClasses.forEach(c => {
            defaultMaps[c] = c; // default stay in same class
        });
        setMappings(defaultMaps);
        setShowModal(true);
    };

    const handleActivate = async () => {
        setIsLoading(true);
        try {
            const res = await fetch(`/api/admin/tahun-ajaran/${targetTahun.id}/activate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ mappings })
            });

            const data = await res.json();
            if (res.ok) {
                setShowModal(false);
                fetchTahunAjarans();
                Swal.fire('Berhasil', data.message, 'success');
            } else {
                Swal.fire('Gagal', data.message, 'error');
            }
        } catch (e) {
            Swal.fire('Error', 'Terjadi kesalahan', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 mb-8">
            <div className="flex items-center gap-3 mb-6">
                <div className="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-indigo-500">
                    <i className="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <h6 className="font-black text-gray-700 mb-0 uppercase tracking-wider text-sm">Manajemen Tahun Ajaran</h6>
                    <p className="text-xs text-gray-400">Atur tahun ajaran aktif dan kelola kenaikan kelas santri</p>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <form onSubmit={handleAdd} className="flex gap-2">
                        <input
                            type="text"
                            placeholder="Contoh: 2025/2026"
                            className="flex-1 bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 text-sm focus:border-indigo-500 font-medium"
                            value={newTahun}
                            onChange={(e) => setNewTahun(e.target.value)}
                            required
                        />
                        <button
                            type="submit"
                            disabled={isLoading}
                            className="px-6 py-3 bg-indigo-600 text-white rounded-xl text-sm font-bold hover:bg-indigo-700 transition"
                        >
                            Tambah
                        </button>
                    </form>
                </div>
                
                <div className="bg-gray-50 rounded-xl p-4 border border-gray-100 max-h-[300px] overflow-y-auto">
                    <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Daftar Tahun Ajaran</h4>
                    {tahunAjarans.map(t => (
                        <div key={t.id} className={`flex items-center justify-between p-3 rounded-lg mb-2 ${t.is_active ? 'bg-indigo-50 border border-indigo-100' : 'bg-white border border-gray-100'}`}>
                            <div>
                                <h6 className="font-bold text-sm mb-0">{t.nama}</h6>
                                {t.is_active && <span className="text-[10px] font-black text-indigo-600 uppercase bg-indigo-100 px-2 py-0.5 rounded-full inline-block mt-1">Sts Aktif</span>}
                            </div>
                            <div className="flex gap-2">
                                {!t.is_active && (
                                    <>
                                        <button onClick={() => confirmActivate(t)} className="text-xs px-3 py-1.5 bg-emerald-100 text-emerald-700 font-bold rounded-lg hover:bg-emerald-200">
                                            Aktifkan
                                        </button>
                                        <button onClick={() => handleDelete(t.id)} className="text-xs px-3 py-1.5 bg-rose-100 text-rose-700 font-bold rounded-lg hover:bg-rose-200">
                                            Hapus
                                        </button>
                                    </>
                                )}
                            </div>
                        </div>
                    ))}
                    {tahunAjarans.length === 0 && <p className="text-center text-xs text-gray-400">Belum ada data.</p>}
                </div>
            </div>

            {/* Modal Mapping */}
            {showModal && (
                <div className="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">
                    <div className="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl relative max-h-[90vh] flex flex-col">
                        <button onClick={() => setShowModal(false)} className="absolute top-4 right-4 text-gray-400 hover:text-gray-700">
                            <i className="fas fa-times text-xl"></i>
                        </button>
                        
                        <h3 className="font-black text-xl mb-1 text-gray-800">Pemetaan Kelas Naik</h3>
                        <p className="text-sm text-gray-500 mb-6 font-medium">Tentukan kelas tujuan untuk santri yang akan "naik kelas" dari tahun ajaran saat ini ke <b>{targetTahun?.nama}</b>. Isi dengan kata "Lulus", "Hapus", atau "Keluar" jika santri pada kelas tersebut sudah selesai studi (tidak disalin ke tahun baru).</p>

                        <div className="overflow-y-auto pr-2 flex-1 mb-6">
                            {existingClasses.map(c => (
                                <div key={c} className="flex items-center gap-4 mb-3">
                                    <div className="w-1/3 text-right text-sm font-bold text-gray-700 bg-gray-50 rounded-lg py-2 px-3">
                                        Kelas {c}
                                    </div>
                                    <div className="text-gray-400"><i className="fas fa-arrow-right"></i></div>
                                    <div className="flex-1">
                                        <input
                                            type="text"
                                            value={mappings[c] || ''}
                                            onChange={(e) => setMappings({ ...mappings, [c]: e.target.value })}
                                            className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-indigo-500 font-medium"
                                            placeholder="Contoh: Naik ke V, atau 'Lulus'"
                                        />
                                    </div>
                                </div>
                            ))}
                            {existingClasses.length === 0 && (
                                <p className="text-center text-gray-500 text-sm">Tidak ada kelas yang ditemukan di data saat ini.</p>
                            )}
                        </div>

                        <div className="flex justify-end gap-3 mt-auto pt-4 border-t border-gray-100">
                            <button onClick={() => setShowModal(false)} className="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold hover:bg-gray-200">
                                Batal
                            </button>
                            <button onClick={handleActivate} disabled={isLoading} className="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 flex items-center">
                                {isLoading ? <><i className="fas fa-spinner fa-spin mr-2"></i> Proses...</> : 'Jalankan & Aktifkan'}
                            </button>
                        </div>
                    </div>
                </div>
            )}
        </div>
    );
}
