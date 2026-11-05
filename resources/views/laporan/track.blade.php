<x-app-layout>
    <div class="page-container">
        @if (session('success'))
            <div class="alert mb-5" role="status">{{ session('success') }}</div>
        @endif

        <section class="mx-auto max-w-2xl rounded-2xl bg-white p-6 shadow-sm sm:p-8">
            <p class="text-xs font-bold uppercase tracking-[0.16em] text-teal-700">Pelacakan Laporan</p>
            <h1 class="mt-2 text-2xl font-bold text-gray-900">Status penanganan laporan</h1>
            <p class="mt-2 text-sm text-gray-600">Buka kembali halaman ini kapan saja untuk melihat status terbaru dari petugas.</p>

            @php
                $statusClass = match ($laporan->status) {
                    'Selesai' => 'bg-green-100 text-green-800',
                    'Diproses' => 'bg-blue-100 text-blue-800',
                    default => 'bg-amber-100 text-amber-800',
                };
            @endphp

            <div class="mt-6 rounded-xl border border-gray-200 bg-gray-50 p-5">
                <p class="text-sm text-gray-500">Lokasi laporan</p>
                <p class="mt-1 font-semibold text-gray-900">{{ $laporan->lokasi }}, {{ $laporan->kecamatan }}</p>
                <p class="mt-5 text-sm text-gray-500">Status terbaru</p>
                <span class="mt-2 inline-flex rounded-full px-4 py-2 text-sm font-bold {{ $statusClass }}">{{ $laporan->status }}</span>
                @if ($laporan->status === 'Selesai')
                    <p class="mt-3 text-sm font-medium text-green-800">Laporan ini telah selesai ditangani.</p>
                @elseif ($laporan->status === 'Diproses')
                    <p class="mt-3 text-sm text-blue-800">Petugas sedang menindaklanjuti laporan ini.</p>
                @else
                    <p class="mt-3 text-sm text-amber-800">Laporan telah diterima dan menunggu penanganan.</p>
                @endif
                <p class="mt-4 text-xs text-gray-500">Terakhir diperbarui {{ $laporan->updated_at->format('d-m-Y H:i') }}</p>
            </div>

            <div class="mt-5 flex flex-wrap gap-3">
                @auth
                    @if (! auth()->user()->isPetugas())
                        <a href="{{ route('riwayat.index') }}" class="flood-button">Kembali ke Riwayat Saya</a>
                    @endif
                @endauth
                <a href="{{ route('lapor.form') }}" class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Kirim Laporan</a>
            </div>
        </section>
    </div>
</x-app-layout>
