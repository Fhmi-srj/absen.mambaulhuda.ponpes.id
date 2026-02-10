import React, { useState, useEffect } from 'react';
import { useAuth } from '../contexts/AuthContext';
import { PageSkeleton } from '../Components/Skeleton';

export default function Profil() {
    const { user: authUser, setUser: setAuthUser } = useAuth();
    const [loading, setLoading] = useState(true);
    const [userData, setUserData] = useState(null);
    const [roleLabels, setRoleLabels] = useState({});
    const [formData, setFormData] = useState({
        name: '',
        email: '',
        phone: '',
        address: '',
        current_password: '',
        new_password: '',
        new_password_confirmation: '',
    });
    const [message, setMessage] = useState({ type: '', text: '' });
    const [saving, setSaving] = useState(false);

    useEffect(() => {
        document.title = 'Profil - Aktivitas Santri';
        fetchUserData();
    }, []);

    const fetchUserData = async () => {
        try {
            const response = await fetch('/api/profil', {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const data = await response.json();
                setUserData(data.user);
                setRoleLabels(data.roleLabels || {});
                setFormData(prev => ({
                    ...prev,
                    name: data.user?.name || '',
                    email: data.user?.email || '',
                    phone: data.user?.phone || '',
                    address: data.user?.address || '',
                }));
            }
        } catch (error) {
            console.error('Error fetching user data:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleUpdateData = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage({ type: '', text: '' });

        try {
            const response = await fetch('/profil/update-data', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'include',
                body: JSON.stringify({
                    name: formData.name,
                    email: formData.email,
                    phone: formData.phone,
                }),
            });

            const data = await response.json();
            if (response.ok) {
                setMessage({ type: 'success', text: data.message || 'Profil berhasil diperbarui' });
            } else {
                setMessage({ type: 'error', text: data.message || 'Gagal memperbarui profil' });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        } finally {
            setSaving(false);
        }
    };

    const handleUpdatePassword = async (e) => {
        e.preventDefault();
        setSaving(true);
        setMessage({ type: '', text: '' });

        if (formData.new_password !== formData.new_password_confirmation) {
            setMessage({ type: 'error', text: 'Konfirmasi password tidak cocok' });
            setSaving(false);
            return;
        }

        try {
            const response = await fetch('/profil/update-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                credentials: 'include',
                body: JSON.stringify({
                    current_password: formData.current_password,
                    new_password: formData.new_password,
                    new_password_confirmation: formData.new_password_confirmation,
                }),
            });

            const data = await response.json();
            if (response.ok) {
                setMessage({ type: 'success', text: data.message || 'Password berhasil diperbarui' });
                setFormData({
                    ...formData,
                    current_password: '',
                    new_password: '',
                    new_password_confirmation: '',
                });
            } else {
                setMessage({ type: 'error', text: data.message || 'Gagal memperbarui password' });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        } finally {
            setSaving(false);
        }
    };

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>


            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                </div>
            )}

            <div className="grid lg:grid-cols-3 gap-6">
                {/* Profile Card */}
                <div className="bg-white rounded-xl shadow-sm p-6 text-center">
                    <div className="w-24 h-24 mx-auto mb-4 relative drop-shadow-md">
                        {userData?.foto && userData.foto !== 'profile.jpg' ? (
                            <div className="w-full h-full rounded-full overflow-hidden border-4 border-white">
                                <img
                                    src={`/uploads/profiles/${userData.foto}`}
                                    alt="Profile"
                                    className="w-full h-full object-cover"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                        e.target.nextSibling.style.display = 'flex';
                                    }}
                                />
                                <div
                                    className="w-full h-full bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center text-white text-3xl font-bold"
                                    style={{ display: 'none' }}
                                >
                                    {userData?.name?.charAt(0)?.toUpperCase() || 'U'}
                                </div>
                            </div>
                        ) : (
                            <div className="w-full h-full bg-gradient-to-br from-blue-600 to-blue-400 rounded-full flex items-center justify-center text-white text-3xl font-bold border-4 border-white">
                                {userData?.name?.charAt(0)?.toUpperCase() || 'U'}
                            </div>
                        )}
                    </div>
                    <h3 className="text-xl font-bold text-gray-800 mb-1">{userData?.name}</h3>
                    <p className="text-gray-500 mb-2">{userData?.email}</p>
                    <span className="inline-block px-3 py-1 bg-blue-100 text-blue-600 text-sm font-semibold rounded-full">
                        {roleLabels[userData?.role] || userData?.role}
                    </span>
                </div>

                {/* Edit Form */}
                <div className="lg:col-span-2 space-y-6">
                    {/* Update Data Form */}
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Edit Profil</h3>
                        <form onSubmit={handleUpdateData} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                                <input
                                    type="text"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <input
                                    type="email"
                                    value={formData.email}
                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">No. Telepon</label>
                                <input
                                    type="text"
                                    value={formData.phone}
                                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                    placeholder="08xxxxxxxxxx"
                                />
                            </div>
                            <button
                                type="submit"
                                disabled={saving}
                                className="px-6 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors disabled:opacity-50"
                            >
                                <i className="fas fa-save mr-2"></i>
                                {saving ? 'Menyimpan...' : 'Simpan Profil'}
                            </button>
                        </form>
                    </div>

                    {/* Update Password Form */}
                    <div className="bg-white rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-gray-800 mb-4">Ubah Password</h3>
                        <form onSubmit={handleUpdatePassword} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Password Saat Ini</label>
                                <input
                                    type="password"
                                    value={formData.current_password}
                                    onChange={(e) => setFormData({ ...formData, current_password: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                />
                            </div>
                            <div className="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label className="block text-sm font-medium text-gray-600 mb-1">Password Baru</label>
                                    <input
                                        type="password"
                                        value={formData.new_password}
                                        onChange={(e) => setFormData({ ...formData, new_password: e.target.value })}
                                        className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                                    <input
                                        type="password"
                                        value={formData.new_password_confirmation}
                                        onChange={(e) => setFormData({ ...formData, new_password_confirmation: e.target.value })}
                                        className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-100 outline-none"
                                    />
                                </div>
                            </div>
                            <button
                                type="submit"
                                disabled={saving}
                                className="px-6 py-2 bg-amber-500 text-white rounded-lg font-semibold hover:bg-amber-600 transition-colors disabled:opacity-50"
                            >
                                <i className="fas fa-key mr-2"></i>
                                {saving ? 'Menyimpan...' : 'Ubah Password'}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </>
    );
}
