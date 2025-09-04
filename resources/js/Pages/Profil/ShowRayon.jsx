import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';
import { useState } from 'react';
import Pagination from '@/Components/Pagination';
import ArticleCard from '@/Components/ArticleCard';

// --- DEFINISI IKON ---
const InstagramIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163zm0 1.626c-3.141 0-3.48.01-4.697.067-2.61.12-3.844 1.354-3.964 3.964-.057 1.217-.066 1.556-.066 4.697s.01 3.48.066 4.697c.12 2.61 1.354 3.844 3.964 3.964 1.217.057 1.556.066 4.697.066s3.48-.01 4.697-.066c2.61-.12 3.844-1.354 3.964-3.964.057-1.217-.066-1.556-.066-4.697s-.01-3.48-.066-4.697c-.12-2.61-1.354-3.844-3.964-3.964C15.48 3.799 15.141 3.79 12 3.79zm0 8.242a2.828 2.828 0 100 5.656 2.828 2.828 0 000-5.656zm0 7.272a4.444 4.444 0 110-8.888 4.444 4.444 0 010 8.888zM16.892 7.11a1.2 1.2 0 100-2.4 1.2 1.2 0 000 2.4z" /></svg>;
const FacebookIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.59 0 0 .59 0 1.325v21.35C0 23.41.59 24 1.325 24H12.82v-9.29H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.735 0 1.325-.59 1.325-1.325V1.325C24 .59 23.41 0 22.675 0z" /></svg>;
const YouTubeIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 7.243c-.23-.84-.897-1.508-1.737-1.737C18.252 5 12 5 12 5s-6.252 0-7.845.506c-.84.23-1.508.897-1.737 1.737C2 9.07 2 12 2 12s0 2.93.506 4.757c.23.84.897 1.508 1.737 1.737C5.835 19 12 19 12 19s6.252 0 7.845-.506c.84-.23 1.508-.897 1.737-1.737C22 14.93 22 12 22 12s0-2.93-.418-4.757zM9.545 14.545V9.455l4.763 2.545-4.763 2.545z"/></svg>;
const TikTokIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm4.188 6.516c.105.02.21.036.314.052v2.835a4.238 4.238 0 0 1-.803.062c-1.492 0-2.703-1.21-2.703-2.702v-3.23c0-2.022 1.64-3.663 3.664-3.663.513 0 1.004.105 1.45.3V4.99c-.445-.195-.935-.3-1.45-.3-3.037 0-5.504 2.467-5.504 5.502v3.23c0 .852.69 1.543 1.543 1.543.046 0 .092-.002.138-.006.01-.002.02-.002.03-.002h.016c.05-.008.1-.016.15-.025.042-.008.084-.017.126-.027a2.71 2.71 0 0 0 1.91-1.037 2.71 2.71 0 0 0 1.038-1.91v-2.31c.21.144.408.305.59.484a2.704 2.704 0 0 0 1.91 1.037c.04.008.08.016.12.024.05.01.1.02.15.03h.015c.046.004.092.006.138.006.852 0 1.543-.69 1.543-1.543v-3.23c0-.057-.004-.113-.01-.17a.068.068 0 0 1-.003-.016c-.01-.05-.02-.1-.03-.15-.008-.042-.017-.084-.027-.126a2.71 2.71 0 0 0-1.037-1.91 2.71 2.71 0 0 0-1.91-1.038v-2.14c.75.253 1.41.674 1.91 1.25.75.862 1.25 2.013 1.25 3.25 0 2.485-2.015 4.5-4.5 4.5-.472 0-.928-.074-1.352-.213v2.85c.42.135.875.21 1.352.21 3.59 0 6.5-2.91 6.5-6.5s-2.91-6.5-6.5-6.5c-3.59 0-6.5 2.91-6.5 6.5 0 .046.002.092.006.138a.068.068 0 0 1 .002.016c.002.01.002.02.002.03h.001c.008.05.016.1.025.15.008.042.017.084.027.126a2.71 2.71 0 0 0 1.037 1.91 2.71 2.71 0 0 0 1.91 1.038v2.14Z"/></svg>;
const ChartBarIcon = () => <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="w-6 h-6 inline-block mr-2 -ml-1 text-gray-400"><path strokeLinecap="round" strokeLinejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>;

// --- SUB-KOMPONEN ---
const TabButton = ({ children, isActive, onClick }) => (
    <button
        onClick={onClick}
        className={`px-4 py-2 text-sm font-semibold rounded-t-lg transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 ${
            isActive
                ? 'bg-white dark:bg-gray-800 text-indigo-600 border-b-2 border-indigo-500'
                : 'bg-transparent text-gray-500 hover:text-gray-700 dark:hover:text-gray-200'
        }`}
    >
        {children}
    </button>
);

const InfoItem = ({ label, value }) => (
    <div>
        <span className="text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider">{label}</span>
        <p className="text-gray-800 dark:text-gray-100 break-words">{value || 'Belum diisi'}</p>
    </div>
);

const StatItem = ({ label, value, color }) => (
    <div className="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
        <div className="flex items-center">
            <span className={`h-2 w-2 rounded-full mr-3 ${color}`}></span>
            <span className="text-gray-600 dark:text-gray-300 text-sm font-medium">{label}</span>
        </div>
        <div className="text-2xl font-extrabold text-gray-800 dark:text-white">
            {value}
        </div>
    </div>
);

// --- KOMPONEN UTAMA HALAMAN ---
export default function ShowRayon({ rayon, articles, memberStats }) {
    const profile = rayon.profile;
    const [activeTab, setActiveTab] = useState('description');

    const iconMap = {
        instagram: InstagramIcon,
        facebook: FacebookIcon,
        youtube: YouTubeIcon,
        tiktok: TikTokIcon,
    };

    const socialLinks = profile?.social_media
        ? Object.keys(profile.social_media)
            .map(key => ({
                name: key.charAt(0).toUpperCase() + key.slice(1),
                href: profile.social_media[key],
                Icon: iconMap[key.toLowerCase()]
            }))
            .filter(link => link.Icon && link.href)
        : [];

    return (
        <GuestLayout>
            <Head title={`Profil Rayon ${rayon.name}`} />

            <div className="bg-gray-50 dark:bg-gray-900">
                <div className="h-80 bg-gray-800 bg-cover bg-center relative group">
                    <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/50 to-transparent"></div>
                    <img src={profile?.banner_url || 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f'} className="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-80 transition-opacity duration-500" alt="Banner" />
                    <div className="relative h-full flex flex-col justify-center items-center text-center px-4">
    <h1 className="text-2xl md:text-4xl font-extrabold text-white tracking-tight drop-shadow-lg italic leading-tight max-w-5xl">
    {/* Tampilkan Jargon, jika tidak ada, tampilkan Nama Rayon sebagai fallback */}
    {profile?.jargon ? `"${profile.jargon}"` : rayon.name}
</h1>
</div>
                </div>

                <div className="py-12">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {profile ? (
                            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 -mt-48 relative z-10">

                                <aside className="lg:col-span-4 space-y-6">
                                    <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 text-center transition-all duration-300 hover:shadow-indigo-200/50">
                                        <img
                                            src={profile.logo_url || 'https://via.placeholder.com/150'}
                                            alt={`Logo Rayon ${rayon.name}`}
                                            className="w-36 h-36 rounded-full mx-auto object-cover mb-4 border-8 border-white dark:border-gray-800 shadow-lg -mt-24"
                                        />
                                        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">{rayon.name}</h2>
                                    </div>
                                    <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 space-y-4 text-sm">
                                        <h3 className="text-lg font-bold border-b pb-2 mb-4 dark:border-gray-700">Info Detail</h3>
                                        <InfoItem label="Alamat Sekretariat" value={profile.address} />
                                        <InfoItem label="Email Resmi" value={profile.email} />
                                        <InfoItem label="Nomor Telepon" value={profile.phone_number} />
                                    </div>
                                    {socialLinks.length > 0 && (
                                        <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                                            <h3 className="text-lg font-bold border-b pb-2 mb-4 dark:border-gray-700">Media Sosial</h3>
                                            <div className="flex justify-center space-x-4">
                                                {socialLinks.map(({ name, href, Icon }) => (
                                                    <a key={name} href={href} target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors" aria-label={name}><Icon /></a>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </aside>

                                <main className="lg:col-span-8 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl overflow-hidden">
                                    <div className="border-b border-gray-200 dark:border-gray-700">
                                        <nav className="flex flex-wrap space-x-2 px-6 pt-2" aria-label="Tabs">
                                            <TabButton isActive={activeTab === 'description'} onClick={() => setActiveTab('description')}>Tentang</TabButton>
                                            {profile.vision && <TabButton isActive={activeTab === 'vision'} onClick={() => setActiveTab('vision')}>Visi</TabButton>}
                                            {profile.mission && <TabButton isActive={activeTab === 'mission'} onClick={() => setActiveTab('mission')}>Misi</TabButton>}
                                            {profile.history && <TabButton isActive={activeTab === 'history'} onClick={() => setActiveTab('history')}>Sejarah</TabButton>}
                                            {/* [BARU] Tombol tab untuk statistik */}
                                            {memberStats && memberStats.length > 0 && <TabButton isActive={activeTab === 'stats'} onClick={() => setActiveTab('stats')}>Statistik</TabButton>}
                                        </nav>
                                    </div>

                                    {/* [DIUBAH] Layout konten utama menjadi 1 kolom */}
                                    <div className="p-8 min-h-[300px]">
                                        {activeTab === 'description' && (
                                            <article className="prose max-w-none dark:prose-invert" dangerouslySetInnerHTML={{ __html: profile.description }} />
                                        )}
                                        {activeTab === 'vision' && (
                                            <article className="prose max-w-none dark:prose-invert" dangerouslySetInnerHTML={{ __html: profile.vision }} />
                                        )}
                                        {activeTab === 'mission' && (
                                            <article className="prose max-w-none dark:prose-invert" dangerouslySetInnerHTML={{ __html: profile.mission }} />
                                        )}
                                        {activeTab === 'history' && (
                                            <article className="prose max-w-none dark:prose-invert" dangerouslySetInnerHTML={{ __html: profile.history }} />
                                        )}
                                        {/* [BARU] Panel konten untuk tab statistik */}
                                        {activeTab === 'stats' && (
                                            <div>
                                                <h2 className="text-2xl font-bold text-gray-900 dark:text-white mb-4 flex items-center"><ChartBarIcon /> Statistik Anggota</h2>
                                                <div className="space-y-2 rounded-lg border dark:border-gray-700 p-4">
                                                    {memberStats.map((stat, index) => (
                                                        <StatItem
                                                            key={index}
                                                            label={stat.nama_kategori}
                                                            value={stat.total}
                                                            color={['bg-indigo-500', 'bg-teal-500', 'bg-amber-500'][index % 3]}
                                                        />
                                                    ))}
                                                </div>
                                            </div>
                                        )}
                                    </div>
                                </main>
                            </div>
                        ) : (
                            <div className="text-center py-16 bg-white dark:bg-gray-800 rounded-lg shadow-xl"><p className="text-xl text-gray-500">Profil untuk rayon ini belum tersedia.</p></div>
                        )}

                        {articles.data.length > 0 && (
                            <section className="mt-16">
                                <h2 className="text-3xl font-bold text-center text-gray-900 dark:text-white mb-8">Artikel Terbaru Rayon</h2>
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    {articles.data.map(article => <ArticleCard key={article.id} article={article} />)}
                                </div>
                                <div className="mt-12"><Pagination links={articles.links} /></div>
                            </section>
                        )}
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
