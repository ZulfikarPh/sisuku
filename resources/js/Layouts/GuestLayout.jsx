import React, { useState, Fragment } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';
import { Menu, Transition, Disclosure } from '@headlessui/react';
import ApplicationLogo from '@/Components/ApplicationLogo';

// --- DEFINISI IKON (TETAP DI SINI SESUAI PREFERENSI) ---
const MenuIcon = () => <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 6h16M4 12h16M4 18h16" /></svg>;
const CloseIcon = () => <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" /></svg>;
const ChevronUpIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06 0L10 9.06l-3.71 3.73a.75.75 0 11-1.06-1.06l4.25-4.25a.75.75 0 011.06 0l4.25 4.25a.75.75 0 010 1.06z" clipRule="evenodd" /></svg>;
const InstagramIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.85s-.011 3.584-.069 4.85c-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07s-3.584-.012-4.85-.07c-3.252-.148-4.771-1.691-4.919-4.919-.058-1.265-.069-1.645-.069-4.85s.011-3.584.069-4.85c.149-3.225 1.664-4.771 4.919-4.919C8.416 2.175 8.796 2.163 12 2.163zm0 1.626c-3.141 0-3.48.01-4.697.067-2.61.12-3.844 1.354-3.964 3.964-.057 1.217-.066 1.556-.066 4.697s.01 3.48.066 4.697c.12 2.61 1.354 3.844 3.964 3.964 1.217.057 1.556.066 4.697.066s3.48-.01 4.697-.066c2.61-.12 3.844-1.354 3.964-3.964.057-1.217-.066-1.556-.066-4.697s-.01-3.48-.066-4.697c-.12-2.61-1.354-3.844-3.964-3.964C15.48 3.799 15.141 3.79 12 3.79zm0 8.242a2.828 2.828 0 100 5.656 2.828 2.828 0 000-5.656zm0 7.272a4.444 4.444 0 110-8.888 4.444 4.444 0 010 8.888zM16.892 7.11a1.2 1.2 0 100-2.4 1.2 1.2 0 000 2.4z" /></svg>;
const FacebookIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M22.675 0h-21.35C.59 0 0 .59 0 1.325v21.35C0 23.41.59 24 1.325 24H12.82v-9.29H9.692v-3.622h3.128V8.413c0-3.1 1.893-4.788 4.659-4.788 1.325 0 2.463.099 2.795.143v3.24l-1.918.001c-1.504 0-1.795.715-1.795 1.763v2.313h3.587l-.467 3.622h-3.12V24h6.116c.735 0 1.325-.59 1.325-1.325V1.325C24 .59 23.41 0 22.675 0z" /></svg>;
const YouTubeIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21.582 7.243c-.23-.84-.897-1.508-1.737-1.737C18.252 5 12 5 12 5s-6.252 0-7.845.506c-.84.23-1.508.897-1.737 1.737C2 9.07 2 12 2 12s0 2.93.506 4.757c.23.84.897 1.508 1.737 1.737C5.835 19 12 19 12 19s6.252 0 7.845-.506c.84-.23 1.508-.897 1.737-1.737C22 14.93 22 12 22 12s0-2.93-.418-4.757zM9.545 14.545V9.455l4.763 2.545-4.763 2.545z"/></svg>;
const TikTokIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2zm4.188 6.516c.105.02.21.036.314.052v2.835a4.238 4.238 0 0 1-.803.062c-1.492 0-2.703-1.21-2.703-2.702v-3.23c0-2.022 1.64-3.663 3.664-3.663.513 0 1.004.105 1.45.3V4.99c-.445-.195-.935-.3-1.45-.3-3.037 0-5.504 2.467-5.504 5.502v3.23c0 .852.69 1.543 1.543 1.543.046 0 .092-.002.138-.006.01-.002.02-.002.03-.002h.016c.05-.008.1-.016.15-.025.042-.008.084-.017.126-.027a2.71 2.71 0 0 0 1.91-1.037 2.71 2.71 0 0 0 1.038-1.91v-2.31c.21.144.408.305.59.484a2.704 2.704 0 0 0 1.91 1.037c.04.008.08.016.12.024.05.01.1.02.15.03h.015c.046.004.092.006.138.006.852 0 1.543-.69 1.543-1.543v-3.23c0-.057-.004-.113-.01-.17a.068.068 0 0 1-.003-.016c-.01-.05-.02-.1-.03-.15-.008-.042-.017-.084-.027-.126a2.71 2.71 0 0 0-1.037-1.91 2.71 2.71 0 0 0-1.91-1.038v-2.14c.75.253 1.41.674 1.91 1.25.75.862 1.25 2.013 1.25 3.25 0 2.485-2.015 4.5-4.5 4.5-.472 0-.928-.074-1.352-.213v2.85c.42.135.875.21 1.352.21 3.59 0 6.5-2.91 6.5-6.5s-2.91-6.5-6.5-6.5c-3.59 0-6.5 2.91-6.5 6.5 0 .046.002.092.006.138a.068.068 0 0 1 .002.016c.002.01.002.02.002.03h.001c.008.05.016.1.025.15.008.042.017.084.027.126a2.71 2.71 0 0 0 1.037 1.91 2.71 2.71 0 0 0 1.91 1.038v2.14Z"/></svg>;

// --- KOMPONEN UTAMA LAYOUT ---
export default function GuestLayout({ children }) {
    const { url, props } = usePage();
    const { auth, rayons, komisariatProfile } = props;
    const [isMobileMenuOpen, setMobileMenuOpen] = useState(false);

    // [PERBAIKAN] Mendefinisikan semua item navigasi dalam satu tempat
    const navItems = [
        { type: 'link', href: '/', label: 'Homepage' },
        { type: 'link', href: '/artikel', label: 'Artikel' },
        {
            type: 'dropdown',
            label: 'Profil Rayon',
            // Gunakan optional chaining `?.` untuk keamanan jika `rayons` tidak ada
            items: rayons?.filter(rayon => rayon.name !== 'Komisariat') || []
        },
        // Contoh jika ingin menambahkan link profil komisariat secara dinamis
        ...(komisariatProfile ? [{ type: 'link', href: '/profil/komisariat', label: 'Profil Komisariat' }] : []),
    ];

    // Logika cerdas untuk membuat daftar link media sosial secara dinamis
    const iconMap = {
        instagram: InstagramIcon, facebook: FacebookIcon, youtube: YouTubeIcon, tiktok: TikTokIcon,
    };
    const socialLinks = komisariatProfile?.social_media
        ? Object.keys(komisariatProfile.social_media).map(key => ({
            name: key.charAt(0).toUpperCase() + key.slice(1),
            href: komisariatProfile.social_media[key],
            Icon: iconMap[key.toLowerCase()]
        })).filter(link => link.Icon && link.href)
        : [];

    // Fungsi helper untuk menentukan class CSS pada link navigasi yang aktif
    const navLinkClasses = (path, mobile = false) => {
        const base = mobile
            ? 'block px-3 py-2 rounded-md text-base font-medium'
            : 'text-sm font-medium transition-colors duration-300';

        const active = 'text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-gray-700/50';
        const inactive = 'text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400';

        const isActive = (url === path) || (path !== '/' && url.startsWith(path));
        return `${base} ${isActive ? active : inactive}`;
    };

    // Menyiapkan variabel dinamis dengan fallback yang aman
    const siteName = komisariatProfile?.nama_singkat || 'Sisuku';
    const pageTitle = komisariatProfile?.nama_komisariat || 'Sistem Informasi Sahabat Sunan Kudus';
    const siteDescription = komisariatProfile?.tentang || 'Wadah digital untuk pergerakan, wacana, dan aksi kader PMII.';

    return (
        <div className="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 font-sans text-gray-800 dark:text-gray-200">
            <Head
                titleTemplate={`%s - ${siteName}`}
                title={pageTitle}
            >
                <meta name="description" content={siteDescription} />
                <link rel="icon" type="image/png" href="/images/logo-pmii.png" />
            </Head>

            <header className="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm shadow-md sticky top-0 z-50 border-b border-gray-200 dark:border-gray-700">
                <div className="container mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between items-center h-16">
                        <Link href="/" className="flex items-center space-x-3 shrink-0" aria-label="Ke Halaman Utama">
                            <img src="/images/logo-pmii.png" alt="Logo PMII" className="h-9 w-auto object-contain" />
                            <span className="text-xl font-bold text-gray-800 dark:text-white">{siteName}</span>
                        </Link>

                        <nav className="hidden md:flex space-x-8 items-center">
                            {navItems.map((item) =>
                                item.type === 'link' ? (
                                    <Link key={item.href} href={item.href} className={navLinkClasses(item.href)}>
                                        {item.label}
                                    </Link>
                                ) : (
                                    <Menu key={item.label} as="div" className="relative">
                                        <Menu.Button className={`${navLinkClasses('/profil/rayon')} inline-flex items-center`}>
                                            {item.label}
                                            <svg className="-mr-1 ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clipRule="evenodd" /></svg>
                                        </Menu.Button>
                                        <Transition as={Fragment} enter="transition ease-out duration-200" enterFrom="opacity-0 translate-y-1" enterTo="opacity-100 translate-y-0" leave="transition ease-in duration-150" leaveFrom="opacity-100 translate-y-0" leaveTo="opacity-0 translate-y-1">
                                            <Menu.Items className="absolute right-0 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                                <div className="p-1">
                                                    {item.items.map(rayon => (
                                                        <Menu.Item key={rayon.id}>
                                                            {({ active }) => (
                                                                <Link href={`/profil/rayon/${rayon.slug}`} className={`${active ? 'bg-indigo-500 text-white' : 'text-gray-900 dark:text-gray-200'} group flex w-full items-center rounded-md px-2 py-2 text-sm transition-colors`}>
                                                                    {rayon.name}
                                                                </Link>
                                                            )}
                                                        </Menu.Item>
                                                    ))}
                                                </div>
                                            </Menu.Items>
                                        </Transition>
                                    </Menu>
                                )
                            )}
                        </nav>

                        <div className="hidden md:flex items-center space-x-4">
                            {auth && auth.user ? (
                                <Link href={route('dashboard')} className="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor</Link>
                            ) : (
                                <>
                                    <Link href={route('login')} className="text-sm font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Login</Link>
                                    {/* <Link href={route('pendaftaran.mapaba.create')} className="bg-indigo-600 text-white py-2 px-4 rounded-md text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                        Daftar MAPABA
                                    </Link> */}
                                </>
                            )}
                        </div>

                        <div className="md:hidden flex items-center">
                            <button onClick={() => setMobileMenuOpen(!isMobileMenuOpen)} className="inline-flex items-center justify-center p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none" aria-label="Buka menu">
                                <span className="sr-only">Buka menu utama</span>
                                {isMobileMenuOpen ? <CloseIcon /> : <MenuIcon />}
                            </button>
                        </div>
                    </div>
                </div>

                <Transition show={isMobileMenuOpen} as={Fragment} enter="transition ease-out duration-200" enterFrom="opacity-0 -translate-y-1" enterTo="opacity-100 translate-y-0" leave="transition ease-in duration-150" leaveFrom="opacity-100 translate-y-0" leaveTo="opacity-0 -translate-y-1">
                    <div className="md:hidden absolute top-full left-0 w-full bg-white dark:bg-gray-800 shadow-lg border-t border-gray-200 dark:border-gray-700" id="mobile-menu">
                        <div className="p-4 space-y-4">
                            {/* Tautan Navigasi Mobile */}
                            {navItems.filter(i => i.type === 'link').map((link) => (
                                <Link key={link.href} href={link.href} className={navLinkClasses(link.href, true)} onClick={() => setMobileMenuOpen(false)}>
                                    {link.label}
                                </Link>
                            ))}

                            {/* Accordion Profil Rayon */}
                            <Disclosure as="div" className="-mx-3">
                                {({ open }) => (
                                    <>
                                        <Disclosure.Button className={`${navLinkClasses('/profil/rayon', true)} w-full flex justify-between items-center`}>
                                            <span>Profil Rayon</span>
                                            <ChevronUpIcon className={`${open ? 'rotate-180 transform' : ''} h-5 w-5 transition-transform`} />
                                        </Disclosure.Button>
                                        <Disclosure.Panel className="pl-4 mt-2 space-y-2 border-l-2 border-indigo-200 dark:border-gray-600">
                                            {rayons?.filter(rayon => rayon.name !== 'Komisariat').map(rayon => (
                                                <Link key={rayon.id} href={`/profil/rayon/${rayon.slug}`} className="block px-3 py-2 rounded-md text-base font-medium text-gray-600 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-gray-700/50" onClick={() => setMobileMenuOpen(false)}>
                                                    {rayon.name}
                                                </Link>
                                            ))}
                                        </Disclosure.Panel>
                                    </>
                                )}
                            </Disclosure>

                            {/* Divider */}
                            <div className="border-t border-gray-200 dark:border-gray-700 !my-6"></div>

                            {/* Tombol Aksi Mobile */}
                            {/* <div className="space-y-4">
                               {auth && auth.user ? (
                                    <Link href={route('dashboard')} className="w-full text-center block bg-indigo-600 text-white py-2 px-4 rounded-md text-base font-medium hover:bg-indigo-700 transition-colors" onClick={() => setMobileMenuOpen(false)}>Dasbor</Link>
                                ) : (
                                    <>
                                        <Link href={route('login')} className="w-full text-center block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 py-2 px-4 rounded-md text-base font-medium hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" onClick={() => setMobileMenuOpen(false)}>Login</Link>
                                        <Link href={route('pendaftaran.mapaba.create')} className="w-full text-center block bg-indigo-600 text-white py-2 px-4 rounded-md text-base font-medium hover:bg-indigo-700 transition-colors" onClick={() => setMobileMenuOpen(false)}>
                                            Daftar MAPABA
                                        </Link>
                                    </>
                                )}
                            </div> */}
                        </div>
                    </div>
                </Transition>
            </header>

            <main className="flex-grow">{children}</main>

            <footer className="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 text-gray-600 dark:text-gray-300">
                <div className="container mx-auto px-6 py-10">
                    <div className="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
                        {/* Kolom 1: Info Situs */}
                        <div className="space-y-4 md:col-span-3 lg:col-span-1">
                            <Link href="/" className="flex items-center space-x-3">
                                <img src="/images/logo-pmii.png" alt="Logo PMII" className="h-9 w-auto object-contain" />
                                <span className="text-xl font-bold text-gray-800 dark:text-white">{siteName}</span>
                            </Link>
                            <p className="text-sm">{siteDescription}</p>
                        </div>

                        {/* Kolom 2: Tautan Navigasi */}
                        <div className="col-span-1 md:col-auto">
                            <h3 className="font-semibold text-gray-800 dark:text-white uppercase tracking-wider">Tautan Cepat</h3>
                            <div className="mt-4 space-y-2">
                                {navItems.filter(item => item.type === 'link').map(link => (
                                     <Link key={link.href} href={link.href} className="block text-sm hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{link.label}</Link>
                                ))}
                                <Link href={route('login')} className="block text-sm hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Login Kader</Link>
                            </div>
                        </div>

                        {/* Kolom 3: Hubungi Kami */}
                        <div className="col-span-1 md:col-auto">
                           <h3 className="font-semibold text-gray-800 dark:text-white uppercase tracking-wider">Hubungi Kami</h3>
                            <div className="mt-4 space-y-2 text-sm">
                                {komisariatProfile?.alamat_sekretariat && <p>{komisariatProfile.alamat_sekretariat}</p>}
                                {komisariatProfile?.email_resmi && <a href={`mailto:${komisariatProfile.email_resmi}`} className="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">{komisariatProfile.email_resmi}</a>}
                            </div>
                        </div>

                        {/* Kolom 4: Media Sosial */}
                        <div className="col-span-1 md:col-span-3 lg:col-auto">
                            <h3 className="font-semibold text-gray-800 dark:text-white uppercase tracking-wider">Ikuti Kami</h3>
                            <div className="flex space-x-4 mt-4">
                                {socialLinks.map(({ name, href, Icon }) => (
                                    href && <a key={name} href={href} target="_blank" rel="noopener noreferrer" className="text-gray-400 hover:text-indigo-500 transition-colors" aria-label={name}><Icon /></a>
                                ))}
                            </div>
                        </div>
                    </div>

                    <div className="mt-10 border-t border-gray-200 dark:border-gray-700 pt-6 text-center">
                        <p className="text-sm text-gray-500">&copy; {new Date().getFullYear()} {pageTitle}. All Rights Reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
