import React, { useState, useEffect } from 'react';
import { useAuth } from '../../contexts/AuthContext';
import { PageSkeleton } from '../../Components/Skeleton';
import Swal from 'sweetalert2';
import Modal from '../../Components/Modal';

export default function Pengguna() {
    const { user: authUser } = useAuth();
    const [loading, setLoading] = useState(true);
    const [users, setUsers] = useState([]);
    const [roles, setRoles] = useState([]);
    const [roleLabels, setRoleLabels] = useState({});
    const [filterRole, setFilterRole] = useState('');
    const [modalOpen, setModalOpen] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState({ type: '', text: '' });
    const [formData, setFormData] = useState({
        id: '',
        name: '',
        email: '',
        password: '',
        phone: '',
        address: '',
        role: 'karyawan',
    });

    useEffect(() => {
        document.title = 'Manajemen User - Aktivitas Santri';
        fetchUsers();
    }, [filterRole]);

    const fetchUsers = async () => {
        try {
            const url = filterRole
                ? `/admin/pengguna?role=${filterRole}`
                : '/admin/pengguna';

            const response = await fetch(url, {
                credentials: 'include',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.ok) {
                const data = await response.json();
                setUsers(data.users || []);
                setRoles(data.roles || []);
                setRoleLabels(data.roleLabels || {});
            }
        } catch (error) {
            console.error('Error fetching users:', error);
        } finally {
            setLoading(false);
        }
    };

    const resetForm = () => {
        setFormData({
            id: '',
            name: '',
            email: '',
            password: '',
            phone: '',
            address: '',
            role: 'karyawan',
        });
        setIsEditing(false);
    };

    const openAddModal = () => {
        resetForm();
        setModalOpen(true);
    };

    const openEditModal = (user) => {
        setFormData({
            id: user.id,
            name: user.name || '',
            email: user.email || '',
            password: '',
            phone: user.phone || '',
            address: user.address || '',
            role: user.role || 'karyawan',
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
            form.append('name', formData.name);
            form.append('email', formData.email);
            if (formData.password) form.append('password', formData.password);
            form.append('phone', formData.phone);
            form.append('address', formData.address);
            form.append('role', formData.role);

            const url = isEditing
                ? `/api/admin/pengguna/${formData.id}`
                : '/api/admin/pengguna';

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
                fetchUsers();
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
            title: 'Hapus User?',
            text: `Yakin ingin menghapus user "${name}"? User akan dipindahkan ke trash.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/api/admin/pengguna/${id}`, {
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
                fetchUsers();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        }
    };

    const handleResetDevice = async (id) => {
        const result = await Swal.fire({
            title: 'Reset Device?',
            text: 'Yakin ingin reset device? User harus login ulang di device baru.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f59e0b',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(`/api/admin/pengguna/${id}/reset-device`, {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                setMessage({ type: 'success', text: data.message });
                fetchUsers();
            } else {
                setMessage({ type: 'error', text: data.message });
            }
        } catch (error) {
            setMessage({ type: 'error', text: 'Terjadi kesalahan' });
        }
    };

    if (loading) {
        return <PageSkeleton />;
    }

    return (
        <>
            {/* Header */}
            <div className="flex flex-wrap justify-between items-center gap-4 mb-6">

                <button
                    onClick={openAddModal}
                    className="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors"
                >
                    <i className="fas fa-plus mr-2"></i>Tambah User
                </button>
            </div>

            {/* Message */}
            {message.text && (
                <div className={`mb-4 p-4 rounded-lg ${message.type === 'success' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}`}>
                    {message.text}
                    <button onClick={() => setMessage({ type: '', text: '' })} className="float-right font-bold">&times;</button>
                </div>
            )}

            {/* Card */}
            <div className="bg-white rounded-xl shadow-sm overflow-hidden">
                {/* Filter */}
                <div className="p-4 border-b border-gray-100">
                    <select
                        value={filterRole}
                        onChange={(e) => setFilterRole(e.target.value)}
                        className="px-3 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                    >
                        <option value="">Semua Role</option>
                        {roles.map((r) => (
                            <option key={r} value={r}>{roleLabels[r] || r}</option>
                        ))}
                    </select>
                </div>

                {/* Table */}
                <div className="overflow-x-auto">
                    <table className="w-full min-w-[700px]">
                        <thead className="bg-gray-50">
                            <tr>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Nama</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Email</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Role</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Phone</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Device</th>
                                <th className="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-gray-100">
                            {users.length === 0 ? (
                                <tr>
                                    <td colSpan="6" className="px-5 py-8 text-center text-gray-400">
                                        Tidak ada data user
                                    </td>
                                </tr>
                            ) : (
                                users.map((u) => (
                                    <tr key={u.id} className="hover:bg-gray-50">
                                        <td className="px-5 py-4 font-semibold text-gray-800">{u.name}</td>
                                        <td className="px-5 py-4 text-gray-600">{u.email}</td>
                                        <td className="px-5 py-4">
                                            <span className={`px-2 py-1 text-xs font-semibold rounded-full ${u.role === 'admin' ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-600'}`}>
                                                {roleLabels[u.role] || u.role}
                                            </span>
                                        </td>
                                        <td className="px-5 py-4 text-gray-600">{u.phone || '-'}</td>
                                        <td className="px-5 py-4">
                                            <i className={`fas fa-mobile-alt ${u.device_count > 0 ? 'text-green-500' : 'text-gray-300'}`}></i>
                                        </td>
                                        <td className="px-5 py-4">
                                            <div className="flex gap-2">
                                                <button
                                                    onClick={() => openEditModal(u)}
                                                    className="p-2 text-amber-500 hover:bg-amber-50 rounded transition-colors"
                                                    title="Edit"
                                                >
                                                    <i className="fas fa-edit"></i>
                                                </button>
                                                {u.device_count > 0 && (
                                                    <button
                                                        onClick={() => handleResetDevice(u.id)}
                                                        className="p-2 text-blue-500 hover:bg-blue-50 rounded transition-colors"
                                                        title="Reset Device"
                                                    >
                                                        <i className="fas fa-sync"></i>
                                                    </button>
                                                )}
                                                {u.id !== authUser?.id && (
                                                    <button
                                                        onClick={() => handleDelete(u.id, u.name)}
                                                        className="p-2 text-red-500 hover:bg-red-50 rounded transition-colors"
                                                        title="Hapus"
                                                    >
                                                        <i className="fas fa-trash"></i>
                                                    </button>
                                                )}
                                            </div>
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            {/* Modal */}
            <Modal isOpen={modalOpen} onClose={() => setModalOpen(false)} className="max-w-md">
                <div className="relative bg-white rounded-2xl shadow-xl w-full overflow-hidden">
                    <form onSubmit={handleSubmit}>
                        {/* Header */}
                        <div className="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h5 className="font-bold text-gray-800">
                                {isEditing ? 'Edit User' : 'Tambah User'}
                            </h5>
                            <button type="button" onClick={() => setModalOpen(false)} className="text-gray-400 hover:text-gray-600">
                                <i className="fas fa-times"></i>
                            </button>
                        </div>

                        {/* Body */}
                        <div className="p-6 space-y-4 max-h-[60vh] overflow-y-auto">
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Nama</label>
                                <input
                                    type="text"
                                    value={formData.name}
                                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <input
                                    type="email"
                                    value={formData.email}
                                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                    required
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">
                                    Password <small className="text-gray-400">{isEditing && '(kosongkan jika tidak ubah)'}</small>
                                </label>
                                <input
                                    type="password"
                                    value={formData.password}
                                    onChange={(e) => setFormData({ ...formData, password: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                    required={!isEditing}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">No. HP</label>
                                <input
                                    type="text"
                                    value={formData.phone}
                                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                <textarea
                                    value={formData.address}
                                    onChange={(e) => setFormData({ ...formData, address: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                    rows="2"
                                ></textarea>
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-600 mb-1">Role</label>
                                <select
                                    value={formData.role}
                                    onChange={(e) => setFormData({ ...formData, role: e.target.value })}
                                    className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:border-blue-500 outline-none"
                                    required
                                >
                                    {roles.map((r) => (
                                        <option key={r} value={r}>{roleLabels[r] || r}</option>
                                    ))}
                                </select>
                            </div>
                        </div>

                        {/* Footer */}
                        <div className="px-6 py-4 border-t border-gray-100 flex gap-3">
                            <button
                                type="button"
                                onClick={() => setModalOpen(false)}
                                className="flex-1 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition-colors"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                disabled={saving}
                                className="flex-1 py-2 bg-blue-500 text-white rounded-lg font-semibold hover:bg-blue-600 transition-colors disabled:opacity-50"
                            >
                                {saving ? 'Menyimpan...' : 'Simpan'}
                            </button>
                        </div>
                    </form>
                </div>
            </Modal>
        </>
    );
}
