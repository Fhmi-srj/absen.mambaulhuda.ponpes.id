import React, { useState, useEffect } from 'react';
import Swal from 'sweetalert2';

export default function AdminPengaturan() {
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [settings, setSettings] = useState({
        app_name: '',
        school_name: '',
        school_address: '',
        school_phone: '',
        wa_api_url: '',
        wa_api_key: '',
        wa_sender: '',
        latitude: '',
        longitude: '',
        radius_meters: ''
    });

    useEffect(() => {
        document.title = 'Pengaturan - Admin';
        fetchSettings();
    }, []);

    const fetchSettings = async () => {
        setIsLoading(true);
        try {
            const response = await fetch('/admin/pengaturan', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            setSettings(data.settings);
        } catch (error) {
            console.error('Error fetching settings:', error);
            Swal.fire('Error', 'Gagal mengambil pengaturan', 'error');
        } finally {
            setIsLoading(false);
        }
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setSettings(prev => ({ ...prev, [name]: value }));
    };

    const handleSubmit = async (e) => {
        e.preventDefault();
        setIsSaving(true);

        try {
            const response = await fetch('/admin/pengaturan/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                },
                body: JSON.stringify(settings)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire({
                    title: 'Berhasil',
                    text: result.message || 'Pengaturan berhasil disimpan',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire('Gagal', result.message || 'Terjadi kesalahan saat menyimpan', 'error');
            }
        } catch (error) {
            console.error('Error saving settings:', error);
            Swal.fire('Error', 'Terjadi kesalahan sistem', 'error');
        } finally {
            setIsSaving(false);
        }
    };

    if (isLoading) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
            </div>
        );
    }

    return (
        <div className="pb-24 max-w-4xl mx-auto">
            <div className="flex items-center gap-3 mb-8">
                <div className="bg-gray-600 w-12 h-12 rounded-2xl flex items-center justify-center text-white shadow-lg">
                    <i className="fas fa-cog text-xl"></i>
                </div>
                <div>
                    <h4 className="font-black text-gray-800 mb-0">Pengaturan Sistem</h4>
                    <p className="text-sm text-gray-500 font-medium">Konfigurasi parameter aplikasi dan gateway</p>
                </div>
            </div>

            <form onSubmit={handleSubmit} className="space-y-6">
                {/* General Info */}
                <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                            <i className="fas fa-info-circle"></i>
                        </div>
                        <h6 className="font-black text-gray-700 mb-0 uppercase tracking-wider text-sm">Informasi Umum</h6>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Aplikasi</label>
                            <input
                                type="text"
                                name="app_name"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.app_name}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nama Lembaga</label>
                            <input
                                type="text"
                                name="school_name"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.school_name}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div className="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div className="md:col-span-2">
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Alamat</label>
                                <input
                                    type="text"
                                    name="school_address"
                                    className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                    value={settings.school_address}
                                    onChange={handleInputChange}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Telepon</label>
                                <input
                                    type="text"
                                    name="school_phone"
                                    className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                    value={settings.school_phone}
                                    onChange={handleInputChange}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* WhatsApp Settings */}
                <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-8 h-8 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-500">
                            <i className="fab fa-whatsapp text-lg"></i>
                        </div>
                        <h6 className="font-black text-gray-700 mb-0 uppercase tracking-wider text-sm">WhatsApp Gateway</h6>
                    </div>

                    <div className="space-y-6">
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">API URL</label>
                            <input
                                type="url"
                                name="wa_api_url"
                                placeholder="http://server.com/send-message"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.wa_api_url}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">API Key</label>
                                <input
                                    type="text"
                                    name="wa_api_key"
                                    className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                    value={settings.wa_api_key}
                                    onChange={handleInputChange}
                                />
                            </div>
                            <div>
                                <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Nomor Pengirim</label>
                                <input
                                    type="text"
                                    name="wa_sender"
                                    placeholder="628123xxx"
                                    className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                    value={settings.wa_sender}
                                    onChange={handleInputChange}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Location Settings */}
                <div className="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-8 h-8 rounded-lg bg-rose-50 flex items-center justify-center text-rose-500">
                            <i className="fas fa-map-marker-alt"></i>
                        </div>
                        <h6 className="font-black text-gray-700 mb-0 uppercase tracking-wider text-sm">Lokasi Absensi</h6>
                    </div>

                    <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Latitude</label>
                            <input
                                type="text"
                                name="latitude"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.latitude}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Longitude</label>
                            <input
                                type="text"
                                name="longitude"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.longitude}
                                onChange={handleInputChange}
                            />
                        </div>
                        <div>
                            <label className="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2 ml-1">Radius (Meter)</label>
                            <input
                                type="number"
                                name="radius_meters"
                                className="w-full bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 focus:border-blue-500 focus:ring-0 transition-all font-medium"
                                value={settings.radius_meters}
                                onChange={handleInputChange}
                            />
                        </div>
                    </div>
                    <p className="text-[10px] text-gray-400 mt-4 leading-relaxed bg-gray-50 p-3 rounded-xl border border-dashed border-gray-200">
                        <i className="fas fa-lightbulb text-amber-500 mr-1"></i>
                        Radius menentukan jarak maksimal santri diperbolehkan melakukan absensi dari titik koordinat pusat (Latitude/Longitude).
                    </p>
                </div>

                <div className="flex justify-end pt-4">
                    <button
                        type="submit"
                        disabled={isSaving}
                        className="w-full md:w-auto px-12 py-4 bg-gradient-to-r from-gray-800 to-gray-700 text-white rounded-2xl font-black shadow-xl shadow-gray-200 hover:shadow-gray-300 active:scale-95 transition-all disabled:opacity-50"
                    >
                        {isSaving ? (
                            <><i className="fas fa-spinner fa-spin mr-2"></i>Menyimpan...</>
                        ) : (
                            <><i className="fas fa-save mr-2"></i>Simpan Perubahan</>
                        )}
                    </button>
                </div>
            </form>
        </div>
    );
}
