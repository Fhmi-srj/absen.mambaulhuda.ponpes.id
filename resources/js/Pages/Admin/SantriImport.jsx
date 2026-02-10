import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function AdminSantriImport() {
    const [isLoading, setIsLoading] = useState(true);
    const [isImporting, setIsImporting] = useState(false);
    const [columnDefinitions, setColumnDefinitions] = useState([]);
    const [importErrors, setImportErrors] = useState([]);
    const [file, setFile] = useState(null);

    useEffect(() => {
        document.title = 'Import Santri - Admin';
        fetchConfig();
    }, []);

    const fetchConfig = async () => {
        setIsLoading(true);
        try {
            const response = await fetch('/admin/santri-import', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setColumnDefinitions(data.columnDefinitions);
        } catch (error) {
            console.error('Error fetching import config:', error);
            Swal.fire('Error', 'Gagal mengambil konfigurasi import', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleFileChange = (e) => {
        setFile(e.target.files[0]);
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        if (!file) {
            Swal.fire('Peringatan', 'Pilih file Excel terlebih dahulu', 'warning');
            return;
        }

        setIsImporting(true);
        setImportErrors([]);

        const formData = new FormData();
        formData.append('file_santri', file);

        try {
            const response = await fetch('/admin/santri-import/import', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: formData
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Berhasil', result.message || 'Import data selesai', 'success');
                setFile(null);
                e.target.reset();
            } else {
                if (result.errors) {
                    setImportErrors(result.errors);
                    Swal.fire('Gagal', 'Terdapat kesalahan pada data Excel. Cek detail di bawah.', 'error');
                } else {
                    Swal.fire('Gagal', result.message || 'Terjadi kesalahan saat import', 'error');
                }
            }
        } catch (error) {
            console.error('Error importing data:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
            setIsImporting(false);
        }
    };

    const getColumnLetter = (index) => {
        const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if (index < 26) return letters[index];
        return "A" + letters[index - 26];
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
        );
    }

    return (
        <div className="pb-24">
            <div className="flex items-center gap-3 mb-8">
                <div className="bg-emerald-500 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                    <i className="fas fa-file-import text-xl"></i>
                </div>
                <div>
                    <h4 className="font-black text-gray-800 mb-0">Import Santri</h4>
                    <p className="text-sm text-gray-500 font-medium">Bulk update & insert data santri via Excel</p>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {/* Upload Section */}
                <div className="lg:col-span-5 space-y-6">
                    <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                        <h6 className="font-black text-gray-700 mb-6 flex items-center gap-2">
                            <i className="fas fa-upload text-blue-500"></i>
                            Upload File
                        </h6>

                        {importErrors.length > 0 && (
                            <div className="bg-red-50 border border-red-100 rounded-2xl p-4 mb-6">
                                <div className="text-red-700 font-bold text-xs flex items-center gap-2 mb-2 uppercase tracking-widest">
                                    <i className="fas fa-exclamation-triangle"></i>
                                    Kesalahan Validasi
                                </div>
                                <div className="max-h-40 overflow-y-auto space-y-1.5 pr-2 custom-scrollbar">
                                    {importErrors.slice(0, 50).map((err, i) => (
                                        <div key={i} className="text-[10px] text-red-600 bg-white/50 px-2 py-1.5 rounded-lg border border-red-50/50">
                                            {err}
                                        </div>
                                    ))}
                                    {importErrors.length > 50 && (
                                        <div className="text-[10px] text-red-500 italic text-center pt-1">...dan {importErrors.length - 50} error lainnya</div>
                                    )}
                                </div>
                            </div>
                        )}

                        <form onSubmit={handleSubmit} className="space-y-4">
                            <div className="relative group">
                                <input
                                    type="file"
                                    className="hidden"
                                    id="excel-file"
                                    accept=".xlsx,.xls"
                                    onChange={handleFileChange}
                                />
                                <label
                                    htmlFor="excel-file"
                                    className={`flex flex-col items-center justify-center w-full h-40 border-2 border-dashed rounded-3xl cursor-pointer transition-all ${file ? 'border-emerald-200 bg-emerald-50/30' : 'border-gray-200 bg-gray-50/50 hover:bg-gray-50 hover:border-blue-200'
                                        }`}
                                >
                                    <i className={`fas ${file ? 'fa-file-excel text-emerald-500' : 'fa-cloud-upload-alt text-gray-300'} text-4xl mb-3`}></i>
                                    <span className="text-xs font-bold text-gray-500 text-center px-4 leading-relaxed">
                                        {file ? file.name : 'Klik untuk pilih file Excel (.xlsx)'}
                                    </span>
                                </label>
                            </div>

                            <button
                                type="submit"
                                disabled={isImporting || !file}
                                className="w-full py-4 bg-gradient-to-r from-blue-600 to-blue-500 text-white rounded-2xl font-black shadow-lg shadow-blue-100 disabled:opacity-50 hover:shadow-xl active:scale-95 transition-all"
                            >
                                {isImporting ? (
                                    <><i className="fas fa-spinner fa-spin mr-2"></i>Memporses Data...</>
                                ) : (
                                    <><i className="fas fa-check-circle mr-2"></i>Mulai Import</>
                                )}
                            </button>
                        </form>
                    </div>

                    <div className="bg-gradient-to-br from-indigo-50 to-white rounded-3xl p-6 border border-indigo-100">
                        <h6 className="font-bold text-indigo-700 mb-4 flex items-center gap-2">
                            <i className="fas fa-lightbulb"></i>
                            Petunjuk Import
                        </h6>
                        <ul className="space-y-3">
                            {[
                                "Baris pertama harus berisi header kolom",
                                "Data dimulai dari baris kedua",
                                "Kolom merah bertanda 'WAJIB' tidak boleh kosong",
                                "Sistem akan sinkronisasi data berdasarkan NISN",
                                "Jika NISN kosong, sinkronisasi via Nama + Kelas"
                            ].map((text, i) => (
                                <li key={i} className="flex gap-2 text-xs font-medium text-slate-600 leading-normal">
                                    <div className="w-4 h-4 rounded-full bg-indigo-500 text-white flex-shrink-0 flex items-center justify-center text-[8px] mt-0.5">
                                        {i + 1}
                                    </div>
                                    {text}
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>

                {/* Column Definition Section */}
                <div className="lg:col-span-7">
                    <div className="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                        <div className="px-8 py-5 bg-gray-50/50 border-b border-gray-100 flex items-center justify-between">
                            <h6 className="font-black text-gray-700 mb-0 uppercase tracking-widest text-xs">Struktur Kolom Excel</h6>
                            <span className="text-[10px] font-bold text-gray-400">Total: {columnDefinitions.length} Kolom</span>
                        </div>
                        <div className="overflow-x-auto max-h-[600px] custom-scrollbar">
                            <table className="w-full text-left">
                                <thead className="bg-white sticky top-0 z-10 border-b border-gray-100">
                                    <tr>
                                        <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Kolom</th>
                                        <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest">Field & Nama</th>
                                        <th className="px-6 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-50">
                                    {columnDefinitions.map((col, idx) => (
                                        <tr key={idx} className="hover:bg-gray-50/50 transition-colors">
                                            <td className="px-6 py-4 text-center">
                                                <div className="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center font-black text-gray-500 text-sm">
                                                    {getColumnLetter(idx)}
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <div className="font-bold text-gray-700 leading-tight mb-0.5">{col.name}</div>
                                                <div className="text-[10px] text-gray-400 font-medium">{col.label}</div>
                                                {col.note && <div className="text-[9px] text-blue-500 font-bold mt-1 opacity-70 italic">*{col.note}</div>}
                                            </td>
                                            <td className="px-6 py-4 text-center">
                                                <span className={`px-2 py-1 rounded text-[9px] font-black tracking-widest uppercase ${col.required ? 'bg-red-100 text-red-600' : 'bg-slate-100 text-slate-500'
                                                    }`}>
                                                    {col.required ? 'Wajib' : 'Opsional'}
                                                </span>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
