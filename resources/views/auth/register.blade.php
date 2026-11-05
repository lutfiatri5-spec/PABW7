<x-guest-layout>
    <div class="grid overflow-hidden rounded-2xl bg-white shadow-sm md:grid-cols-2">
        <section class="flood-panel flex flex-col justify-center">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-100">Portal Petugas BPBD</p>
            <h1 class="mt-4 text-3xl font-bold leading-tight">Bersama tanggap banjir.</h1>
            <p class="mt-5 text-sm leading-6 text-teal-50">Buat akun warga untuk login. Anda juga dapat mengirim laporan banjir tanpa login.</p>
        </section>

        <section class="p-6 sm:p-9">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Warga</h2>
            <p class="mt-2 text-sm text-gray-600">Buat akun warga untuk login ke LaporBanjir.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <ul class="list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="name" class="mb-1 block text-sm font-semibold text-gray-800">Nama Lengkap</label>
                    <input id="name" class="flood-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap">
                </div>
                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-800">Email</label>
                    <input id="email" class="flood-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="nama@email.com">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-semibold text-gray-800">Password</label>
                    <input id="password" class="flood-input" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                </div>
                <div>
                    <label for="password_confirmation" class="mb-1 block text-sm font-semibold text-gray-800">Konfirmasi Password</label>
                    <input id="password_confirmation" class="flood-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password">
                </div>
                <button type="submit" class="flood-button w-full">Daftar sebagai Warga</button>
                <p class="text-center text-sm text-gray-600">Sudah punya akun? <a class="font-semibold text-teal-800 hover:underline" href="{{ route('login') }}">Login</a></p>
            </form>
        </section>
    </div>
</x-guest-layout>
