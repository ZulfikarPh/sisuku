import { useEffect } from 'react';
import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import AuthLayout from '@/Layouts/AuthLayout';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Head, Link, useForm } from '@inertiajs/react';

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = (e) => {
        e.preventDefault();
        post(route('login'));
    };

    return (
        <AuthLayout>
            <Head title="Log in" />

            <div className="w-full bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">
                <div className="text-center">
                    <Link href="/" className="lg:hidden mb-6 inline-block">
                        <ApplicationLogo className="w-16 h-16 fill-current text-gray-500" />
                    </Link>
                    <h1 className="text-2xl font-bold text-gray-800 dark:text-gray-100">Selamat Datang Kembali!</h1>
                    <p className="text-sm text-gray-600 dark:text-gray-400">Silakan masuk untuk melanjutkan.</p>
                </div>

                {status && <div className="mt-6 mb-4 font-medium text-sm text-green-600 dark:text-green-400">{status}</div>}

                <form onSubmit={submit} className="mt-6">
                    <div>
                        <InputLabel htmlFor="email" value="Email" />
                        <TextInput
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            isFocused={true}
                            onChange={(e) => setData('email', e.target.value)}
                            required
                        />
                        <InputError message={errors.email} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <div className="flex justify-between items-baseline">
                            <InputLabel htmlFor="password" value="Password" />
                            {canResetPassword && (
                                <Link
                                    href={route('password.request')}
                                    className="text-xs text-indigo-600 dark:text-indigo-400 hover:underline"
                                >
                                    Lupa password?
                                </Link>
                            )}
                        </div>
                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="current-password"
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />
                        <InputError message={errors.password} className="mt-2" />
                    </div>

                    <div className="mt-4 block">
                        <label className="flex items-center">
                            <Checkbox name="remember" checked={data.remember} onChange={(e) => setData('remember', e.target.checked)} />
                            <span className="ms-2 text-sm text-gray-600 dark:text-gray-400">Ingat saya</span>
                        </label>
                    </div>

                    <div className="mt-8">
                        <PrimaryButton className="w-full justify-center text-base py-3" disabled={processing}>
                            Log In
                        </PrimaryButton>
                    </div>
                </form>

                <div className="mt-6 text-center">
                    <p className="text-sm text-gray-600 dark:text-gray-400">
                        Belum punya akun?{' '}
                        <Link href={route('register')} className="font-medium text-indigo-600 hover:text-indigo-500">
                            Daftar di sini
                        </Link>
                    </p>
                </div>
            </div>
        </AuthLayout>
    );
}
