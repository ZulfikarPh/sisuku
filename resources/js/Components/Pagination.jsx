import { Link } from '@inertiajs/react';

export default function Pagination({ links }) {
    return (
        <div className="flex items-center justify-between">
            <div className="flex flex-1 justify-between sm:justify-end">
                {links.map((link, index) => (
                    <Link
                        key={index}
                        href={link.url || '#'}
                        preserveScroll
                        className={
                            'relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 ' +
                            (link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600 ' : '') +
                            (!link.url ? '!text-gray-400 dark:!text-gray-500 cursor-not-allowed ' : '') +
                            (index === 0 ? 'rounded-r-none ' : '') +
                            (index === links.length - 1 ? 'rounded-l-none -ml-px ' : '-ml-px ')
                        }
                        dangerouslySetInnerHTML={{ __html: link.label }}
                        as="button"
                        disabled={!link.url}
                    />
                ))}
            </div>
        </div>
    );
}
