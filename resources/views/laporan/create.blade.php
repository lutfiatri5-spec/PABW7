<x-app-layout>
    <div class="page-container">
        <div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold text-gray-900">Tambah Laporan</h1>
            <p class="mt-2 text-sm text-gray-600">Masukkan informasi laporan dan foto bukti.</p>
            @include('laporan._form', ['action' => route('laporan.store')])
        </div>
    </div>
</x-app-layout>
