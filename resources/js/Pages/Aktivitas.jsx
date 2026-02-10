import React, { useState, useEffect, useRef, useCallback } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';
import { QRCodeSVG } from 'qrcode.react';
import axios from 'axios';
import { PageSkeleton } from '../Components/Skeleton'; // Added
import LoadingSpinner from '../Components/LoadingSpinner';
import Swal from 'sweetalert2'; // Added
import { Html5Qrcode } from 'html5-qrcode'; // Added

// Category configurations
const CATEGORIES = {
    sakit: { label: 'Sakit', icon: 'fa-procedures', color: 'bg-red-100 text-red-500', judulLabel: 'DIAGNOSA' },
    izin_keluar: { label: 'Izin Keluar', icon: 'fa-sign-out-alt', color: 'bg-amber-100 text-amber-500', judulLabel: 'KEPERLUAN' },
    izin_pulang: { label: 'Izin Pulang', icon: 'fa-home', color: 'bg-orange-100 text-orange-500', judulLabel: 'ALASAN' },
    sambangan: { label: 'Sambangan', icon: 'fa-users', color: 'bg-emerald-100 text-emerald-500', judulLabel: 'NAMA PENJENGUK' },
    pelanggaran: { label: 'Pelanggaran', icon: 'fa-exclamation-triangle', color: 'bg-pink-100 text-pink-500', judulLabel: 'JENIS PELANGGARAN' },
    paket: { label: 'Paket', icon: 'fa-box-open', color: 'bg-blue-100 text-blue-500', judulLabel: 'ISI PAKET' },
    hafalan: { label: 'Hafalan', icon: 'fa-quran', color: 'bg-blue-100 text-blue-500', judulLabel: 'NAMA KITAB/SURAT' },
};

export default function Aktivitas() {
    const { user } = useAuth();
    const role = user?.role || 'user';
    const isAdmin = role === 'admin';
    const pageTitle = 'Aktivitas Santri';

    // State
    const [loading, setLoading] = useState(false);
    const [searchQuery, setSearchQuery] = useState('');
    const [searchResults, setSearchResults] = useState([]);
    const [showSearchResults, setShowSearchResults] = useState(false);
    const [selectedSiswa, setSelectedSiswa] = useState(null);
    const [isSearchingSiswa, setIsSearchingSiswa] = useState(false);
    const [showCamera, setShowCamera] = useState(false);

    // Table state
    const [aktivitasData, setAktivitasData] = useState([]);
    const [filterKategori, setFilterKategori] = useState('all');
    const [filterSearch, setFilterSearch] = useState('');
    const [filterTanggalDari, setFilterTanggalDari] = useState('');
    const [filterTanggalSampai, setFilterTanggalSampai] = useState('');
    const [currentPage, setCurrentPage] = useState(1);
    const [totalPages, setTotalPages] = useState(1);
    const [totalRecords, setTotalRecords] = useState(0);
    const [selectedIds, setSelectedIds] = useState([]);
    const [isRefreshing, setIsRefreshing] = useState(false);
    const [tableLoading, setTableLoading] = useState(false); // Added

    // Modal state
    const [showInputModal, setShowInputModal] = useState(false);
    const [showBulkWaModal, setShowBulkWaModal] = useState(false);
    const [showReportModal, setShowReportModal] = useState(false);
    const [showPrintSlipModal, setShowPrintSlipModal] = useState(false);
    const [modalKategori, setModalKategori] = useState('');
    const [editData, setEditData] = useState(null);
    const [bulkWaList, setBulkWaList] = useState([]);
    const [reportText, setReportText] = useState('');
    const [printSlipData, setPrintSlipData] = useState(null);

    // Form state
    const [formData, setFormData] = useState({
        tanggal: '',
        tanggal_selesai: '',
        batas_waktu: '',
        judul: '',
        keterangan: '',
        status_sambangan: '',
        status_kegiatan: 'Belum Diperiksa',
        status_paket: 'Belum Diterima',
    });
    const [fotoPreview, setFotoPreview] = useState(null);
    const [fotoFile, setFotoFile] = useState(null);
    const [bulkWAData, setBulkWAData] = useState([]);

    // Scanner state
    const [isScanning, setIsScanning] = useState(false); // Added
    const scannerRef = useRef(null); // Added

    // Refs
    const fileInputRef = useRef(null);
    const cameraInputRef = useRef(null);
    const searchRef = useRef(null); // Added
    const searchContainerRef = useRef(null);
    const qrScannerRef = useRef(null);

    // Fetch aktivitas data
    const fetchAktivitas = useCallback(async () => {
        setTableLoading(true); // Changed from setIsRefreshing
        try {
            const params = new URLSearchParams({
                kategori: filterKategori === 'all' ? '' : filterKategori,
                search_keyword: filterSearch,
                tanggal_dari: filterTanggalDari,
                tanggal_sampai: filterTanggalSampai,
                page: currentPage,
                length: 10,
            });

            const response = await axios.post('/api/aktivitas/data', {
                draw: 1,
                start: (currentPage - 1) * 10,
                length: 10,
                kategori: filterKategori === 'all' ? '' : filterKategori,
                search_keyword: filterSearch,
                tanggal_dari: filterTanggalDari,
                tanggal_sampai: filterTanggalSampai,
            });

            const data = response.data;
            setAktivitasData(data.data || []);
            setTotalRecords(data.recordsTotal || 0);
            setTotalPages(Math.ceil((data.recordsTotal || 0) / 10));
        } catch (err) {
            console.error('Error fetching aktivitas:', err);
            Swal.fire('Error', 'Gagal memuat data', 'error');
        } finally {
            setTableLoading(false);
        }
    }, [filterKategori, filterSearch, filterTanggalDari, filterTanggalSampai, currentPage]);

    useEffect(() => {
        fetchAktivitas();
    }, [fetchAktivitas]);

    // Close search results when clicking outside
    useEffect(() => {
        const handleClickOutside = (event) => {
            if (searchContainerRef.current && !searchContainerRef.current.contains(event.target)) {
                setShowSearchResults(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    // QR Scanner Functions // Added Start
    const startScanner = async () => {
        setIsScanning(true);
        try {
            // Html5Qrcode is already imported at the top
            scannerRef.current = new Html5Qrcode("aktivitas-qr-reader");

            await scannerRef.current.start(
                { facingMode: "environment" },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess,
                () => { } // ignore errors
            );
        } catch (err) {
            console.error('QR Scanner error:', err);
            Swal.fire('Error', 'Tidak dapat mengakses kamera', 'error');
            stopScanner();
        }
    };

    const stopScanner = useCallback(() => {
        if (scannerRef.current) {
            scannerRef.current.stop().catch(() => { });
            scannerRef.current = null;
        }
        setIsScanning(false);
    }, []);

    const onScanSuccess = (decodedText) => {
        stopScanner();
        handleSearchSiswa(decodedText.trim());
    };
    // QR Scanner Functions // Added End

    // Search siswa
    const handleSearchSiswa = async (query) => {
        setSearchQuery(query);
        if (query.length < 3) {
            setSearchResults([]);
            setShowSearchResults(false);
            setIsSearchingSiswa(false);
            return;
        }

        setIsSearchingSiswa(true);
        try {
            const response = await axios.get(`/api/santri/search?q=${encodeURIComponent(query)}`);
            const data = response.data;
            setSearchResults(data || []);
            setShowSearchResults(true);
        } catch (err) {
            console.error('Error searching siswa:', err);
        } finally {
            setIsSearchingSiswa(false);
        }
    };

    const selectSiswa = (siswa) => {
        setSelectedSiswa(siswa);
        setSearchQuery('');
        setShowSearchResults(false);
    };

    const resetSiswa = () => {
        setSelectedSiswa(null);
    };

    const generatePersonalMessage = (item) => {
        const jam = new Date().getHours();
        let salam = 'Selamat pagi';
        if (jam >= 11 && jam < 15) salam = 'Selamat siang';
        else if (jam >= 15 && jam < 18) salam = 'Selamat sore';
        else if (jam >= 18 || jam < 4) salam = 'Selamat malam';

        const categoryLabel = CATEGORIES[item.kategori]?.label || item.kategori;
        return `Assalamu'alaikum Wr. Wb.

${salam} Ayah/Bunda dari *${item.nama_lengkap}* (Kelas ${item.kelas}).

Kami menginformasikan aktivitas santri sbb:
📌 *Kategori:* ${categoryLabel}
📝 *Keterangan:* ${item.judul}
🗓️ *Tanggal:* ${new Date(item.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}

${item.keterangan ? `Note: ${item.keterangan}` : ''}

Demikian informasi ini kami sampaikan. Jazakumullah Khairan Katsiran.
Wassalamu'alaikum Wr. Wb.`;
    };

    const generateReportText = (items) => {
        const dateNow = new Date().toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        let text = `*LAPORAN AKTIVITAS SANTRI*\nTanggal Laporan: ${dateNow}\n\n`;

        items.forEach((item, index) => {
            const categoryLabel = CATEGORIES[item.kategori]?.label || item.kategori;
            text += `${index + 1}. *${item.nama_lengkap}* (${item.kelas})\n`;
            text += `   - [${categoryLabel}] ${item.judul}\n`;
            if (item.keterangan) text += `   - Ket: ${item.keterangan}\n`;
            text += `\n`;
        });

        text += `_Dibuat secara otomatis melalui Sistem Aktivitas Santri_`;
        return text;
    };
    // Handle category click
    const handleCategoryClick = (kategori) => {
        setFilterKategori(kategori);
        if (selectedSiswa) {
            openInputModal(kategori);
        }
    };

    // Modal functions
    const openInputModal = (kategori, data = null) => {
        if (!data && !selectedSiswa) {
            alert('Silakan pilih santri dulu untuk input data.');
            return;
        }

        setModalKategori(kategori);
        setEditData(data);

        const now = new Date();
        now.setMinutes(now.getMinutes() - now.getTimezoneOffset());

        setFormData({
            tanggal: data?.tanggal || now.toISOString().slice(0, 16),
            tanggal_selesai: data?.tanggal_selesai || '',
            batas_waktu: data?.batas_waktu || '',
            judul: data?.judul || '',
            keterangan: data?.keterangan || '',
            status_sambangan: data?.status_sambangan || '',
            status_kegiatan: data?.status_kegiatan || 'Belum Diperiksa',
            status_paket: data?.status_kegiatan || 'Belum Diterima',
        });

        setFotoPreview(data?.foto_dokumen_1 ? `/storage/${data.foto_dokumen_1}` : null);
        setFotoFile(null);
        setShowInputModal(true);
    };

    const closeInputModal = () => {
        setShowInputModal(false);
        setEditData(null);
        setFotoPreview(null);
        setFotoFile(null);
    };

    // Form submission
    const handleFormSubmit = async (e) => {
        e.preventDefault();

        // Validation for 'Paket'
        if (modalKategori === 'paket' && formData.status_paket === 'Sudah Diterima' && !fotoFile && !editData?.foto_dokumen_2) {
            Swal.fire({
                icon: 'warning',
                title: 'Foto Wajib',
                text: 'Harap ambil foto penerima paket untuk status "Sudah Diterima".'
            });
            return;
        }

        setLoading(true);
        try {
            const formDataObj = new FormData();
            formDataObj.append('siswa_id', editData?.siswa_id || selectedSiswa?.id);
            formDataObj.append('kategori', modalKategori);
            formDataObj.append('tanggal', formData.tanggal);
            formDataObj.append('tanggal_selesai', formData.tanggal_selesai);
            formDataObj.append('batas_waktu', formData.batas_waktu);
            formDataObj.append('judul', formData.judul);
            formDataObj.append('keterangan', formData.keterangan);
            formDataObj.append('status_sambangan', formData.status_sambangan);
            formDataObj.append('status_kegiatan', formData.status_kegiatan);
            formDataObj.append('status_paket', formData.status_paket);

            if (fotoFile) {
                formDataObj.append('foto_dokumen_1', fotoFile);
            }

            const url = editData ? `/api/aktivitas/${editData.id}/update` : '/api/aktivitas/store';

            const response = await axios.post(url, formDataObj);

            const result = response.data;

            if (result.status === 'success') {
                closeInputModal();
                fetchAktivitas();

                if (result.data?.kode_konfirmasi) {
                    setPrintSlipData(result.data);
                    setShowPrintSlipModal(true);
                } else {
                    alert(result.message || 'Data berhasil disimpan');
                }
            } else {
                alert(result.message || 'Terjadi kesalahan');
            }
        } catch (err) {
            console.error('Error submitting form:', err);
            alert('Terjadi kesalahan');
        } finally {
            setLoading(false);
        }
    };

    // Image Compression // Added Start
    const compressImage = (file, maxWidth = 1200, quality = 0.7) => {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    if (width > maxWidth) {
                        height = (height * maxWidth) / width;
                        width = maxWidth;
                    }

                    canvas.width = width;
                    canvas.height = height;

                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', quality);
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    };
    // Image Compression // Added End

    // Handle photo
    const handlePhotoSelect = async (file) => {
        if (file) {
            const compressedFile = await compressImage(file);
            setFotoFile(compressedFile);
            setFotoPreview(URL.createObjectURL(compressedFile));
        }
    };

    // Table actions
    const handleSelectAll = (checked) => {
        if (checked) {
            setSelectedIds(aktivitasData.map((item) => item.id));
        } else {
            setSelectedIds([]);
        }
    };

    const handleSelectRow = (id, checked) => {
        if (checked) {
            setSelectedIds([...selectedIds, id]);
        } else {
            setSelectedIds(selectedIds.filter((i) => i !== id));
        }
    };

    const handleEdit = async (id) => {
        try {
            const response = await axios.get(`/api/aktivitas/${id}/edit`);
            const result = response.data;
            if (result.status === 'success') {
                openInputModal(result.data.kategori, result.data);
            }
        } catch (err) {
            console.error('Error fetching edit data:', err);
        }
    };

    const handleDelete = async (id, name) => {
        const result = await Swal.fire({
            title: 'Hapus Data?',
            text: `Yakin ingin menghapus aktivitas "${name}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await axios.post('/api/aktivitas/bulk-delete', { ids: [id] });
            if (response.data.status === 'success') {
                fetchAktivitas();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } catch (err) {
            console.error('Error deleting:', err);
            Swal.fire('Error', 'Gagal menghapus data', 'error');
        }
    };

    const handleBulkDelete = async () => {
        if (selectedIds.length === 0) return;

        const result = await Swal.fire({
            title: 'Hapus Massal?',
            text: `Hapus ${selectedIds.length} data terpilih?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Semua!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await axios.post('/api/aktivitas/bulk-delete', { ids: selectedIds });
            if (response.data.status === 'success') {
                setSelectedIds([]);
                fetchAktivitas();
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Data berhasil dihapus',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        } catch (err) {
            console.error('Error bulk deleting:', err);
            Swal.fire('Error', 'Gagal menghapus data', 'error');
        }
    };

    // WA functions
    const handleSingleWa = async (item) => {
        if (!item.no_wa_wali) {
            alert('Nomor WA wali tidak tersedia');
            return;
        }

        const message = generatePersonalMessage(item);

        const result = await Swal.fire({
            title: 'Kirim WhatsApp?',
            text: `Kirim pesan ke ${item.no_wa_wali}?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await axios.post('/api/kirim-wa', {
                phone: item.no_wa_wali,
                message: message,
                image: item.kategori === 'paket' ? item.foto_dokumen_1 : null,
            });

            const result = response.data;
            if (result.status === 'success') {
                alert('Pesan berhasil dikirim');
            } else {
                alert(result.message || 'Gagal mengirim pesan');
            }
        } catch (err) {
            console.error('Error sending WA:', err);
            alert('Gagal mengirim pesan');
        }
    };

    const handleBulkWa = () => {
        const list = aktivitasData.filter(
            (item) => selectedIds.includes(item.id) && item.no_wa_wali
        );
        if (list.length === 0) {
            alert('Tidak ada nomor wali yang tersedia');
            return;
        }
        setBulkWaList(list);
        setShowBulkWaModal(true);
    };

    const sendBulkWa = async () => {
        setShowBulkWaModal(false);
        let sent = 0, failed = 0;

        for (const item of bulkWaList) {
            try {
                const response = await axios.post('/api/kirim-wa', {
                    phone: item.no_wa_wali,
                    message: generatePersonalMessage(item),
                    image: item.kategori === 'paket' ? item.foto_dokumen_1 : null,
                });
                const result = response.data;
                if (result.status === 'success') sent++;
                else failed++;
            } catch {
                failed++;
            }
            await new Promise((r) => setTimeout(r, 500));
        }

        alert(`Berhasil: ${sent}, Gagal: ${failed}`);
    };

    const handleBulkReport = () => {
        const list = aktivitasData.filter((item) => selectedIds.includes(item.id));
        if (list.length === 0) {
            alert('Tidak ada data yang dipilih');
            return;
        }
        setReportText(generateReportText(list));
        setShowReportModal(true);
    };

    const copyReport = () => {
        navigator.clipboard.writeText(reportText);
        alert('Teks berhasil disalin!');
    };

    const doPrintSlip = async () => {
        if (!printSlipData) return;

        Swal.fire({
            title: 'Mengirim ke Print Server...',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        try {
            const response = await axios.post('/api/print-queue', {
                job_type: 'slip_konfirmasi',
                nama_santri: printSlipData.nama_santri,
                kelas: printSlipData.kelas,
                kategori: printSlipData.kategori,
                judul: printSlipData.judul,
                batas_waktu: printSlipData.batas_waktu,
                kode_konfirmasi: printSlipData.kode_konfirmasi
            });

            if (response.data.success) {
                setShowPrintSlipModal(false);
                Swal.fire({
                    icon: 'success',
                    title: 'Terkirim!',
                    text: `Slip dikirim ke antrian cetak (Job #${response.data.job_id})`,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Error', 'Gagal mengirim ke print server', 'error');
            }
        } catch (error) {
            Swal.fire('Error', 'Gagal mengirim: ' + error.message, 'error');
        }
    };

    // Format helpers
    const formatDate = (dateStr) => {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')} ${d.getHours().toString().padStart(2, '0')}:${d.getMinutes().toString().padStart(2, '0')}`;
    };

    // Get visible categories based on role
    // Table Rendering Helpers
    const renderTableHeaders = () => {
        const commonClasses = "px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase whitespace-nowrap";
        const stickyHeaderClasses = commonClasses + " sticky right-0 bg-gray-50 z-20 shadow-[-4px_0_10px_rgba(0,0,0,0.05)]";

        switch (filterKategori) {
            case 'sakit':
                return (
                    <>
                        <th className={commonClasses}>Tgl Sakit</th>
                        <th className={commonClasses}>Tgl Sembuh</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>Diagnosa</th>
                        <th className={commonClasses}>Status</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
            case 'izin_keluar':
            case 'izin_pulang':
                return (
                    <>
                        <th className={commonClasses}>Waktu Pergi</th>
                        <th className={commonClasses}>Waktu Kembali</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>{filterKategori === 'izin_keluar' ? 'Keperluan' : 'Alasan'}</th>
                        <th className={commonClasses}>Keterangan</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
            case 'sambangan':
                return (
                    <>
                        <th className={commonClasses}>Waktu</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>Penjenguk</th>
                        <th className={commonClasses}>Hubungan</th>
                        <th className={commonClasses}>Keterangan</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
            case 'paket':
                return (
                    <>
                        <th className={commonClasses}>Tgl Tiba</th>
                        <th className={commonClasses}>Tgl Terima</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>Isi Paket</th>
                        <th className={commonClasses}>Foto</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
            case 'pelanggaran':
            case 'hafalan':
                return (
                    <>
                        <th className={commonClasses}>Tanggal</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>{filterKategori === 'pelanggaran' ? 'Jenis' : 'Kitab/Surat'}</th>
                        <th className={commonClasses}>Keterangan</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
            default:
                return (
                    <>
                        <th className={commonClasses}>Tanggal</th>
                        <th className={commonClasses}>Siswa</th>
                        <th className={commonClasses}>Kategori</th>
                        <th className={commonClasses}>Judul/Isi</th>
                        <th className={commonClasses}>Keterangan</th>
                        <th className={stickyHeaderClasses}>Aksi</th>
                    </>
                );
        }
    };

    const renderTableRow = (item) => {
        const commonTd = "px-5 py-4 text-sm text-gray-600 whitespace-nowrap";
        const actionTd = "px-5 py-4 whitespace-nowrap";

        const renderSiswaCell = () => (
            <td className="px-5 py-4 whitespace-nowrap">
                <div className="font-medium text-gray-800">{item.nama_lengkap}</div>
                <div className="text-sm text-gray-500">{item.nomor_induk || '-'}</div>
            </td>
        );

        const renderActionCell = () => (
            <td className={actionTd + " sticky right-0 bg-white z-10 shadow-[-4px_0_10px_rgba(0,0,0,0.05)]"}>
                <div className="flex gap-1">
                    <button onClick={() => handleEdit(item.id)} className="p-1.5 h-8 w-8 flex items-center justify-center text-amber-500 border border-amber-200 rounded-lg hover:bg-amber-50" title="Edit">
                        <i className="fas fa-pencil-alt text-xs"></i>
                    </button>
                    <button onClick={() => handleSingleWa(item)} className="p-1.5 h-8 w-8 flex items-center justify-center text-emerald-500 border border-emerald-200 rounded-lg hover:bg-emerald-50" title="WA ke Wali">
                        <i className="fab fa-whatsapp text-xs"></i>
                    </button>
                    {isAdmin && (
                        <button onClick={() => handleDelete(item.id, item.nama_lengkap)} className="p-1.5 h-8 w-8 flex items-center justify-center text-red-500 border border-red-200 rounded-lg hover:bg-red-50" title="Hapus">
                            <i className="fas fa-trash-alt text-xs"></i>
                        </button>
                    )}
                </div>
            </td>
        );

        switch (filterKategori) {
            case 'sakit':
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        <td className={commonTd}>
                            {item.tanggal_selesai ? formatDate(item.tanggal_selesai) : <span className="px-2 py-0.5 bg-red-100 text-red-600 rounded-full text-xs font-bold">Belum Sembuh</span>}
                        </td>
                        {renderSiswaCell()}
                        <td className={commonTd}>{item.judul}</td>
                        <td className={commonTd}>
                            <span className={`px-2 py-0.5 rounded-full text-xs font-bold ${item.status_kegiatan === 'Sudah Diperiksa' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600'}`}>
                                {item.status_kegiatan || 'Belum Diperiksa'}
                            </span>
                        </td>
                        {renderActionCell()}
                    </>
                );
            case 'izin_keluar':
            case 'izin_pulang':
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        <td className={commonTd}>
                            {item.tanggal_selesai ? formatDate(item.tanggal_selesai) : <span className="px-2 py-0.5 bg-amber-100 text-amber-600 rounded-full text-xs font-bold">Belum Kembali</span>}
                        </td>
                        {renderSiswaCell()}
                        <td className={commonTd}>{item.judul}</td>
                        <td className={commonTd}>{item.keterangan || '-'}</td>
                        {renderActionCell()}
                    </>
                );
            case 'sambangan':
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        {renderSiswaCell()}
                        <td className={commonTd}>{item.judul}</td>
                        <td className={commonTd}>
                            <span className="px-2 py-1 bg-blue-50 text-blue-600 rounded text-xs font-medium">{item.status_sambangan || '-'}</span>
                        </td>
                        <td className={commonTd}>{item.keterangan || '-'}</td>
                        {renderActionCell()}
                    </>
                );
            case 'paket':
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        <td className={commonTd}>
                            {item.tanggal_selesai ? formatDate(item.tanggal_selesai) : <span className="px-2 py-0.5 bg-amber-100 text-amber-600 rounded-full text-xs font-bold">Belum Diterima</span>}
                        </td>
                        {renderSiswaCell()}
                        <td className={commonTd}>{item.judul}</td>
                        <td className={commonTd}>
                            <div className="flex gap-1">
                                {item.foto_dokumen_1 && (
                                    <a href={`/storage/${item.foto_dokumen_1}`} target="_blank" className="p-1 hover:text-blue-500" title="Foto Paket">
                                        <i className="fas fa-box-open"></i>
                                    </a>
                                )}
                                {item.foto_dokumen_2 && (
                                    <a href={`/storage/${item.foto_dokumen_2}`} target="_blank" className="p-1 hover:text-emerald-500" title="Foto Penerima">
                                        <i className="fas fa-user-check"></i>
                                    </a>
                                )}
                                {!item.foto_dokumen_1 && !item.foto_dokumen_2 && '-'}
                            </div>
                        </td>
                        {renderActionCell()}
                    </>
                );
            case 'pelanggaran':
            case 'hafalan':
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        {renderSiswaCell()}
                        <td className={commonTd}>{item.judul}</td>
                        <td className={commonTd}>{item.keterangan || '-'}</td>
                        {renderActionCell()}
                    </>
                );
            default:
                return (
                    <>
                        <td className={commonTd}>{formatDate(item.tanggal)}</td>
                        {renderSiswaCell()}
                        <td className={commonTd}>
                            <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase ${CATEGORIES[item.kategori]?.color || 'bg-gray-100 text-gray-600'}`}>
                                {CATEGORIES[item.kategori]?.label || (item.kategori || '').replace('_', ' ')}
                            </span>
                        </td>
                        <td className={commonTd}>{item.judul || '-'}</td>
                        <td className={commonTd}>{item.keterangan || '-'}</td>
                        {renderActionCell()}
                    </>
                );
        }
    };

    const getVisibleCategories = () => {
        const cats = ['sakit'];
        if (role !== 'kesehatan') {
            cats.push('izin_keluar', 'izin_pulang', 'sambangan', 'pelanggaran', 'paket');
        }
        if (role === 'admin' || role === 'guru') {
            cats.push('hafalan');
        }
        return cats;
    };

    const visibleCategories = getVisibleCategories();

    // Set document title
    useEffect(() => {
        document.title = `${pageTitle} - Aktivitas Santri`;
    }, [pageTitle]);

    return (
        <>


            <div className="grid lg:grid-cols-12 gap-4">
                {/* LEFT COLUMN - Input */}
                <div className="lg:col-span-4 overflow-hidden">
                    <div className="bg-white rounded-xl shadow-sm p-4">
                        {/* Search */}
                        <div className="mb-4 relative" ref={searchContainerRef}>
                            <p className="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Pilih Santri</p>
                            <div className="relative">
                                <input
                                    type="text"
                                    value={searchQuery}
                                    onChange={(e) => handleSearchSiswa(e.target.value)}
                                    placeholder="Cari nama atau NIS..."
                                    className="w-full px-4 py-3 pr-12 rounded-lg border border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                />
                                {isSearchingSiswa ? (
                                    <div className="absolute right-12 top-1/2 -translate-y-1/2">
                                        <div className="animate-spin rounded-full h-4 w-4 border-2 border-blue-500 border-t-transparent"></div>
                                    </div>
                                ) : null}
                                <Link
                                    to="/pemindai"
                                    className="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg border border-gray-200 bg-white text-blue-500 hover:bg-blue-500 hover:text-white transition-colors"
                                >
                                    <i className="fas fa-qrcode"></i>
                                </Link>
                            </div>

                            {/* QR Scanner Container */}
                            {isScanning && (
                                <div className="mt-3 rounded-xl overflow-hidden shadow-sm relative">
                                    <div id="aktivitas-qr-reader" className="w-full"></div>
                                    <button
                                        type="button"
                                        onClick={stopScanner}
                                        className="absolute top-2 right-2 w-8 h-8 flex items-center justify-center rounded-full bg-red-500 text-white shadow-lg z-10"
                                    >
                                        <i className="fas fa-times"></i>
                                    </button>
                                </div>
                            )}

                            {/* Search Results */}
                            {showSearchResults && searchResults.length > 0 && (
                                <div className="absolute z-50 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-64 overflow-y-auto">
                                    {searchResults.map((s) => (
                                        <button
                                            key={s.id}
                                            onClick={() => selectSiswa(s)}
                                            className="w-full px-4 py-3 text-left hover:bg-gray-50 border-b border-gray-100 last:border-0"
                                        >
                                            <div className="font-bold text-gray-800">{s.nama_lengkap}</div>
                                            <div className="text-sm text-gray-500">{s.kelas} | {s.nisn}</div>
                                        </button>
                                    ))}
                                </div>
                            )}
                        </div>

                        {/* Student Card */}
                        <div className="mb-4">
                            {!selectedSiswa ? (
                                <div className="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center bg-gray-50">
                                    <i className="far fa-id-card text-4xl text-gray-300 mb-3"></i>
                                    <p className="text-sm text-gray-500 font-medium">Belum ada siswa dipilih</p>
                                </div>
                            ) : (
                                <div className="bg-gradient-to-br from-blue-500 to-blue-400 rounded-xl p-5 text-white relative shadow-lg">
                                    <button
                                        onClick={resetSiswa}
                                        className="absolute top-3 right-3 text-white/70 hover:text-white"
                                    >
                                        <i className="fas fa-times"></i>
                                    </button>
                                    <h4 className="font-bold text-lg mb-3 truncate pr-8">{selectedSiswa.nama_lengkap}</h4>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex justify-between border-b border-white/20 pb-2">
                                            <span className="opacity-80">NIS</span>
                                            <span className="font-semibold">{selectedSiswa.nisn}</span>
                                        </div>
                                        <div className="flex justify-between border-b border-white/20 pb-2">
                                            <span className="opacity-80">Kelas</span>
                                            <span className="font-semibold">{selectedSiswa.kelas}</span>
                                        </div>
                                        <div className="flex justify-between">
                                            <span className="opacity-80">Alamat</span>
                                            <span className="font-semibold truncate max-w-[150px]">{selectedSiswa.alamat || '-'}</span>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>

                        {/* Category Buttons */}
                        <div>
                            <p className="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Input Data</p>
                            <div className="grid grid-cols-2 gap-2">
                                {visibleCategories.map((cat) => (
                                    <button
                                        key={cat}
                                        onClick={() => handleCategoryClick(cat)}
                                        className="flex items-center gap-2 p-2.5 bg-white border border-gray-200 rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all text-left overflow-hidden"
                                    >
                                        <div className={`w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0 ${CATEGORIES[cat].color}`}>
                                            <i className={`fas ${CATEGORIES[cat].icon} text-sm`}></i>
                                        </div>
                                        <span className="font-bold text-xs text-gray-700 truncate">{CATEGORIES[cat].label}</span>
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>
                </div>

                {/* RIGHT COLUMN - Table */}
                <div className="lg:col-span-8 min-w-0">
                    <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                        {/* Header */}
                        <div className="px-4 py-3 border-b border-gray-100/50">
                            <div className="flex flex-col gap-3">
                                <div className="flex flex-wrap items-center justify-between gap-2">
                                    <h6 className="font-bold text-gray-800 flex items-center gap-2">
                                        <i className="fas fa-history text-blue-500"></i>
                                        <span>RIWAYAT</span>
                                    </h6>
                                    <select
                                        value={filterKategori}
                                        onChange={(e) => { setFilterKategori(e.target.value); setCurrentPage(1); }}
                                        className="px-3 py-1.5 bg-gray-100 border-0 rounded-lg text-sm font-semibold text-gray-600 flex-shrink-0"
                                    >
                                        <option value="all">SEMUA</option>
                                        {visibleCategories.map((cat) => (
                                            <option key={cat} value={cat}>{CATEGORIES[cat].label.toUpperCase()}</option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex flex-wrap items-center gap-2">
                                    <input
                                        type="date"
                                        value={filterTanggalDari}
                                        onChange={(e) => { setFilterTanggalDari(e.target.value); setCurrentPage(1); }}
                                        className="px-2 py-1.5 border border-gray-200 rounded-lg text-sm flex-1 min-w-[120px]"
                                    />
                                    <span className="text-gray-400">-</span>
                                    <input
                                        type="date"
                                        value={filterTanggalSampai}
                                        onChange={(e) => { setFilterTanggalSampai(e.target.value); setCurrentPage(1); }}
                                        className="px-2 py-1.5 border border-gray-200 rounded-lg text-sm flex-1 min-w-[120px]"
                                    />
                                    <input
                                        type="text"
                                        value={filterSearch}
                                        onChange={(e) => { setFilterSearch(e.target.value); setCurrentPage(1); }}
                                        placeholder="Cari..."
                                        className="px-2 py-1.5 border border-gray-200 rounded-lg text-sm flex-1 min-w-[80px]"
                                    />
                                    <button
                                        onClick={fetchAktivitas}
                                        className="px-3 py-1.5 bg-gray-100 border border-gray-200 rounded-lg hover:bg-gray-200 flex-shrink-0"
                                    >
                                        <i className={`fas fa-sync-alt ${isRefreshing ? 'animate-spin' : ''}`}></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Bulk Actions */}
                        {selectedIds.length > 0 && (
                            <div className="px-4 py-2 bg-green-50 border-b border-green-200 flex items-center justify-between">
                                <span className="text-sm font-bold text-green-700">
                                    <i className="fas fa-check-circle mr-1"></i>
                                    {selectedIds.length} data terpilih
                                </span>
                                <div className="flex gap-2">
                                    <button onClick={handleBulkWa} className="px-3 py-1 bg-green-500 text-white text-sm font-bold rounded-lg hover:bg-green-600">
                                        <i className="fab fa-whatsapp mr-1"></i> WA Massal
                                    </button>
                                    <button onClick={handleBulkReport} className="px-3 py-1 bg-blue-500 text-white text-sm font-bold rounded-lg hover:bg-blue-600">
                                        <i className="fas fa-file-alt mr-1"></i> Laporan
                                    </button>
                                    {isAdmin && (
                                        <button onClick={handleBulkDelete} className="px-3 py-1 bg-red-500 text-white text-sm font-bold rounded-lg hover:bg-red-600">
                                            <i className="fas fa-trash-alt mr-1"></i> Hapus
                                        </button>
                                    )}
                                </div>
                            </div>
                        )}

                        {/* Table with horizontal scroll */}
                        <div className="overflow-x-auto">
                            <table className="w-full min-w-[700px]">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-5 py-3 text-left">
                                            <input
                                                type="checkbox"
                                                checked={selectedIds.length === aktivitasData.length && aktivitasData.length > 0}
                                                onChange={(e) => handleSelectAll(e.target.checked)}
                                                className="rounded"
                                            />
                                        </th>
                                        {renderTableHeaders()}
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-gray-100/50">
                                    {tableLoading ? (
                                        <tr>
                                            <td colSpan="10" className="px-5 py-4">
                                                <LoadingSpinner size="medium" text="Memuat riwayat aktivitas..." />
                                            </td>
                                        </tr>
                                    ) : aktivitasData.length === 0 ? (
                                        <tr>
                                            <td colSpan="10" className="px-5 py-8 text-center text-gray-500">
                                                Belum ada data aktivitas
                                            </td>
                                        </tr>
                                    ) : (
                                        aktivitasData.map((item) => (
                                            <tr key={item.id} className="hover:bg-gray-50">
                                                <td className="px-5 py-4">
                                                    <input
                                                        type="checkbox"
                                                        checked={selectedIds.includes(item.id)}
                                                        onChange={(e) => handleSelectRow(item.id, e.target.checked)}
                                                        className="rounded"
                                                    />
                                                </td>
                                                {renderTableRow(item)}
                                            </tr>
                                        ))
                                    )}
                                </tbody>
                            </table>
                        </div>

                        {/* Pagination */}
                        {totalPages > 1 && (
                            <div className="px-4 py-3 border-t border-gray-100/50 flex items-center justify-between">
                                <span className="text-sm text-gray-500">
                                    Menampilkan {(currentPage - 1) * 10 + 1} - {Math.min(currentPage * 10, totalRecords)} dari {totalRecords}
                                </span>
                                <div className="flex gap-1">
                                    <button
                                        onClick={() => setCurrentPage(Math.max(1, currentPage - 1))}
                                        disabled={currentPage === 1}
                                        className="px-3 py-1 border border-gray-200 rounded-lg disabled:opacity-50"
                                    >
                                        <i className="fas fa-chevron-left"></i>
                                    </button>
                                    <button
                                        onClick={() => setCurrentPage(Math.min(totalPages, currentPage + 1))}
                                        disabled={currentPage === totalPages}
                                        className="px-3 py-1 border border-gray-200 rounded-lg disabled:opacity-50"
                                    >
                                        <i className="fas fa-chevron-right"></i>
                                    </button>
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </div>

            {/* INPUT MODAL */}
            {
                showInputModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div className="fixed inset-0 bg-black/50" onClick={closeInputModal}></div>
                        <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
                            <form onSubmit={handleFormSubmit}>
                                <div className="px-6 py-4 bg-blue-500 text-white flex items-center justify-between">
                                    <h6 className="font-bold">{editData ? 'EDIT DATA' : ''} {CATEGORIES[modalKategori]?.label.toUpperCase() || 'INPUT DATA'}</h6>
                                    <button type="button" onClick={closeInputModal} className="text-white/80 hover:text-white">
                                        <i className="fas fa-times text-xl"></i>
                                    </button>
                                </div>
                                <div className="p-6 overflow-y-auto max-h-[60vh] space-y-4">
                                    <div className="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase block mb-1">
                                                {modalKategori === 'sakit' ? 'TANGGAL SAKIT' : modalKategori === 'izin_keluar' || modalKategori === 'izin_pulang' ? 'TANGGAL PERGI' : 'TANGGAL'}
                                            </label>
                                            <input
                                                type="datetime-local"
                                                value={formData.tanggal}
                                                onChange={(e) => setFormData({ ...formData, tanggal: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                                required
                                            />
                                        </div>
                                        {(modalKategori === 'izin_keluar' || modalKategori === 'izin_pulang') && (
                                            <div>
                                                <label className="text-xs font-bold text-gray-400 uppercase block mb-1">BATAS WAKTU</label>
                                                <input
                                                    type="datetime-local"
                                                    value={formData.batas_waktu}
                                                    onChange={(e) => setFormData({ ...formData, batas_waktu: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                                />
                                            </div>
                                        )}
                                        {(modalKategori === 'sakit' || modalKategori === 'izin_keluar' || modalKategori === 'izin_pulang' || (modalKategori === 'paket' && editData)) && (
                                            <div>
                                                <label className="text-xs font-bold text-gray-400 uppercase block mb-1">
                                                    {modalKategori === 'sakit' ? 'TANGGAL SEMBUH' : modalKategori === 'paket' ? 'TANGGAL DITERIMA' : 'TANGGAL KEMBALI'}
                                                </label>
                                                <input
                                                    type="datetime-local"
                                                    value={formData.tanggal_selesai}
                                                    onChange={(e) => setFormData({ ...formData, tanggal_selesai: e.target.value })}
                                                    className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                                />
                                                <small className="text-gray-400">Opsional</small>
                                            </div>
                                        )}
                                    </div>

                                    <div>
                                        <label className="text-xs font-bold text-gray-400 uppercase block mb-1">
                                            {CATEGORIES[modalKategori]?.judulLabel || 'JUDUL'}
                                        </label>
                                        <input
                                            type="text"
                                            value={formData.judul}
                                            onChange={(e) => setFormData({ ...formData, judul: e.target.value })}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                            placeholder="..."
                                        />
                                    </div>

                                    {modalKategori === 'sambangan' && (
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase block mb-1">STATUS PENJENGUK</label>
                                            <select
                                                value={formData.status_sambangan}
                                                onChange={(e) => setFormData({ ...formData, status_sambangan: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                            >
                                                <option value="">-- Pilih --</option>
                                                <option value="Keluarga">Keluarga Inti</option>
                                                <option value="Kerabat">Kerabat</option>
                                                <option value="Teman">Teman</option>
                                                <option value="Lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    )}

                                    {modalKategori === 'sakit' && (
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase block mb-1">STATUS PERIKSA</label>
                                            <select
                                                value={formData.status_kegiatan}
                                                onChange={(e) => setFormData({ ...formData, status_kegiatan: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                            >
                                                <option value="Belum Diperiksa">Belum Diperiksa</option>
                                                <option value="Sudah Diperiksa">Sudah Diperiksa</option>
                                            </select>
                                        </div>
                                    )}

                                    {modalKategori === 'paket' && editData && (
                                        <div>
                                            <label className="text-xs font-bold text-gray-400 uppercase block mb-1">STATUS PAKET</label>
                                            <select
                                                value={formData.status_paket}
                                                onChange={(e) => setFormData({ ...formData, status_paket: e.target.value })}
                                                className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                            >
                                                <option value="Belum Diterima">Belum Diterima</option>
                                                <option value="Sudah Diterima">Sudah Diterima</option>
                                            </select>
                                        </div>
                                    )}

                                    <div>
                                        <label className="text-xs font-bold text-gray-400 uppercase block mb-1">KETERANGAN</label>
                                        <textarea
                                            value={formData.keterangan}
                                            onChange={(e) => setFormData({ ...formData, keterangan: e.target.value })}
                                            className="w-full px-3 py-2 border border-gray-200 rounded-lg"
                                            rows="3"
                                            placeholder="Tambahkan detail..."
                                        ></textarea>
                                    </div>

                                    <div>
                                        <label className="text-xs font-bold text-gray-400 uppercase block mb-1">
                                            FOTO BUKTI <span className="text-gray-400 font-normal">(Opsional)</span>
                                        </label>
                                        <div className={`border-2 border-dashed rounded-xl p-5 text-center ${fotoPreview ? 'border-green-400 bg-green-50' : 'border-gray-200 bg-gray-50'}`}>
                                            <input
                                                ref={fileInputRef}
                                                type="file"
                                                accept="image/*"
                                                onChange={(e) => handlePhotoSelect(e.target.files[0])}
                                                className="hidden"
                                            />
                                            <input
                                                ref={cameraInputRef}
                                                type="file"
                                                accept="image/*"
                                                capture="environment"
                                                onChange={(e) => handlePhotoSelect(e.target.files[0])}
                                                className="hidden"
                                            />

                                            {!fotoPreview ? (
                                                <div className="flex flex-wrap justify-center gap-3">
                                                    <button
                                                        type="button"
                                                        onClick={() => cameraInputRef.current?.click()}
                                                        className="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-semibold text-sm flex items-center gap-2"
                                                    >
                                                        <i className="fas fa-camera"></i> Ambil Foto
                                                    </button>
                                                    <button
                                                        type="button"
                                                        onClick={() => fileInputRef.current?.click()}
                                                        className="px-4 py-2 bg-white border border-gray-200 text-gray-600 rounded-lg font-semibold text-sm flex items-center gap-2"
                                                    >
                                                        <i className="fas fa-folder-open"></i> Pilih File
                                                    </button>
                                                </div>
                                            ) : (
                                                <div className="relative inline-block">
                                                    <img src={fotoPreview} alt="Preview" className="max-h-64 rounded-lg shadow" />
                                                    <button
                                                        type="button"
                                                        onClick={() => { setFotoPreview(null); setFotoFile(null); }}
                                                        className="absolute -top-2 -right-2 w-7 h-7 bg-red-500 text-white rounded-full flex items-center justify-center shadow"
                                                    >
                                                        <i className="fas fa-times text-xs"></i>
                                                    </button>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>
                                <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                    <button type="button" onClick={closeInputModal} className="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium">
                                        Batal
                                    </button>
                                    <button type="submit" disabled={loading} className="px-4 py-2 bg-blue-500 text-white rounded-lg font-bold shadow disabled:opacity-50">
                                        {loading ? 'Menyimpan...' : 'SIMPAN DATA'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                )
            }

            {/* BULK WA MODAL */}
            {
                showBulkWaModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div className="fixed inset-0 bg-black/50" onClick={() => setShowBulkWaModal(false)}></div>
                        <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
                            <div className="px-6 py-4 bg-green-500 text-white flex items-center justify-between">
                                <h6 className="font-bold"><i className="fab fa-whatsapp mr-2"></i>WA MASSAL</h6>
                                <button onClick={() => setShowBulkWaModal(false)} className="text-white/80 hover:text-white">
                                    <i className="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div className="p-4">
                                <div className="bg-green-50 border border-green-200 rounded-lg p-3 mb-3 text-sm text-green-700">
                                    <i className="fas fa-info-circle mr-1"></i>
                                    Kirim ke <strong>{bulkWaList.length} wali</strong>
                                </div>
                                <div className="mb-3">
                                    <label className="text-[10px] font-bold text-gray-400 uppercase block mb-1">DAFTAR PENERIMA</label>
                                    <div className="border border-gray-200 rounded-lg max-h-32 overflow-y-auto bg-gray-50">
                                        {bulkWaList.map((item) => (
                                            <div key={item.id} className="flex justify-between items-center px-3 py-1.5 border-b border-gray-100 last:border-0 text-sm">
                                                <div>
                                                    <strong>{item.nama_lengkap}</strong>
                                                    <span className="text-gray-400 mx-1">|</span>
                                                    <small className="text-gray-500">{(item.kategori || '').replace('_', ' ')}</small>
                                                </div>
                                                <span className="text-green-600 font-semibold">{item.no_wa_wali}</span>
                                            </div>
                                        ))}
                                    </div>
                                </div>
                                {bulkWaList.length > 0 && (
                                    <div className="mb-1">
                                        <label className="text-[10px] font-bold text-gray-400 uppercase block mb-1">PREVIEW PESAN</label>
                                        <div className="border border-gray-200 rounded-lg p-3 bg-green-50 text-xs font-mono whitespace-pre-wrap max-h-40 overflow-y-auto">
                                            {generatePersonalMessage(bulkWaList[0])}
                                        </div>
                                    </div>
                                )}
                            </div>
                            <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3 border-t border-gray-100">
                                <button onClick={() => setShowBulkWaModal(false)} className="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium text-sm">
                                    Batal
                                </button>
                                <button onClick={sendBulkWa} className="px-4 py-2 bg-green-500 text-white rounded-lg font-bold shadow text-sm">
                                    <i className="fab fa-whatsapp mr-1"></i> KIRIM SEKARANG
                                </button>
                            </div>
                        </div>
                    </div>
                )
            }

            {/* REPORT MODAL */}
            {
                showReportModal && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div className="fixed inset-0 bg-black/50" onClick={() => setShowReportModal(false)}></div>
                        <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden">
                            <div className="px-6 py-4 bg-blue-500 text-white flex items-center justify-between">
                                <h6 className="font-bold"><i className="fas fa-file-alt mr-2"></i>PREVIEW LAPORAN</h6>
                                <button onClick={() => setShowReportModal(false)} className="text-white/80 hover:text-white">
                                    <i className="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div className="p-6">
                                <div className="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700">
                                    <i className="fas fa-info-circle mr-1"></i>
                                    Laporan dari <strong>{selectedIds.length} data</strong>
                                </div>
                                <div>
                                    <label className="text-xs font-bold text-gray-400 uppercase block mb-2">TEKS LAPORAN</label>
                                    <textarea
                                        value={reportText}
                                        readOnly
                                        className="w-full px-3 py-2 border border-gray-200 rounded-lg font-mono text-sm"
                                        rows="12"
                                    ></textarea>
                                </div>
                            </div>
                            <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3">
                                <button onClick={() => setShowReportModal(false)} className="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium">
                                    Tutup
                                </button>
                                <button onClick={copyReport} className="px-4 py-2 bg-blue-500 text-white rounded-lg font-bold shadow">
                                    <i className="fas fa-copy mr-1"></i> COPY TEKS
                                </button>
                            </div>
                        </div>
                    </div>
                )
            }

            {/* PRINT SLIP MODAL */}
            {
                showPrintSlipModal && printSlipData && (
                    <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                        <div className="fixed inset-0 bg-black/50" onClick={() => setShowPrintSlipModal(false)}></div>
                        <div className="relative bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] flex flex-col overflow-hidden print-slip">
                            <div className="px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-400 text-white flex items-center justify-between no-print flex-shrink-0">
                                <h6 className="font-bold"><i className="fas fa-print mr-2"></i>SLIP IZIN KELUAR</h6>
                                <button onClick={() => setShowPrintSlipModal(false)} className="text-white/80 hover:text-white">
                                    <i className="fas fa-times text-xl"></i>
                                </button>
                            </div>
                            <div className="p-6 overflow-y-auto flex-1 custom-scrollbar">
                                <div className="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-4 text-sm text-blue-700 no-print">
                                    <i className="fas fa-info-circle mr-1"></i>
                                    Cetak slip ini dan berikan kepada santri.
                                </div>
                                <div className="border border-gray-200 rounded-lg p-4 bg-white text-center">
                                    <h5 className="font-bold text-gray-800 mb-1">{printSlipData.nama_santri}</h5>
                                    <small className="text-gray-500">Kelas {printSlipData.kelas}</small>
                                    <div className="flex justify-between my-4 px-2 text-left">
                                        <div>
                                            <small className="text-gray-400">Keperluan</small>
                                            <div className="font-semibold">{printSlipData.judul || '-'}</div>
                                        </div>
                                        <div className="text-right">
                                            <small className="text-gray-400">Batas Waktu</small>
                                            <div className="font-semibold text-red-500">
                                                {printSlipData.batas_waktu ? new Date(printSlipData.batas_waktu).toLocaleString('id-ID', { day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit' }) : '-'}
                                            </div>
                                        </div>
                                    </div>
                                    <hr className="my-4" />
                                    <div className="text-3xl font-bold tracking-widest font-mono bg-gray-100 rounded py-2 mb-2">
                                        {printSlipData.kode_konfirmasi}
                                    </div>
                                    <small className="text-gray-400">Kode Konfirmasi</small>

                                    <div className="mt-6 flex flex-col items-center">
                                        <div className="bg-white p-3 rounded-xl border border-gray-100 shadow-sm">
                                            <QRCodeSVG
                                                value={printSlipData.kode_konfirmasi}
                                                size={160}
                                                level="H"
                                                includeMargin={true}
                                            />
                                        </div>
                                        <small className="text-gray-400 mt-2">Scan untuk verifikasi</small>
                                    </div>
                                </div>
                            </div>
                            <div className="px-6 py-4 bg-gray-50 flex justify-end gap-3 no-print flex-shrink-0 border-t border-gray-100">
                                <button onClick={() => setShowPrintSlipModal(false)} className="px-4 py-2 bg-gray-100 border border-gray-200 rounded-lg font-medium">
                                    Tutup
                                </button>
                                <button
                                    onClick={doPrintSlip}
                                    className="px-4 py-2 bg-emerald-500 text-white rounded-lg font-bold shadow hover:bg-emerald-600 transition-colors"
                                >
                                    <i className="fas fa-print mr-1"></i> CETAK SLIP
                                </button>
                            </div>
                        </div>
                    </div>
                )}
        </>
    );
}
