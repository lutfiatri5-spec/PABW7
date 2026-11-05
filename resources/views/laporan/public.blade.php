<x-app-layout>
    <div class="page-container">
        @if (session('success'))
            <div class="alert" role="status">{{ session('success') }}</div>
        @endif

        <section id="form" class="form-layout">
            <div class="intro-panel">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-100">Layanan Pelaporan</p>
                <h1 class="mt-4 text-3xl font-bold leading-tight">Bantu laporkan kondisi banjir.</h1>
                <p class="mt-4 max-w-md text-sm leading-6 text-teal-50">Informasi dari masyarakat membantu memberikan gambaran kondisi banjir yang terjadi di suatu wilayah.</p>
                <ol>
                    <li>Isi nama pelapor dan nomor HP</li>
                    <li>Masukkan lokasi kejadian</li>
                    <li>Isi tinggi genangan dan tanggal</li>
                    <li>Tambahkan keterangan dan foto bukti</li>
                </ol>
            </div>

            <div class="card">
                <h2 class="text-2xl font-bold text-gray-900">Form Laporan</h2>
                <p class="mt-2 text-sm text-gray-600">Silakan isi informasi kejadian banjir di bawah ini.</p>
                @include('laporan._form', ['action' => route('lapor.submit')])
            </div>
        </section>

    </div>
</x-app-layout>
