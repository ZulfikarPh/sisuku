import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link } from '@inertiajs/react';

// --- SUB-KOMPONEN untuk kerapian ---

// Pil untuk Kategori & Tag
const Pill = ({ children, href = '#' }) => (
    <Link href={href} className="block bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-xs font-semibold px-3 py-1 rounded-full hover:bg-indigo-200 dark:hover:bg-indigo-900 transition-colors">
        {children}
    </Link>
);

// Tombol Share
const ShareButton = ({ platform, url, children }) => {
    const shareUrls = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${url}`,
        twitter: `https://twitter.com/intent/tweet?url=${url}`,
        whatsapp: `https://api.whatsapp.com/send?text=${url}`,
    };
    return (
        <a href={shareUrls[platform]} target="_blank" rel="noopener noreferrer" className="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white transition-colors">
            {children}
        </a>
    );
};

// --- KOMPONEN UTAMA ---
export default function Show({ article, relatedArticles }) {
    // URL halaman saat ini untuk tombol share
    const currentUrl = window.location.href;

    // Helper untuk format tanggal
    const formatDate = (dateString) => new Date(dateString).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

    return (
        <GuestLayout>
            <Head title={article.title} description={article.excerpt} />

            <div className="bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                {/* BAGIAN 1: HERO HEADER IMERSIF */}
                <header className="relative h-[50vh] min-h-[350px] flex items-center justify-center text-white">
                    <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/40 to-black/20 z-10"></div>
                    <img
                        src={article.thumbnail_url} // [PERBAIKAN] Menggunakan accessor thumbnail_url
                        alt={article.title}
                        className="absolute inset-0 w-full h-full object-cover"
                    />
                    <div className="relative z-20 text-center max-w-4xl mx-auto px-4">
                        <Pill href="#">{article.category.name}</Pill>
                        <h1 className="text-3xl md:text-5xl font-extrabold mt-4 leading-tight drop-shadow-lg">
                            {article.title}
                        </h1>
                        <p className="mt-4 text-lg text-gray-200 drop-shadow">
                            Oleh {article.penulis} &bull; {formatDate(article.published_at)}
                        </p>
                    </div>
                </header>

                {/* BAGIAN 2: KONTEN UTAMA & SIDEBAR */}
                <div className="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12">

                        {/* Kolom Konten Utama */}
                        <main className="lg:col-span-8">
                            <article className="prose lg:prose-lg max-w-none dark:prose-invert"
                                dangerouslySetInnerHTML={{ __html: article.content }}
                            />
                        </main>

                        {/* Kolom Sidebar */}
                        <aside className="lg:col-span-4 space-y-8 lg:sticky top-24 self-start">
                            {/* Kartu Info Penulis/Rayon */}
                            <div className="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                                <h3 className="text-lg font-bold mb-4">Info Artikel</h3>
                                <div className="space-y-3 text-sm">
                                    <div className="flex justify-between">
                                        <span className="text-gray-500 dark:text-gray-400">Penulis:</span>
                                        <span className="font-semibold">{article.penulis}</span>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-500 dark:text-gray-400">Rayon:</span>
                                        <Link href={route('rayon.profil.show', article.rayon.slug)} className="font-semibold hover:underline text-indigo-600 dark:text-indigo-400">
                                            {article.rayon.name}
                                        </Link>
                                    </div>
                                    <div className="flex justify-between">
                                        <span className="text-gray-500 dark:text-gray-400">Tanggal Terbit:</span>
                                        <span className="font-semibold">{formatDate(article.published_at)}</span>
                                    </div>
                                </div>
                            </div>

                            {/* Kartu Share */}
                            <div className="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                                <h3 className="text-lg font-bold mb-4">Bagikan Artikel</h3>
                                <div className="flex items-center justify-center space-x-6">
                                    <ShareButton platform="facebook" url={currentUrl}>
                                        <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.59 0 0 .59 0 1.325v21.35C0 23.41.59 24 1.325 24H12.82v-9.29H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.735 0 1.325-.59 1.325-1.325V1.325C24 .59 23.41 0 22.675 0z" /></svg>
                                    </ShareButton>
                                    {/* Tambahkan Twitter, WA, dll. di sini */}
                                </div>
                            </div>

                            {/* Kartu Tags */}
                            {article.tags.length > 0 && (
                                <div className="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                                    <h3 className="text-lg font-bold mb-4">Tags</h3>
                                    <div className="flex flex-wrap gap-2">
                                        {article.tags.map(tag => <Pill key={tag.id}>{tag.name}</Pill>)}
                                    </div>
                                </div>
                            )}

                            {/* Kartu Artikel Terkait */}
                            {relatedArticles.length > 0 && (
                                <div className="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg">
                                    <h3 className="text-lg font-bold mb-4">Baca Juga</h3>
                                    <div className="space-y-4">
                                        {relatedArticles.map(related => (
                                            <Link key={related.id} href={route('articles.show', related.slug)} className="block group">
                                                <span className="font-bold group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{related.title}</span>
                                                <span className="text-xs block text-gray-500 dark:text-gray-400 mt-1">{related.category.name}</span>
                                            </Link>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </aside>
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
