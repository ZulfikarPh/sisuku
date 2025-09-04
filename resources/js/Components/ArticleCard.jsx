import { Link } from '@inertiajs/react';

export default function ArticleCard({ article }) {
    return (
        <div className="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <Link href={`/artikel/${article.slug}`}>
                <img
                    className="w-full h-48 object-cover"
                    src={article.thumbnail_url || 'https://via.placeholder.com/400x200'}
                    alt={article.title}
                />
            </Link>
            <div className="p-6 flex flex-col h-full">
                <div className="flex-grow">
                    <div className="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-2">
                        <span>{article.category?.name || 'Uncategorized'}</span>
                        <span>{new Date(article.published_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}</span>
                    </div>
                    <h3 className="text-xl font-bold mb-2 text-gray-900 dark:text-white leading-tight">
                        <Link href={`/artikel/${article.slug}`} className="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                            {article.title}
                        </Link>
                    </h3>
                    <p className="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">
                        {article.excerpt}
                    </p>
                </div>
                <div className="mt-auto">
                    <Link href={`/artikel/${article.slug}`} className="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Baca Selengkapnya &rarr;
                    </Link>
                </div>
            </div>
        </div>
    );
}
