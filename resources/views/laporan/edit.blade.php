<x-app-layout>
    <div class="page-container">
        <div class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <h1 class="text-2xl font-bold text-gray-900">Ubah Laporan</h1>
            <p class="mt-2 text-sm text-gray-600">Perbarui data laporan, foto bukti, atau status penanganan.</p>
            @include('laporan._form', ['action' => route('laporan.update', $laporan), 'laporan' => $laporan])
        </div>
    </div>
</x-app-layout>
