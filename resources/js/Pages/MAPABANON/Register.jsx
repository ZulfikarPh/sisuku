// resources/js/Pages/MAPABA/Register.jsx

import React, { useState } from 'react';
import { usePage, router } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'; // Sesuaikan path jika berbeda
import GuestLayout from '@/Layouts/GuestLayout'; // Sesuaikan path jika berbeda

function Register({ auth, rayons, prodis, errors: inertiaErrors }) {
    // auth prop untuk layout, rayons & prodis untuk data, inertiaErrors untuk validasi Laravel
    // usePage().props juga bisa diakses langsung untuk flash messages

    const [formData, setFormData] = useState({
        nama_lengkap: '',
        nim: '',
        email: '',
        no_hp: '',
        rayon_id: '',   // Ini harus jadi ID
        prodi_id: '',   // Ini harus jadi ID
        ttl: '',
        jenis_kelamin: '', // Enum: 'Laki-Laki' atau 'Perempuan'
        alamat: '',
        // tahun_mapaba dan minat_dan_bakat tidak ada di form karena diisi otomatis/dihapus
        bukti_follow: null, // Objek File
        foto_ktm: null,     // Objek File
        bukti_pembayaran: null, // Objek File
    });

    const [loading, setLoading] = useState(false);
    const [submitError, setSubmitError] = useState(null); // Error umum untuk proses submit
    const [successMessage, setSuccessMessage] = useState(null); // Pesan sukses umum

    // Mengambil flash messages dan validation errors dari Inertia props
    const { flash } = usePage().props;
    const formErrors = inertiaErrors || {}; // Validation errors dari Laravel

    // Handler untuk perubahan input form
    const handleChange = (e) => {
        const { name, value, type, files } = e.target;
        if (type === 'file') {
            setFormData({ ...formData, [name]: files[0] });
        } else {
            setFormData({ ...formData, [name]: value });
        }
        // Hapus error spesifik dari state error agar pesan error hilang saat field diubah
        if (formErrors[name]) {
            delete formErrors[name]; // Ini hanya menghapus dari objek lokal, Inertia akan tetap punya jika belum refresh
        }
        setSubmitError(null); // Clear submit error on change
        setSuccessMessage(null); // Clear success message on change
    };

    // Handler untuk submit form
    const handleSubmit = async (e) => {
        e.preventDefault();
        setLoading(true);
        setSubmitError(null);
        setSuccessMessage(null);

        // Menggunakan Inertia.js router.post untuk mengirim form
        // Inertia secara otomatis menangani FormData untuk upload file
        router.post('/pendaftaran-mapaba', formData, {
            onStart: () => setLoading(true), // Callback saat pengiriman dimulai
            onSuccess: (page) => { // Callback saat sukses
                setSuccessMessage(page.props.flash?.success || 'Pendaftaran berhasil! Silakan tunggu verifikasi.');
                // Reset form setelah sukses
                setFormData({
                    nama_lengkap: '', nim: '', email: '', no_hp: '', rayon_id: '', prodi_id: '',
                    ttl: '', jenis_kelamin: '', alamat: '',
                    bukti_follow: null, foto_ktm: null, bukti_pembayaran: null,
                });
                // Clear file input display secara manual
                document.getElementById('bukti_follow').value = '';
                document.getElementById('foto_ktm').value = '';
                document.getElementById('bukti_pembayaran').value = '';
            },
            onError: (errors) => { // Callback saat ada error dari server (termasuk validasi)
                setSubmitError('Terjadi kesalahan saat pendaftaran. Harap periksa input Anda.');
                // Inertia secara otomatis mengisi usePage().props.errors
                console.error('Inertia Validation Errors:', errors);
            },
            onFinish: () => setLoading(false), // Callback setelah pengiriman selesai (baik sukses/gagal)
            forceFormData: true, // Penting: Pastikan ini dikirim sebagai FormData untuk upload file
        });
    };

    // Tentukan layout yang akan digunakan (publik atau terautentikasi)
    const LayoutComponent = auth?.user ? AuthenticatedLayout : GuestLayout;

    return (
        <LayoutComponent user={auth?.user} header={auth?.user ? <h2 className="font-semibold text-xl text-gray-800 leading-tight">Pendaftaran MAPABA</h2> : null}>
            <div className="py-12">
                <div className="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            <h2 className="text-2xl font-bold mb-6 text-center">Form Pendaftaran MAPABA</h2>

                            {/* Menampilkan pesan error umum dari submit */}
                            {submitError && (
                                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong className="font-bold">Error!</strong>
                                    <span className="block sm:inline"> {submitError}</span>
                                </div>
                            )}

                            {/* Menampilkan error validasi dari Laravel (via Inertia props) */}
                            {Object.keys(formErrors).length > 0 && (
                                <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong className="font-bold">Validasi Gagal!</strong>
                                    <ul className="mt-2 list-disc list-inside">
                                        {Object.values(formErrors).map((error, index) => (
                                            <li key={index}>{error}</li>
                                        ))}
                                    </ul>
                                </div>
                            )}

                            {/* Menampilkan pesan sukses dari Laravel (via flash message) */}
                            {successMessage && (
                                <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong className="font-bold">Berhasil!</strong>
                                    <span className="block sm:inline"> {successMessage}</span>
                                </div>
                            )}

                            {/* Form Pendaftaran */}
                            <form onSubmit={handleSubmit} className="space-y-6">
                                {/* Nama Lengkap */}
                                <div>
                                    <label htmlFor="nama_lengkap" className="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                                    <input
                                        type="text"
                                        name="nama_lengkap"
                                        id="nama_lengkap"
                                        value={formData.nama_lengkap}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    />
                                    {formErrors.nama_lengkap && <p className="text-red-500 text-xs mt-1">{formErrors.nama_lengkap}</p>}
                                </div>

                                {/* NIM */}
                                <div>
                                    <label htmlFor="nim" className="block text-sm font-medium text-gray-700">NIM (Nomor Induk Mahasiswa)</label>
                                    <input
                                        type="text"
                                        name="nim"
                                        id="nim"
                                        value={formData.nim}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    />
                                    {formErrors.nim && <p className="text-red-500 text-xs mt-1">{formErrors.nim}</p>}
                                </div>

                                {/* Email */}
                                <div>
                                    <label htmlFor="email" className="block text-sm font-medium text-gray-700">Email</label>
                                    <input
                                        type="email"
                                        name="email"
                                        id="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    />
                                    {formErrors.email && <p className="text-red-500 text-xs mt-1">{formErrors.email}</p>}
                                </div>

                                {/* No. Telepon */}
                                <div>
                                    <label htmlFor="no_hp" className="block text-sm font-medium text-gray-700">Nomor Telepon/HP</label>
                                    <input
                                        type="tel"
                                        name="no_hp"
                                        id="no_hp"
                                        value={formData.no_hp}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    />
                                    {formErrors.no_hp && <p className="text-red-500 text-xs mt-1">{formErrors.no_hp}</p>}
                                </div>

                                {/* TTL */}
                                <div>
                                    <label htmlFor="ttl" className="block text-sm font-medium text-gray-700">Tempat, Tanggal Lahir</label>
                                    <input
                                        type="text"
                                        name="ttl"
                                        id="ttl"
                                        value={formData.ttl}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    />
                                    {formErrors.ttl && <p className="text-red-500 text-xs mt-1">{formErrors.ttl}</p>}
                                </div>

                                {/* Jenis Kelamin */}
                                <div>
                                    <label htmlFor="jenis_kelamin" className="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                                    <select
                                        name="jenis_kelamin"
                                        id="jenis_kelamin"
                                        value={formData.jenis_kelamin}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                    >
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-Laki">Laki-Laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                    {formErrors.jenis_kelamin && <p className="text-red-500 text-xs mt-1">{formErrors.jenis_kelamin}</p>}
                                </div>

                                {/* Alamat */}
                                <div>
                                    <label htmlFor="alamat" className="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                                    <textarea
                                        name="alamat"
                                        id="alamat"
                                        value={formData.alamat}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        rows="3"
                                    ></textarea>
                                    {formErrors.alamat && <p className="text-red-500 text-xs mt-1">{formErrors.alamat}</p>}
                                </div>

                                {/* Tahun MAPABA - DIHAPUS DARI FORM KARENA OTOMATIS DI BACKEND */}
                                {/* Minat dan Bakat - DIHAPUS DARI FORM */}

                                {/* Rayon PMII */}
                                <div>
                                    <label htmlFor="rayon_id" className="block text-sm font-medium text-gray-700">Rayon PMII</label>
                                    <select
                                        name="rayon_id"
                                        id="rayon_id"
                                        value={formData.rayon_id}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    >
                                        <option value="">Pilih Rayon</option>
                                        {rayons && rayons.map(rayon => (
                                            <option key={rayon.id} value={rayon.id}>
                                                {rayon.name}
                                            </option>
                                        ))}
                                    </select>
                                    {formErrors.rayon_id && <p className="text-red-500 text-xs mt-1">{formErrors.rayon_id}</p>}
                                </div>

                                {/* Program Studi */}
                                <div>
                                    <label htmlFor="prodi_id" className="block text-sm font-medium text-gray-700">Program Studi</label>
                                    <select
                                        name="prodi_id"
                                        id="prodi_id"
                                        value={formData.prodi_id}
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        required
                                    >
                                        <option value="">Pilih Program Studi</option>
                                        {prodis && prodis.map(prodi => (
                                            <option key={prodi.id} value={prodi.id}>
                                                {prodi.nama_prodi}
                                            </option>
                                        ))}
                                    </select>
                                    {formErrors.prodi_id && <p className="text-red-500 text-xs mt-1">{formErrors.prodi_id}</p>}
                                </div>

                                {/* Bukti File Uploads - DIKEMBALIKAN */}
                                <div>
                                    <label htmlFor="bukti_follow" className="block text-sm font-medium text-gray-700">Bukti Follow Akun (Foto/PDF)</label>
                                    <input
                                        type="file"
                                        name="bukti_follow"
                                        id="bukti_follow"
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        accept="image/*,application/pdf"
                                        required
                                    />
                                    {formErrors.bukti_follow && <p className="text-red-500 text-xs mt-1">{formErrors.bukti_follow}</p>}
                                </div>

                                <div>
                                    <label htmlFor="foto_ktm" className="block text-sm font-medium text-gray-700">Foto KTM (Foto)</label>
                                    <input
                                        type="file"
                                        name="foto_ktm"
                                        id="foto_ktm"
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        accept="image/*"
                                        required
                                    />
                                    {formErrors.foto_ktm && <p className="text-red-500 text-xs mt-1">{formErrors.foto_ktm}</p>}
                                </div>

                                <div>
                                    <label htmlFor="bukti_pembayaran" className="block text-sm font-medium text-gray-700">Bukti Pembayaran (Foto/PDF)</label>
                                    <input
                                        type="file"
                                        name="bukti_pembayaran"
                                        id="bukti_pembayaran"
                                        onChange={handleChange}
                                        className="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                        accept="image/*,application/pdf"
                                        required
                                    />
                                    {formErrors.bukti_pembayaran && <p className="text-red-500 text-xs mt-1">{formErrors.bukti_pembayaran}</p>}
                                </div>

                                <div className="flex items-center justify-end">
                                    <button
                                        type="submit"
                                        className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                        disabled={loading}
                                    >
                                        {loading ? 'Mengirim...' : 'Daftar MAPABA'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </LayoutComponent>
    );
}

export default Register;
