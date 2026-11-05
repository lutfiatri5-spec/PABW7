<x-guest-layout>
    <div class="grid overflow-hidden rounded-2xl bg-white shadow-sm md:grid-cols-2">
        <section class="flood-panel flex flex-col justify-center">
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-100">Portal Petugas BPBD</p>
            <h1 class="mt-4 text-3xl font-bold leading-tight">Selamat datang kembali.</h1>
            <p class="mt-5 text-sm leading-6 text-teal-50">
                Petugas masuk dengan akun yang telah disediakan untuk mengelola laporan. Warga dapat login menggunakan akun yang sudah didaftarkan.
            </p>
            <div class="mt-6 rounded-lg border border-white/30 bg-white/10 p-4 text-xs leading-5 text-teal-50">
                Pengelolaan laporan hanya tersedia untuk akun petugas.
            </div>
        </section>

        <section class="p-6 sm:p-9">
            <h2 class="text-2xl font-bold text-gray-900">Login Petugas</h2>
            <p class="mt-2 text-sm text-gray-600">Masukkan email dan password akun Anda.</p>

            @if ($errors->any())
                <div class="mt-5 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                    <p class="font-semibold">Periksa kembali data yang dimasukkan:</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <x-auth-session-status class="mt-4 text-sm text-green-700" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                @csrf
                <div>
                    <label for="email" class="mb-1 block text-sm font-semibold text-gray-800">Email</label>
                    <input id="email" class="flood-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="nama@email.com">
                </div>
                <div>
                    <label for="password" class="mb-1 block text-sm font-semibold text-gray-800">Password</label>
                    <input id="password" class="flood-input" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password">
                </div>
                <label for="remember_me" class="flex items-center gap-2 text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-teal-700 focus:ring-teal-600" name="remember">
                    Ingat saya
                </label>
                <button type="submit" class="flood-button w-full">Login</button>
                @if (Route::has('password.request'))
                    <p class="text-center text-sm">
                        <a class="font-medium text-teal-800 hover:underline" href="{{ route('password.request') }}">Lupa password?</a>
                    </p>
                @endif
            </form>
        </section>
    </div>
</x-guest-layout>
