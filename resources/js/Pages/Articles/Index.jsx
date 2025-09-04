import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, router } from '@inertiajs/react';
import { useState, useEffect } from 'react';
import Pagination from '@/Components/Pagination';
import ArticleCard from '@/Components/ArticleCard';

export default function Index({ featuredArticle, articles, categories, filters }) {
    // State untuk menyimpan input filter dari pengguna, diinisialisasi dengan filter aktif dari controller
    const [searchTerm, setSearchTerm] = useState(filters.search || '');
    const [selectedCategory, setSelectedCategory] = useState(filters.category || '');

    // useEffect untuk mengirim request filter secara otomatis saat ada perubahan
    useEffect(() => {
        // Debounce: Menunggu 500ms setelah pengguna berhenti mengetik sebelum mengirim request
        const timer = setTimeout(() => {
            const params = { search: searchTerm, category: selectedCategory };

            // Hapus parameter yang kosong agar URL bersih
            if (!searchTerm) delete params.search;
            if (!selectedCategory) delete params.category;

            router.get(route('articles.index'), params, {
                preserveState: true,
                replace: true,
            });
        }, 500);

        // Membersihkan timer jika user mengetik lagi sebelum 500ms
        return () => clearTimeout(timer);
    }, [searchTerm, selectedCategory]);

    return (
        <GuestLayout>
            <Head title="Artikel" description="Kumpulan artikel, wacana, dan gagasan dari kader PMII." />

            <div className="bg-white dark:bg-gray-900">
                {/* BAGIAN 1: HERO / ARTIKEL UNGGULAN */}
                {featuredArticle && (
                    <section className="relative h-[60vh] min-h-[400px] text-white">
                        <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent z-10"></div>
                        <img
                            src={featuredArticle.thumbnail_url}
                            alt={featuredArticle.title}
                            className="absolute inset-0 w-full h-full object-cover"
                        />
                        <div className="relative z-20 h-full flex flex-col justify-end p-8 md:p-12">
                            <Link href={route('articles.show', featuredArticle.slug)} className="group">
                                <span className="mb-2 inline-block bg-indigo-500 px-3 py-1 text-sm font-semibold rounded-full group-hover:bg-indigo-400 transition-colors">
                                    {featuredArticle.category.name}
                                </span>
                                <h1 className="text-3xl md:text-5xl font-extrabold leading-tight drop-shadow-lg group-hover:underline">
                                    {featuredArticle.title}
                                </h1>
                            </Link>
                            <p className="mt-4 max-w-2xl text-gray-200 line-clamp-2">
                                {featuredArticle.excerpt}
                            </p>
                            <div className="mt-4 text-sm text-gray-300">
                                <span>Oleh {featuredArticle.penulis}</span>
                                <span className="mx-2">&bull;</span>
                                <span>{new Date(featuredArticle.published_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                            </div>
                        </div>
                    </section>
                )}

                {/* BAGIAN 2: DAFTAR ARTIKEL & FILTER */}
                <div className="py-12 bg-gray-50 dark:bg-gray-900/50">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        {/* Filter Section */}
                        <div className="mb-8 flex flex-col md:flex-row gap-4 items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                            <h2 className="text-lg font-semibold text-gray-700 dark:text-gray-200 hidden lg:block">Filter:</h2>
                            <div className="relative flex-grow w-full">
                                <input
                                    type="text"
                                    value={searchTerm}
                                    onChange={(e) => setSearchTerm(e.target.value)}
                                    placeholder="Cari judul artikel..."
                                    className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500"
                                />
                                <div className="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg className="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                            </div>
                            <select
                                value={selectedCategory}
                                onChange={(e) => setSelectedCategory(e.target.value)}
                                className="border border-gray-300 rounded-md dark:bg-gray-700 dark:border-gray-600 focus:ring-indigo-500 focus:border-indigo-500 w-full md:w-auto"
                            >
                                <option value="">Semua Kategori</option>
                                {categories.map(category => (
                                    <option key={category.id} value={category.id}>{category.name}</option>
                                ))}
                            </select>
                        </div>

                        {/* Article Grid */}
                        {articles.data.length > 0 ? (
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                {articles.data.map((article) => (
                                    <ArticleCard key={article.id} article={article} />
                                ))}
                            </div>
                        ) : (
                            <div className="text-center py-16 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-lg">
                                <p className="text-xl font-semibold text-gray-600 dark:text-gray-400">Tidak Ada Artikel yang Ditemukan</p>
                                <p className="text-sm text-gray-500 mt-2">Coba ubah kata kunci pencarian atau filter kategori Anda.</p>
                            </div>
                        )}

                        {/* Pagination */}
                        {articles.total > articles.per_page && (
                            <div className="mt-12">
                                <Pagination links={articles.links} />
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </GuestLayout>
    );
}
