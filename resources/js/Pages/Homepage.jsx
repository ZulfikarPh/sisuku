import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';
import { useInView } from 'react-intersection-observer';

// Asumsikan komponen ini sudah dipindah ke filenya masing-masing
import ArticleCard from '@/Components/ArticleCard';
import TestimonialCard from '@/Components/TestimonialCard';
import StatCard from '@/Components/StatCard';

// --- SUB-KOMPONEN KHUSUS UNTUK HALAMAN INI ---

// Komponen untuk judul setiap seksi dengan gaya yang konsisten
const SectionTitle = ({ children }) => (
    <h2 className="text-3xl md:text-4xl font-extrabold text-center text-gray-900 dark:text-white tracking-tight">
        {children}
    </h2>
);

// Komponen untuk deskripsi di bawah judul seksi
const SectionDescription = ({ children }) => (
    <p className="mt-4 max-w-3xl mx-auto text-center text-lg text-gray-600 dark:text-gray-400">
        {children}
    </p>
);

// Komponen untuk pemisah antar seksi dengan bentuk gelombang
const SectionSeparator = ({ className = '' }) => (
    <div className={`absolute bottom-0 left-0 w-full overflow-hidden leading-[0] ${className}`}>
        <svg className="relative block h-[60px] md:h-[100px] w-full" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-82.39,16.72-168.36,57.09-250.45,89.63-39.2,15.72-78.28,31.3-117.32,46.39L0,120H1200V0L985.66,92.83Z" className="fill-current text-gray-50 dark:text-gray-900"></path>
        </svg>
    </div>
);


// --- KOMPONEN UTAMA HOMEPAGE ---

export default function Homepage({ komisariatProfile, memberStats, articles, testimonials }) {
    // Fungsi untuk animasi saat elemen masuk ke viewport
    const { ref: aboutRef, inView: aboutInView } = useInView({ triggerOnce: true, threshold: 0.2 });
    const { ref: statsRef, inView: statsInView } = useInView({ triggerOnce: true, threshold: 0.2 });
    const { ref: articlesRef, inView: articlesInView } = useInView({ triggerOnce: true, threshold: 0.2 });
    const { ref: ctaRef, inView: ctaInView } = useInView({ triggerOnce: true, threshold: 0.2 });
    const { ref: testimonyRef, inView: testimonyInView } = useInView({ triggerOnce: true, threshold: 0.2 });

    const getStat = (categoryName) => memberStats?.find(s => s.nama_kategori === categoryName)?.total || 0;

    // Fallback data jika komisariatProfile kosong, agar tidak error
    const profile = komisariatProfile || {};

    return (
        <GuestLayout>
            <Head title={`Selamat Datang di ${profile.nama_komisariat || 'PMII'}`} />

            {/* 1. HERO SECTION */}
            <section className="relative h-[90vh] min-h-[600px] text-white flex items-end justify-center text-center bg-gray-900">
                <div className="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent z-10"></div>
                <img
                    src={profile.banner_url || '/images/default-banner.jpg'}
                    alt="Banner Komisariat"
                    className="absolute inset-0 w-full h-full object-cover animate-zoom-in"
                />
                <div className="relative z-20 px-4 pb-20 md:pb-24 animate-fade-in-up">
                    <img src={profile.logo_url || '/images/logo-pmii.png'} alt="Logo" className="w-24 h-24 md:w-32 md:h-32 mx-auto mb-6 drop-shadow-lg"/>
                    <h1 className="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight" style={{textShadow: '2px 2px 8px rgba(0,0,0,0.7)'}}>
                        {profile.nama_komisariat || 'PMII Sunan Kudus'}
                    </h1>
                    <p className="mt-4 text-xl md:text-2xl text-gray-200" style={{textShadow: '1px 1px 4px rgba(0,0,0,0.7)'}}>
                        {profile.jargon || 'Dzikir, Fikir, dan Amal Shaleh'}
                    </p>
                </div>
            </section>

            {/* 2. TENTANG KAMI / SEJARAH SINGKAT */}
            {profile.sejarah_singkat && (
                <section ref={aboutRef} className={`py-20 bg-white dark:bg-gray-800 transition-all duration-700 ease-out ${aboutInView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
                    <div className="container mx-auto px-6 max-w-4xl">
                        <SectionTitle>Sahabat Pergerakan</SectionTitle>
                        <div className="mt-8 prose lg:prose-lg max-w-none dark:prose-invert text-gray-700 dark:text-gray-300 text-justify"
                             dangerouslySetInnerHTML={{ __html: profile.sejarah_singkat }} />
                    </div>
                </section>
            )}

            {/* 3. STATISTIK ANGGOTA */}
            <section ref={statsRef} className={`relative pt-20 pb-24 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white transition-opacity duration-1000 ${statsInView ? 'opacity-100' : 'opacity-0'}`}>
                <SectionSeparator className="text-white dark:text-gray-800" />
                <div className="container mx-auto px-6 text-center relative z-10">
                    <SectionTitle>Kekuatan Kaderisasi</SectionTitle>
                    <SectionDescription>Dari anggota baru hingga alumni, inilah pilar kekuatan pergerakan kita.</SectionDescription>
                    <div className="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                        <StatCard value={getStat('Anggota')} label="Anggota Aktif" />
                        <StatCard value={getStat('Kader')} label="Kader Mujahid" />
                        <StatCard value={getStat('Alumni')} label="Alumni Hebat" />
                    </div>
                </div>
                <SectionSeparator className="transform rotate-180 top-0 bottom-auto text-white dark:text-gray-800"/>
            </section>

            {/* 4. ARTIKEL TERBARU */}
            {articles && articles.length > 0 && (
                <section ref={articlesRef} className={`py-20 bg-white dark:bg-gray-800 transition-all duration-700 ease-out ${articlesInView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
                    <div className="container mx-auto px-6">
                        <SectionTitle>Wacana & Informasi</SectionTitle>
                        <SectionDescription>Gagasan, berita, dan informasi terbaru dari kader pergerakan.</SectionDescription>
                        <div className="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {articles.map((article) => <ArticleCard key={article.id} article={article} />)}
                        </div>
                        <div className="text-center mt-12">
                            <Link href="/artikel" className="inline-block bg-indigo-600 text-white font-bold text-sm px-8 py-3 rounded-lg hover:bg-indigo-700 transition-all duration-300 transform hover:scale-105 shadow-lg">
                                Lihat Semua Artikel
                            </Link>
                        </div>
                    </div>
                </section>
            )}

            {/* 5. CALL TO ACTION (CTA)
            <section ref={ctaRef} className={`bg-indigo-700 text-white transition-opacity duration-1000 ${ctaInView ? 'opacity-100' : 'opacity-0'}`}>
                <div className="container mx-auto px-6 text-center py-20">
                    <h2 className="text-3xl font-extrabold tracking-tight">Siap Menjadi Bagian dari Perubahan?</h2>
                    <p className="mt-4 max-w-2xl mx-auto text-lg text-indigo-100">Bergabunglah bersama kami di Masa Penerimaan Anggota Baru (MAPABA).</p>
                    <div className="mt-8">
                        <Link href={route('pendaftaran.mapaba.create')} className="inline-block bg-white text-indigo-600 font-bold text-sm px-8 py-3 rounded-lg hover:bg-gray-200 transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Daftar MAPABA Sekarang
                        </Link>
                    </div>
                </div>
            </section> */}

            {/* 6. TESTIMONI */}
            {testimonials && testimonials.length > 0 && (
                <section ref={testimonyRef} className={`relative py-20 bg-white dark:bg-gray-800 transition-all duration-700 ease-out ${testimonyInView ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-10'}`}>
                    <div className="container mx-auto px-6 relative z-10">
                        <SectionTitle>Kata Mereka</SectionTitle>
                        <SectionDescription>Pesan dan kesan dari para tokoh, senior, dan alumni yang menginspirasi.</SectionDescription>
                        <div className="mt-12 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                            {testimonials.map(testimoni => <TestimonialCard key={testimoni.id} testimoni={testimoni} />)}
                        </div>
                    </div>
                </section>
            )}

        </GuestLayout>
    );
}
