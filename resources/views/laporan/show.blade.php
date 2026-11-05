<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('laporan.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">&larr; Kembali ke laporan</a>
                <h2 class="mt-2 text-xl font-semibold leading-tight text-gray-800">Detail Laporan</h2>
            </div>
            @auth
                <div class="flex gap-2">
                    <a href="{{ route('laporan.edit', $laporan) }}" class="inline-flex items-center rounded-md border border-teal-700 bg-white px-4 py-2 text-sm font-medium text-teal-800 hover:bg-teal-50">Edit</a>
                    <form action="{{ route('laporan.destroy', $laporan) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center rounded-md bg-red-700 px-4 py-2 text-sm font-semibold text-white hover:bg-red-800">Hapus</button>
                    </form>
                </div>
            @endauth
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="alert" role="status">{{ session('success') }}</div>
            @endif

            <section class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-200">
                @if ($laporan->foto)
                    <a href="{{ route('laporan.foto', $laporan, false) }}" target="_blank" rel="noopener">
                        <img src="{{ route('laporan.foto', $laporan, false) }}" alt="Foto kejadian banjir di {{ $laporan->lokasi }}" class="max-h-96 w-full object-cover">
                    </a>
                @endif
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-start justify-between gap-4 border-b border-gray-100 pb-5">
                        <div>
                            <p class="text-sm font-medium text-indigo-600">{{ $laporan->kecamatan }}</p>
                            <h3 class="mt-1 text-2xl font-bold text-gray-900">{{ $laporan->lokasi }}</h3>
                            <p class="mt-2 text-sm text-gray-500">Kejadian {{ $laporan->tanggal_kejadian?->format('d M Y') ?? $laporan->created_at->format('d M Y') }} · Dilaporkan {{ $laporan->created_at->format('H:i') }}</p>
                        </div>
                        @php
                            $statusClass = match ($laporan->status) {
                                'Selesai' => 'bg-green-100 text-green-800',
                                'Diproses' => 'bg-blue-100 text-blue-800',
                                default => 'bg-amber-100 text-amber-800',
                            };
                        @endphp
                        <span class="rounded-full px-3 py-1 text-sm font-medium {{ $statusClass }}">{{ $laporan->status }}</span>
                    </div>

                    @include('laporan._status-form', ['laporan' => $laporan])

                    <dl class="grid gap-5 py-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm text-gray-500">Nama Pelapor</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $laporan->nama_pelapor }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Nomor HP</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $laporan->no_hp }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Tinggi Air</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ number_format($laporan->tinggi_air) }} cm</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-500">Lokasi</dt>
                            <dd class="mt-1 font-medium text-gray-900">{{ $laporan->lokasi }}, {{ $laporan->kecamatan }}</dd>
                        </div>
                    </dl>

                    <div class="border-t border-gray-100 pt-5">
                        <h4 class="text-sm font-semibold text-gray-900">Keterangan</h4>
                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-700">{{ $laporan->keterangan }}</p>
                    </div>

                </div>
            </section>
        </div>
    </div>
</x-app-layout>
