import React, { useState, useEffect } from 'react';
import { PageSkeleton } from '../../Components/Skeleton';
import Swal from 'sweetalert2';

export default function Santri() {
    const [loading, setLoading] = useState(true);
    const [santriList, setSantriList] = useState([]);
    const [total, setTotal] = useState(0);
    const [kelasList, setKelasList] = useState([]);
    const [pagination, setPagination] = useState({ current_page: 1, last_page: 1 });

    // Filters
    const [search, setSearch] = useState('');
    const [filterStatus, setFilterStatus] = useState('');
    const [filterKelas, setFilterKelas] = useState('');
    const [sortCol, setSortCol] = useState('nama_lengkap');
    const [sortDir, setSortDir] = useState('ASC');
    const [currentPage, setCurrentPage] = useState(1);

    // Selection
    const [selectedIds, setSelectedIds] = useState([]);

    // Modal
    const [modalOpen, setModalOpen] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [activeTab, setActiveTab] = useState('santri');

    // Form data
    const [formData, setFormData] = useState({
        id: '',
        nama_lengkap: '', nisn: '', nik: '', nomor_kk: '',
        tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', jumlah_saudara: '',
        lembaga_sekolah: '', kelas: '', quran: '', kategori: '',
        status: 'AKTIF', asal_sekolah: '', status_mukim: '',
        alamat: '', kecamatan: '', kabupaten: '', no_wa_wali: '', nomor_rfid: '', nomor_pip: '',
        nama_ayah: '', nik_ayah: '', tempat_lahir_ayah: '', tanggal_lahir_ayah: '', pekerjaan_ayah: '', penghasilan_ayah: '',
        nama_ibu: '', nik_ibu: '', tempat_lahir_ibu: '', tanggal_lahir_ibu: '', pekerjaan_ibu: '', penghasilan_ibu: '',
        sumber_info: '', prestasi: '', tingkat_prestasi: '', juara_prestasi: '',
    });

    useEffect(() => {
        document.title = 'Data Induk Santri - Aktivitas Santri';
        fetchSantri();
    }, [currentPage, sortCol, sortDir]);

    const fetchSantri = async () => {
        try {
            const params = new URLSearchParams({
                page: currentPage,
                sort: sortCol,
                dir: sortDir,
                search,
                status: filterStatus,
                kelas: filterKelas,
            });

            const response = await fetch(`/admin/santri?${params}`, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const data = await response.json();
                setSantriList(data.santriList?.data || []);
                setTotal(data.total || 0);
                setKelasList(data.kelasList || []);
                setPagination({
                    current_page: data.santriList?.current_page || 1,
                    last_page: data.santriList?.last_page || 1,
                });
            }
        } catch (error) {
            console.error('Error fetching santri:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleFilter = (e) => {
        e.preventDefault();
        setCurrentPage(1);
        fetchSantri();
    };

    const handleSort = (col) => {
        if (sortCol === col) {
            setSortDir(sortDir === 'ASC' ? 'DESC' : 'ASC');
        } else {
            setSortCol(col);
            setSortDir('ASC');
        }
    };

    const resetForm = () => {
        setFormData({
            id: '', nama_lengkap: '', nisn: '', nik: '', nomor_kk: '',
            tempat_lahir: '', tanggal_lahir: '', jenis_kelamin: '', jumlah_saudara: '',
            lembaga_sekolah: '', kelas: '', quran: '', kategori: '',
            status: 'AKTIF', asal_sekolah: '', status_mukim: '',
            alamat: '', kecamatan: '', kabupaten: '', no_wa_wali: '', nomor_rfid: '', nomor_pip: '',
            nama_ayah: '', nik_ayah: '', tempat_lahir_ayah: '', tanggal_lahir_ayah: '', pekerjaan_ayah: '', penghasilan_ayah: '',
            nama_ibu: '', nik_ibu: '', tempat_lahir_ibu: '', tanggal_lahir_ibu: '', pekerjaan_ibu: '', penghasilan_ibu: '',
            sumber_info: '', prestasi: '', tingkat_prestasi: '', juara_prestasi: '',
        });
        setIsEditing(false);
        setActiveTab('santri');
    };

    const toggleSelectAll = () => {
        if (selectedIds.length === santriList.length) {
            setSelectedIds([]);
        } else {
            setSelectedIds(santriList.map(s => s.id));
        }
    };

    const toggleSelect = (id) => {
        setSelectedIds(prev =>
            prev.includes(id) ? prev.filter(i => i !== id) : [...prev, id]
        );
    };

    const handleCetakKartu = () => {
        if (selectedIds.length === 0) return;
        window.open(`/cetak-kartu?ids=${selectedIds.join(',')}`, '_blank');
    };

    const openAddModal = () => {
        resetForm();
        setModalOpen(true);
        // Reset file inputs after render
        setTimeout(() => {
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => input.value = '');
        }, 0);
    };

    const openEditModal = async (id) => {
        try {
            const response = await fetch(`/api/admin/santri/${id}`, {
                credentials: 'include',
                headers: { 'Accept': 'application/json' },
            });
            const data = await response.json();
            if (data.success) {
                setFormData({
                    id: data.data.id || '',
                    nama_lengkap: data.data.nama_lengkap || '',
                    nisn: data.data.nisn || '',
                    nik: data.data.nik || '',
                    nomor_kk: data.data.nomor_kk || '',
                    tempat_lahir: data.data.tempat_lahir || '',
                    tanggal_lahir: data.data.tanggal_lahir || '',
                    jenis_kelamin: data.data.jenis_kelamin || '',
                    jumlah_saudara: data.data.jumlah_saudara || '',
                    lembaga_sekolah: data.data.lembaga_sekolah || '',
                    kelas: data.data.kelas || '',
                    quran: data.data.quran || '',
                    kategori: data.data.kategori || '',
                    status: data.data.status || 'AKTIF',
                    asal_sekolah: data.data.asal_sekolah || '',
                    status_mukim: data.data.status_mukim || '',
                    alamat: data.data.alamat || '',
                    kecamatan: data.data.kecamatan || '',
                    kabupaten: data.data.kabupaten || '',
                    no_wa_wali: data.data.no_wa_wali || '',
                    nomor_rfid: data.data.nomor_rfid || '',
                    nomor_pip: data.data.nomor_pip || '',
                    nama_ayah: data.data.nama_ayah || '',
                    nik_ayah: data.data.nik_ayah || '',
                    tempat_lahir_ayah: data.data.tempat_lahir_ayah || '',
                    tanggal_lahir_ayah: data.data.tanggal_lahir_ayah || '',
                    pekerjaan_ayah: data.data.pekerjaan_ayah || '',
                    penghasilan_ayah: data.data.penghasilan_ayah || '',
                    nama_ibu: data.data.nama_ibu || '',
                    nik_ibu: data.data.nik_ibu || '',
                    tempat_lahir_ibu: data.data.tempat_lahir_ibu || '',
                    tanggal_lahir_ibu: data.data.tanggal_lahir_ibu || '',
                    pekerjaan_ibu: data.data.pekerjaan_ibu || '',
                    penghasilan_ibu: data.data.penghasilan_ibu || '',
                    sumber_info: data.data.sumber_info || '',
                    prestasi: data.data.prestasi || '',
                    tingkat_prestasi: data.data.tingkat_prestasi || '',
                    juara_prestasi: data.data.juara_prestasi || '',
                });
                setIsEditing(true);
                setModalOpen(true);
                // Reset file inputs
                setTimeout(() => {
                    const fileInputs = document.querySelectorAll('input[type="file"]');
                    fileInputs.forEach(input => input.value = '');
                }, 0);
            }
        } catch (error) {
            console.error('Error fetching santri:', error);
        }
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage({ type: '', text: '' });

        try {
            const form = new FormData(e.currentTarget);

            // Files are already in FormData because they have name attributes
            // We only need to ensure the ID is correct for updates
            const url = isEditing
                ? `/api/admin/santri/${formData.id}`
                : '/api/admin/santri';

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
                setMessage({ type: 'success', text: data.message || 'Berhasil disimpan' });
                setModalOpen(false);
                fetchSantri();
            } else {
                setMessage({ type: 'error', text: data.message || 'Gagal menyimpan' });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        } finally {
            setSaving(false);
        }
    };

    const handleDelete = async (id, name) => {
        const result = await Swal.fire({
            title: 'Hapus Santri?',
            text: `Yakin ingin menghapus santri "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/api/admin/santri/${id}`, {
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
                fetchSantri();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        }
    };

    const updateField = (field, value) => {
        setFormData(prev => ({ ...prev, [field]: value }));
    };

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>
            {/* Header */}
            <div className="flex flex-wrap justify-between items-center gap-4 mb-4">
                <div>

                    <p className="text-sm text-gray-500">Total: {total} santri</p>
                </div>
                <button
                    onClick={openAddModal}
                    className="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors"
                >
                    <i className="fas fa-plus mr-2"></i>Tambah Santri
                </button>
            </div>

            {/* Message */}
            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                    <button onClick={() => setMessage({ type: '', text: '' })} className="float-right font-bold">&times;</button>
                </div>
            )}

            {/* Filter */}
            <div className="bg-white rounded-xl shadow-sm p-4 mb-4">
                <form onSubmit={handleFilter} className="flex flex-wrap gap-3 items-end">
                    <div className="flex-1 min-w-[200px]">
                        <label className="block text-xs text-gray-500 mb-1">Cari</label>
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Nama/NISN/NIK/WA..."
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                        />
                    </div>
                    <div className="w-32">
                        <label className="block text-xs text-gray-500 mb-1">Status</label>
                        <select
                            value={filterStatus}
                            onChange={(e) => setFilterStatus(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                        >
                            <option value="">Semua</option>
                            <option value="AKTIF">Aktif</option>
                            <option value="NON-AKTIF">Non-Aktif</option>
                            <option value="LULUS">Lulus</option>
                        </select>
                    </div>
                    <div className="w-32">
                        <label className="block text-xs text-gray-500 mb-1">Kelas</label>
                        <select
                            value={filterKelas}
                            onChange={(e) => setFilterKelas(e.target.value)}
                            className="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm"
                        >
                            <option value="">Semua</option>
                            {kelasList.map((k) => (
                                <option key={k} value={k}>{k}</option>
                            ))}
                        </select>
                    </div>
                    <div className="flex gap-2">
                        <button type="submit" className="px-4 py-2 bg-blue-500 text-white rounded-lg text-sm font-semibold">
                            <i className="fas fa-filter mr-1"></i>Filter
                        </button>
                        {selectedIds.length > 0 && (
                            <button
                                type="button"
                                onClick={handleCetakKartu}
                                className="px-4 py-2 bg-green-500 text-white rounded-lg text-sm font-semibold hover:bg-green-600 transition-colors"
                            >
                                <i className="fas fa-id-card mr-1"></i>Cetak ({selectedIds.length})
                            </button>
                        )}
                    </div>
                </form>
            </div>

            {/* Table */}
            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-[11px] border-collapse" style={{ minWidth: '5000px' }}>
                        <thead className="bg-[#3b5998] text-white">
                            <tr>
                                <th className="px-2 py-3 text-center w-10 sticky left-0 bg-[#3b5998] z-20 border border-[#2d4373]">
                                    <input
                                        type="checkbox"
                                        checked={selectedIds.length === santriList.length && santriList.length > 0}
                                        onChange={toggleSelectAll}
                                        className="rounded border-none ring-0 focus:ring-0"
                                    />
                                </th>
                                <th className="px-2 py-3 text-center w-12 border border-[#2d4373]">NO</th>
                                <th className="px-3 py-3 text-left cursor-pointer border border-[#2d4373]" onClick={() => handleSort('nama_lengkap')}>
                                    NAMA {sortCol === 'nama_lengkap' && (sortDir === 'ASC' ? '↑' : '↓')}
                                </th>
                                <th className="px-3 py-3 text-left cursor-pointer border border-[#2d4373]" onClick={() => handleSort('kelas')}>
                                    KELAS {sortCol === 'kelas' && (sortDir === 'ASC' ? '↑' : '↓')}
                                </th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">QURAN</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">KATEGORI</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NISN</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NIK</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NO KK</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">SEKOLAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">STATUS</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">TTL</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">JK</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">JML SAUDARA</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">ALAMAT</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">KECAMATAN</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">KABUPATEN</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">ASAL SEKOLAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">STATUS MUKIM</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NAMA AYAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NIK AYAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">TTL AYAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">PEKERJAAN AYAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">PENGHASILAN AYAH</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NAMA IBU</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NIK IBU</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">TTL IBU</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">PEKERJAAN IBU</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">PENGHASILAN IBU</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NO WA</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">RFID</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">NO PIP</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">SUMBER INFO</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">PRESTASI</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">TINGKAT</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">JUARA</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">FOTO</th>
                                <th className="px-3 py-3 text-left border border-[#2d4373]">DOKUMEN</th>
                                <th className="px-3 py-3 text-center w-24 sticky right-0 bg-[#3b5998] z-20 border border-[#2d4373]">AKSI</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {santriList.length === 0 ? (
                                <tr>
                                    <td colSpan="42" className="px-3 py-8 text-center text-gray-400">
                                        Tidak ada data
                                    </td>
                                </tr>
                            ) : (
                                santriList.map((s, i) => (
                                    <tr key={s.id} className="hover:bg-gray-50 border-b border-gray-100">
                                        <td className="px-2 py-2 text-center sticky left-0 bg-white z-10 border-x border-gray-100">
                                            <input
                                                type="checkbox"
                                                checked={selectedIds.includes(s.id)}
                                                onChange={() => toggleSelect(s.id)}
                                                className="rounded border-gray-300 pointer-events-auto"
                                            />
                                        </td>
                                        <td className="px-2 py-2 text-center border-r border-gray-100">{(pagination.current_page - 1) * 20 + i + 1}</td>
                                        <td className="px-3 py-2 font-bold border-r border-gray-100">{s.nama_lengkap}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.kelas || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.quran || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            <span className="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-[10px] font-bold uppercase">
                                                {s.kategori || '-'}
                                            </span>
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100"><code className="text-red-500 font-mono">{s.nisn || '-'}</code></td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nik || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nomor_kk || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.lembaga_sekolah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            <span className={`px-2 py-0.5 text-[10px] font-semibold rounded ${s.status === 'AKTIF' ? 'bg-green-100 text-green-600' :
                                                s.status === 'LULUS' ? 'bg-blue-100 text-blue-600' :
                                                    'bg-gray-100 text-gray-600'
                                                }`}>
                                                {s.status || '-'}
                                            </span>
                                        </td>
                                        <td className="px-3 py-2 whitespace-nowrap border-r border-gray-100">
                                            {s.tempat_lahir || '-'}{s.tanggal_lahir ? `, ${new Date(s.tanggal_lahir).toLocaleDateString('id-ID')}` : ''}
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.jenis_kelamin || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.jumlah_saudara || '-'}</td>
                                        <td className="px-3 py-2 max-w-[200px] truncate border-r border-gray-100" title={s.alamat}>{s.alamat || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.kecamatan || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.kabupaten || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.asal_sekolah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.status_mukim || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nama_ayah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nik_ayah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            {s.tempat_lahir_ayah || '-'}{s.tanggal_lahir_ayah ? `, ${new Date(s.tanggal_lahir_ayah).toLocaleDateString('id-ID')}` : ''}
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.pekerjaan_ayah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.penghasilan_ayah || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nama_ibu || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nik_ibu || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            {s.tempat_lahir_ibu || '-'}{s.tanggal_lahir_ibu ? `, ${new Date(s.tanggal_lahir_ibu).toLocaleDateString('id-ID')}` : ''}
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.pekerjaan_ibu || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.penghasilan_ibu || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.no_wa_wali || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            {s.nomor_rfid ? (
                                                <span className="px-2 py-0.5 text-[10px] bg-green-100 text-green-600 rounded">Ada</span>
                                            ) : '-'}
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.nomor_pip || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.sumber_info || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.prestasi || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.tingkat_prestasi || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">{s.juara_prestasi || '-'}</td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            {s.foto_santri ? (
                                                <span className="px-2 py-0.5 text-[10px] bg-green-100 text-green-600 rounded">Ada</span>
                                            ) : '-'}
                                        </td>
                                        <td className="px-3 py-2 border-r border-gray-100">
                                            {(s.dokumen_kk || s.dokumen_akte || s.dokumen_ktp || s.dokumen_ijazah || s.dokumen_sertifikat) ? (
                                                <span className="px-2 py-0.5 text-[10px] bg-green-100 text-green-600 rounded">Ada</span>
                                            ) : '-'}
                                        </td>
                                        <td className="px-3 py-2 sticky right-0 bg-white z-10 border-l border-gray-100 text-center">
                                            <div className="flex gap-1 justify-center">
                                                <button
                                                    onClick={() => openEditModal(s.id)}
                                                    className="p-1 px-2 text-blue-500 hover:bg-blue-50 rounded border border-blue-200"
                                                    title="Edit"
                                                >
                                                    <i className="fas fa-edit"></i>
                                                </button>
                                                <button
                                                    onClick={() => handleDelete(s.id, s.nama_lengkap)}
                                                    className="p-1 px-2 text-red-500 hover:bg-red-50 rounded border border-red-200"
                                                    title="Hapus"
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {/* Pagination */}
                {pagination.last_page > 1 && (
                    <div className="p-4 border-t flex justify-center gap-2">
                        <button
                            onClick={() => setCurrentPage(p => Math.max(1, p - 1))}
                            disabled={currentPage === 1}
                            className="px-3 py-1 border rounded disabled:opacity-50"
                        >
                            &laquo;
                        </button>
                        <span className="px-3 py-1">
                            {currentPage} / {pagination.last_page}
                        </span>
                        <button
                            onClick={() => setCurrentPage(p => Math.min(pagination.last_page, p + 1))}
                            disabled={currentPage === pagination.last_page}
                            className="px-3 py-1 border rounded disabled:opacity-50"
                        >
                            &raquo;
                        </button>
                    </div>
                )}
            </div>

            {/* Modal */}
            {modalOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div className="absolute inset-0 bg-black/50" onClick={() => setModalOpen(false)}></div>
                    <div className="relative bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
                        <form onSubmit={handleSubmit}>
                            {/* Header */}
                            <div className="px-6 py-4 bg-blue-600 text-white flex items-center justify-between flex-shrink-0">
                                <h5 className="font-bold">
                                    <i className={`fas ${isEditing ? 'fa-user-edit' : 'fa-user-plus'} mr-2`}></i>
                                    {isEditing ? 'Edit Data Santri' : 'Tambah Santri'}
                                </h5>
                                <button type="button" onClick={() => setModalOpen(false)} className="text-white/80 hover:text-white">
                                    <i className="fas fa-times"></i>
                                </button>
                            </div>

                            {/* Tabs */}
                            <div className="border-b flex-shrink-0">
                                <div className="flex">
                                    <button type="button" onClick={() => setActiveTab('santri')}
                                        className={`px-4 py-3 text-sm font-medium border-b-2 ${activeTab === 'santri' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'}`}>
                                        <i className="fas fa-user mr-1"></i> Data Santri
                                    </button>
                                    <button type="button" onClick={() => setActiveTab('ortu')}
                                        className={`px-4 py-3 text-sm font-medium border-b-2 ${activeTab === 'ortu' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'}`}>
                                        <i className="fas fa-users mr-1"></i> Data Orang Tua
                                    </button>
                                    <button type="button" onClick={() => setActiveTab('dokumen')}
                                        className={`px-4 py-3 text-sm font-medium border-b-2 ${activeTab === 'dokumen' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500'}`}>
                                        <i className="fas fa-file mr-1"></i> Dokumen
                                    </button>
                                </div>
                            </div>

                            {/* Body */}
                            <div className="p-6 overflow-y-auto flex-1">
                                {/* Tab Santri */}
                                {activeTab === 'santri' && (
                                    <div className="space-y-6">
                                        {/* Identitas */}
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-id-card mr-2"></i>Identitas</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div className="md:col-span-2">
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap <span className="text-red-500">*</span></label>
                                                    <input type="text" value={formData.nama_lengkap} onChange={(e) => updateField('nama_lengkap', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" required />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">NISN</label>
                                                    <input type="text" value={formData.nisn} onChange={(e) => updateField('nisn', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">NIK</label>
                                                    <input type="text" value={formData.nik} onChange={(e) => updateField('nik', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nomor KK</label>
                                                    <input type="text" value={formData.nomor_kk} onChange={(e) => updateField('nomor_kk', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir</label>
                                                    <input type="text" value={formData.tempat_lahir} onChange={(e) => updateField('tempat_lahir', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                                                    <input type="date" value={formData.tanggal_lahir} onChange={(e) => updateField('tanggal_lahir', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                                                    <select value={formData.jenis_kelamin} onChange={(e) => updateField('jenis_kelamin', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="">- Pilih -</option>
                                                        <option value="L">L (Laki-laki)</option>
                                                        <option value="P">P (Perempuan)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Jumlah Saudara</label>
                                                    <input type="number" value={formData.jumlah_saudara} onChange={(e) => updateField('jumlah_saudara', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" min="0" />
                                                </div>
                                            </div>
                                        </div>

                                        {/* Pendidikan */}
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-school mr-2"></i>Pendidikan</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Lembaga Sekolah</label>
                                                    <select value={formData.lembaga_sekolah} onChange={(e) => updateField('lembaga_sekolah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="">- Pilih -</option>
                                                        <option value="SMP NU BP">SMP NU BP</option>
                                                        <option value="MA ALHIKAM">MA ALHIKAM</option>
                                                        <option value="ITS">ITS</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Kelas</label>
                                                    <input type="text" value={formData.kelas} onChange={(e) => updateField('kelas', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Quran</label>
                                                    <input type="text" value={formData.quran} onChange={(e) => updateField('quran', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Kategori</label>
                                                    <input type="text" value={formData.kategori} onChange={(e) => updateField('kategori', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Status</label>
                                                    <select value={formData.status} onChange={(e) => updateField('status', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="AKTIF">AKTIF</option>
                                                        <option value="NON-AKTIF">NON-AKTIF</option>
                                                        <option value="LULUS">LULUS</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Asal Sekolah</label>
                                                    <input type="text" value={formData.asal_sekolah} onChange={(e) => updateField('asal_sekolah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Status Mukim</label>
                                                    <select value={formData.status_mukim} onChange={(e) => updateField('status_mukim', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="">- Pilih -</option>
                                                        <option value="PONDOK PP MAMBAUL HUDA">PONDOK PP MAMBAUL HUDA</option>
                                                        <option value="PONDOK SELAIN PP MAMBAUL HUDA">PONDOK SELAIN</option>
                                                        <option value="TIDAK PONDOK">TIDAK PONDOK</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Kontak */}
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-map-marker-alt mr-2"></i>Alamat & Kontak</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div className="md:col-span-3">
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                                    <textarea value={formData.alamat} onChange={(e) => updateField('alamat', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" rows="2"></textarea>
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Kecamatan</label>
                                                    <input type="text" value={formData.kecamatan} onChange={(e) => updateField('kecamatan', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Kabupaten</label>
                                                    <input type="text" value={formData.kabupaten} onChange={(e) => updateField('kabupaten', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">No WA Wali</label>
                                                    <input type="text" value={formData.no_wa_wali} onChange={(e) => updateField('no_wa_wali', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nomor RFID</label>
                                                    <input type="text" value={formData.nomor_rfid} onChange={(e) => updateField('nomor_rfid', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">No. PIP/PKH</label>
                                                    <input type="text" value={formData.nomor_pip} onChange={(e) => updateField('nomor_pip', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div className="md:col-span-3">
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Sumber Info</label>
                                                    <input type="text" value={formData.sumber_info} onChange={(e) => updateField('sumber_info', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Prestasi</label>
                                                    <input type="text" value={formData.prestasi} onChange={(e) => updateField('prestasi', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tingkat</label>
                                                    <input type="text" value={formData.tingkat_prestasi} onChange={(e) => updateField('tingkat_prestasi', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Juara</label>
                                                    <input type="text" value={formData.juara_prestasi} onChange={(e) => updateField('juara_prestasi', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Tab Orang Tua */}
                                {activeTab === 'ortu' && (
                                    <div className="space-y-6">
                                        {/* Data Ayah */}
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-male mr-2"></i>Data Ayah</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div className="md:col-span-2">
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nama Ayah</label>
                                                    <input type="text" value={formData.nama_ayah} onChange={(e) => updateField('nama_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">NIK Ayah</label>
                                                    <input type="text" value={formData.nik_ayah} onChange={(e) => updateField('nik_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir Ayah</label>
                                                    <input type="text" value={formData.tempat_lahir_ayah} onChange={(e) => updateField('tempat_lahir_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir Ayah</label>
                                                    <input type="date" value={formData.tanggal_lahir_ayah} onChange={(e) => updateField('tanggal_lahir_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Pekerjaan Ayah</label>
                                                    <input type="text" value={formData.pekerjaan_ayah} onChange={(e) => updateField('pekerjaan_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Penghasilan Ayah</label>
                                                    <select value={formData.penghasilan_ayah} onChange={(e) => updateField('penghasilan_ayah', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="">- Pilih -</option>
                                                        <option value="Di bawah Rp. 1.000.000">Di bawah Rp. 1.000.000</option>
                                                        <option value="Di bawah Rp. 2.500.000">Di bawah Rp. 2.500.000</option>
                                                        <option value="Di bawah Rp. 4.000.000">Di bawah Rp. 4.000.000</option>
                                                        <option value="Di atas Rp. 4.000.000">Di atas Rp. 4.000.000</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        {/* Data Ibu */}
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-female mr-2"></i>Data Ibu</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <div className="md:col-span-2">
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Nama Ibu</label>
                                                    <input type="text" value={formData.nama_ibu} onChange={(e) => updateField('nama_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">NIK Ibu</label>
                                                    <input type="text" value={formData.nik_ibu} onChange={(e) => updateField('nik_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tempat Lahir Ibu</label>
                                                    <input type="text" value={formData.tempat_lahir_ibu} onChange={(e) => updateField('tempat_lahir_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir Ibu</label>
                                                    <input type="date" value={formData.tanggal_lahir_ibu} onChange={(e) => updateField('tanggal_lahir_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Pekerjaan Ibu</label>
                                                    <input type="text" value={formData.pekerjaan_ibu} onChange={(e) => updateField('pekerjaan_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Penghasilan Ibu</label>
                                                    <select value={formData.penghasilan_ibu} onChange={(e) => updateField('penghasilan_ibu', e.target.value)}
                                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg">
                                                        <option value="">- Pilih -</option>
                                                        <option value="Di bawah Rp. 1.000.000">Di bawah Rp. 1.000.000</option>
                                                        <option value="Di bawah Rp. 2.500.000">Di bawah Rp. 2.500.000</option>
                                                        <option value="Di bawah Rp. 4.000.000">Di bawah Rp. 4.000.000</option>
                                                        <option value="Di atas Rp. 4.000.000">Di atas Rp. 4.000.000</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Tab Dokumen */}
                                {activeTab === 'dokumen' && (
                                    <div className="space-y-6">
                                        <div className="bg-gray-50 rounded-lg p-4">
                                            <h6 className="text-blue-600 font-semibold mb-3"><i className="fas fa-file-upload mr-2"></i>Upload Dokumen</h6>
                                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Foto Santri</label>
                                                    <input type="file" name="foto_santri" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Kartu Keluarga (KK)</label>
                                                    <input type="file" name="dokumen_kk" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*,.pdf" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Akte Kelahiran</label>
                                                    <input type="file" name="dokumen_akte" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*,.pdf" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">KTP Wali</label>
                                                    <input type="file" name="dokumen_ktp" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*,.pdf" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Ijazah</label>
                                                    <input type="file" name="dokumen_ijazah" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*,.pdf" />
                                                </div>
                                                <div>
                                                    <label className="block text-sm font-medium text-gray-600 mb-1">Sertifikat</label>
                                                    <input type="file" name="dokumen_sertifikat" className="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white" accept="image/*,.pdf" />
                                                </div>
                                            </div>
                                            <p className="text-[10px] text-gray-500 mt-2 italic">Format: JPG, PNG, GIF, PDF. Max 2MB per file.</p>
                                        </div>
                                    </div>
                                )}
                            </div>

                            {/* Footer */}
                            <div className="px-6 py-4 border-t bg-gray-50 flex gap-3 flex-shrink-0">
                                <button type="button" onClick={() => setModalOpen(false)}
                                    className="flex-1 py-2 bg-gray-200 text-gray-600 rounded-lg font-semibold hover:bg-gray-300">
                                    Batal
                                </button>
                                <button type="submit" disabled={saving}
                                    className="flex-1 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 disabled:opacity-50">
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
