import React from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Link, usePage } from '@inertiajs/react'; // <-- 1. Tambahkan usePage

export default function AuthLayout({ children }) {
    // 2. Ambil data komisariatProfile secara global
    const { komisariatProfile } = usePage().props;

    // 3. Siapkan URL gambar dengan fallback
    const backgroundImageUrl = komisariatProfile?.banner_url || '/images/auth-bg.jpg';
    const logoUrl = komisariatProfile?.logo_url || null;
    const siteName = komisariatProfile?.nama_komisariat || 'Sistem Informasi Sahabat Sunan Kudus';
    const jargon = komisariatProfile?.jargon || 'Dzikir, Fikir, dan Amal Shaleh';

    return (
        <div className="min-h-screen flex flex-wrap">
            {/* Kolom Kiri - Branding Dinamis */}
            <div
                className="hidden lg:flex w-1/2 items-center justify-center bg-indigo-800 bg-cover bg-center relative"
                // 4. Gunakan URL dinamis untuk background
                style={{ backgroundImage: `url(${backgroundImageUrl})` }}
            >
                <div className="absolute inset-0 bg-indigo-900 opacity-60"></div>
                <div className="relative z-10 text-center text-white p-12">
                    <Link href="/">
                        {/* 5. Tampilkan logo komisariat jika ada, jika tidak, logo default */}
                        {logoUrl ? (
                            <img src={logoUrl} alt="Logo Komisariat" className="w-24 h-24 mx-auto mb-6 object-contain" />
                        ) : (
                            <ApplicationLogo className="w-24 h-24 mx-auto mb-6 fill-current" />
                        )}
                    </Link>
                    <h1 className="text-4xl font-extrabold tracking-tight">
                        {siteName}
                    </h1>
                    <p className="mt-4 text-lg text-indigo-200 italic">
                        "{jargon}"
                    </p>
                </div>
            </div>

            {/* Kolom Kanan - Form */}
            <div className="w-full lg:w-1/2 flex items-center justify-center bg-gray-100 dark:bg-gray-900 p-6">
                <div className="w-full max-w-md">
                    {children}
                </div>
            </div>
        </div>
    );
}
