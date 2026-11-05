<x-app-layout>
    <div class="page-container">
        <div class="page-title">
            <h1>Daftar Laporan Banjir</h1>
            <p>Data laporan yang tersimpan di LaporBanjir. Periksa bukti foto dan perbarui status penanganan.</p>
        </div>

        @if (session('success'))
            <div class="alert" role="status">{{ session('success') }}</div>
        @endif

        <div class="laporan-grid">
            @forelse ($laporans as $laporan)
                @php
                    $severity = $laporan->tinggi_air < 30
                        ? ['Waspada', 'waspada']
                        : ($laporan->tinggi_air <= 70 ? ['Siaga', 'siaga'] : ['Awas', 'awas']);
                    $statusClass = match ($laporan->status) {
                        'Selesai' => 'bg-green-100 text-green-800',
                        'Diproses' => 'bg-blue-100 text-blue-800',
                        default => 'bg-amber-100 text-amber-800',
                    };
                @endphp
                <article class="laporan-card">
                    <div class="flex items-start justify-between gap-3">
                        <h2>{{ $laporan->nama_pelapor }}</h2>
                        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">{{ $laporan->status }}</span>
                    </div>
                    @if ($laporan->foto)
                        <a href="{{ route('laporan.foto', $laporan, false) }}" target="_blank" rel="noopener">
                            <img src="{{ route('laporan.foto', $laporan, false) }}" alt="Bukti banjir di {{ $laporan->lokasi }}" class="mb-4 h-40 w-full rounded-lg object-cover">
                        </a>
                    @endif
                    <div class="laporan-data"><span class="label">Lokasi:</span> {{ $laporan->lokasi }}, {{ $laporan->kecamatan }}</div>
                    <div class="laporan-data"><span class="label">Tinggi Genangan:</span> {{ $laporan->tinggi_air }} cm</div>
                    <div class="laporan-data"><span class="label">Tanggal Kejadian:</span> {{ $laporan->tanggal_kejadian?->format('d-m-Y') ?? $laporan->created_at->format('d-m-Y') }}</div>
                    <span class="status {{ $severity[1] }}">{{ $severity[0] }}</span>
                    @include('laporan._status-form', ['laporan' => $laporan])
                    <div class="mt-4 flex flex-wrap gap-3 border-t border-gray-100 pt-4">
                        <a href="{{ route('laporan.show', $laporan) }}" class="font-semibold text-teal-800 hover:underline">Detail</a>
                        <a href="{{ route('laporan.edit', $laporan) }}" class="font-semibold text-teal-800 hover:underline">Ubah</a>
                        <form action="{{ route('laporan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="font-semibold text-red-700 hover:underline">Hapus</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="card">
                    <p>Belum terdapat laporan banjir.</p>
                </div>
            @endforelse
        </div>

        @if ($laporans->hasPages())
            <div class="mt-6">{{ $laporans->links() }}</div>
        @endif

        <div class="mt-6">
            <a href="{{ route('lapor.form') }}" class="flood-button">+ Buat Laporan Baru</a>
        </div>
    </div>
</x-app-layout>
