import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.jsx';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard({ auth, anggota, latestArticles, upcomingAgendas }) {
    // Fungsi untuk format tanggal agar lebih rapi
    const formatDate = (dateString) => {
        if (!dateString) return '';
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dasbor</h2>}
        >
            <Head title="Dasbor" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

                    {/* Kartu Profil Anggota */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 className="text-2xl font-bold text-gray-900">Selamat datang, {auth.user.name}!</h3>
                        {anggota ? (
                            <p className="mt-2 text-gray-600">
                                {/* Menggunakan optional chaining (?.) untuk keamanan */}
                                Anda terdaftar sebagai anggota dari Rayon <span className="font-semibold">{anggota.rayon?.name || 'N/A'}</span>.
                            </p>
                        ) : (
                            <p className="mt-2 text-gray-600">
                                Gunakan menu navigasi untuk mengelola data sistem.
                            </p>
                        )}
                    </div>

                    {/* Informasi & Berita Terbaru */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            <h3 className="text-xl font-bold mb-4">Informasi & Berita Terbaru</h3>
                            <div className="space-y-4">
                                {latestArticles && latestArticles.length > 0 ? (
                                    latestArticles.map(article => (
                                        <div key={article.id} className="border-b last:border-b-0 pb-4">
                                            <p className="text-sm text-indigo-600 font-semibold">{article.category?.name || 'Tanpa Kategori'}</p>
                                            <Link href={route('articles.show', article.slug)} className="text-lg font-semibold text-gray-800 hover:text-indigo-600">
                                                {article.title}
                                            </Link>
                                            <p className="text-sm text-gray-500 mt-1">Oleh {article.penulis}</p>
                                        </div>
                                    ))
                                ) : (
                                    <p>Belum ada berita yang dipublikasikan.</p>
                                )}
                            </div>
                        </div>
                    </div>

                    {/* Agenda Kegiatan Terdekat */}
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                         <div className="p-6 text-gray-900">
                            <h3 className="text-xl font-bold mb-4">Agenda Kegiatan Terdekat</h3>
                            <div className="space-y-4">
                                {upcomingAgendas && upcomingAgendas.length > 0 ? (
                                    upcomingAgendas.map(agenda => (
                                        <div key={agenda.id} className="border-b last:border-b-0 pb-4">
                                            <p className="text-sm text-green-600 font-semibold">
                                                {formatDate(agenda.start_time)} WIB
                                            </p>
                                            <p className="text-lg font-semibold text-gray-800">{agenda.name}</p>
                                            <p className="text-sm text-gray-500 mt-1">Lokasi: {agenda.location}</p>
                                        </div>
                                    ))
                                ) : (
                                    <p>Belum ada agenda kegiatan terdekat.</p>
                                )}
                            </div>
                         </div>
                    </div>

                </div>
            </div>
        </AuthenticatedLayout>
    );
}
