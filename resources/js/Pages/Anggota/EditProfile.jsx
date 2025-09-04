import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import { Head, useForm, usePage } from '@inertiajs/react';
import { Transition } from '@headlessui/react';

export default function EditProfile({ auth, status }) {
    // Ambil data 'anggota' yang kita kirim dari AnggotaProfileController
    const { anggota } = usePage().props;

    // Siapkan form dengan data awal dari profil anggota
    const { data, setData, post, errors, processing, recentlySuccessful } = useForm({
        _method: 'patch',
        no_hp: anggota?.no_hp || '',
        alamat: anggota?.alamat || '',
        minat_dan_bakat: anggota?.minat_dan_bakat || '',
        foto: null,
    });

    // Fungsi untuk mengirim data ke route 'anggota.profile.update'
    const submit = (e) => {
        e.preventDefault();
        post(route('anggota.profile.update'), {
            forceFormData: true, // Diperlukan untuk upload file
        });
    };

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Profil Keanggotaan</h2>}
        >
            <Head title="Edit Profil Keanggotaan" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


                    {/* {anggota && (
                        <div className="text-right">
                            <a
                                href={route('anggota.card.download')}
                                target="_blank"
                                className="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Download Kartu Anggota (PDF)
                            </a>
                        </div>
                    )} */}

                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <section className="max-w-xl">
                            <header>
                                <h2 className="text-lg font-medium text-gray-900">Informasi Profil Anggota</h2>
                                <p className="mt-1 text-sm text-gray-600">
                                    Perbarui data pribadi Anda. Data seperti Nama dan Rayon hanya bisa diubah oleh admin.
                                </p>
                            </header>

                            <form onSubmit={submit} className="mt-6 space-y-6">
                                {/* Menampilkan data yang tidak bisa diubah */}
                                <div>
                                    <InputLabel value="Nama Lengkap (Read-only)" />
                                    <TextInput className="mt-1 block w-full bg-gray-100" value={anggota?.nama || ''} disabled />
                                </div>
                                <div>
                                    <InputLabel value="Rayon (Read-only)" />
                                    <TextInput className="mt-1 block w-full bg-gray-100" value={anggota?.rayon?.name || ''} disabled />
                                </div>
                                <hr />

                                {/* Form untuk data yang bisa diubah */}
                                <div>
                                    <InputLabel htmlFor="no_hp" value="Nomor HP" />
                                    <TextInput id="no_hp" className="mt-1 block w-full" value={data.no_hp} onChange={(e) => setData('no_hp', e.target.value)} />
                                    <InputError className="mt-2" message={errors.no_hp} />
                                </div>
                                <div>
                                    <InputLabel htmlFor="alamat" value="Alamat" />
                                    <TextInput id="alamat" className="mt-1 block w-full" value={data.alamat} onChange={(e) => setData('alamat', e.target.value)} />
                                    <InputError className="mt-2" message={errors.alamat} />
                                </div>
                                <div>
                                    <InputLabel htmlFor="minat_dan_bakat" value="Minat & Bakat" />
                                    <TextInput id="minat_dan_bakat" className="mt-1 block w-full" value={data.minat_dan_bakat} onChange={(e) => setData('minat_dan_bakat', e.target.value)} />
                                    <InputError className="mt-2" message={errors.minat_dan_bakat} />
                                </div>
                                <div>
                                    <InputLabel htmlFor="foto" value="Ganti Foto Profil Anggota" />
                                    {anggota?.foto && <img src={`/storage/${anggota.foto}`} className="w-20 h-20 rounded-full my-2 object-cover" alt="Foto Profil"/>}
                                    <TextInput id="foto" type="file" className="mt-1 block w-full" onChange={(e) => setData('foto', e.target.files[0])} />
                                    <InputError className="mt-2" message={errors.foto} />
                                </div>

                                <div className="flex items-center gap-4">
                                    <PrimaryButton disabled={processing}>Simpan Perubahan</PrimaryButton>
                                    <Transition show={recentlySuccessful} enter="transition ease-in-out" enterFrom="opacity-0" leave="transition ease-in-out" leaveTo="opacity-0">
                                        <p className="text-sm text-gray-600">Tersimpan.</p>
                                    </Transition>
                                </div>
                                {anggota && (
                        <div className="text-right">
                            <a
                                href={route('anggota.card.download')}
                                target="_blank"
                                className="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Download Kartu Anggota (PDF)
                            </a>
                        </div>
                    )}
                            </form>
                        </section>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
