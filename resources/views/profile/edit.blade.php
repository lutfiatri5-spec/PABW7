<x-app-layout>
    <div class="page-container">
        <p class="text-xs font-bold uppercase tracking-[0.16em] text-teal-700">Akun Petugas</p>
        <h1 class="mt-1 text-2xl font-bold text-teal-800">Profil Petugas</h1>
        <p class="mt-2 text-sm text-gray-600">Informasi akun yang sedang digunakan untuk mengakses sistem LaporBanjir.</p>

        @if (session('status') === 'profile-updated')
            <div class="mt-5 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-800" role="status">Profil berhasil diperbarui.</div>
        @endif

        <section class="mx-auto mt-5 max-w-3xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <div class="grid h-14 w-14 place-items-center rounded-full bg-[#d9efeb] text-xl font-bold text-teal-800">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="mt-3 text-lg font-bold text-teal-800">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->isPetugas() ? 'Petugas BPBD Kabupaten Bandung' : 'Warga LaporBanjir' }}</p>

            <dl class="mt-5 divide-y divide-gray-100 border-y border-gray-100 text-sm">
                <div class="grid gap-1 py-3 sm:grid-cols-[150px_1fr]">
                    <dt class="font-semibold text-gray-800">Nama Lengkap</dt>
                    <dd class="text-gray-700">{{ $user->name }}</dd>
                </div>
                <div class="grid gap-1 py-3 sm:grid-cols-[150px_1fr]">
                    <dt class="font-semibold text-gray-800">Email</dt>
                    <dd class="text-gray-700">{{ $user->email }}</dd>
                </div>
                <div class="grid gap-1 py-3 sm:grid-cols-[150px_1fr]">
                    <dt class="font-semibold text-gray-800">Akun Dibuat</dt>
                    <dd class="text-gray-700">{{ $user->created_at->format('d F Y, H:i') }}</dd>
                </div>
            </dl>

            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('laporan.index') }}" class="flood-button">Kembali ke Daftar Laporan</a>
            </div>
        </section>
    </div>
</x-app-layout>
