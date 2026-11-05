@php($isEdit = isset($laporan))
@php($cancelUrl = $isEdit ? route('laporan.show', $laporan) : (request()->routeIs('lapor.form') ? route('lapor.form') : route('laporan.index')))

<form action="{{ $action }}" method="POST" enctype="multipart/form-data" class="mt-5 space-y-4">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <div class="form-group">
            <label for="nama_pelapor" class="mb-1 block text-sm font-semibold text-gray-800">Nama Pelapor <span class="text-red-600">*</span></label>
            <input id="nama_pelapor" name="nama_pelapor" type="text" value="{{ old('nama_pelapor', $laporan->nama_pelapor ?? '') }}" required maxlength="255" placeholder="Masukkan nama pelapor" class="flood-input">
            @error('nama_pelapor') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="no_hp" class="mb-1 block text-sm font-semibold text-gray-800">Nomor HP <span class="text-red-600">*</span></label>
            <input id="no_hp" name="no_hp" type="tel" value="{{ old('no_hp', $laporan->no_hp ?? '') }}" required maxlength="30" placeholder="Masukkan nomor telepon" class="flood-input">
            @error('no_hp') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="lokasi" class="mb-1 block text-sm font-semibold text-gray-800">Lokasi Kejadian <span class="text-red-600">*</span></label>
            <input id="lokasi" name="lokasi" type="text" value="{{ old('lokasi', $laporan->lokasi ?? '') }}" required maxlength="255" placeholder="Kecamatan / Desa" class="flood-input">
            @error('lokasi') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="kecamatan" class="mb-1 block text-sm font-semibold text-gray-800">Kecamatan <span class="text-red-600">*</span></label>
            <input id="kecamatan" name="kecamatan" type="text" value="{{ old('kecamatan', $laporan->kecamatan ?? '') }}" required maxlength="100" placeholder="Masukkan kecamatan" class="flood-input">
            @error('kecamatan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="tinggi_air" class="mb-1 block text-sm font-semibold text-gray-800">Tinggi Genangan Air (cm) <span class="text-red-600">*</span></label>
            <input id="tinggi_air" name="tinggi_air" type="number" min="0" step="1" value="{{ old('tinggi_air', $laporan->tinggi_air ?? '') }}" required placeholder="Masukkan tinggi genangan" class="flood-input">
            @error('tinggi_air') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="form-group">
            <label for="tanggal_kejadian" class="mb-1 block text-sm font-semibold text-gray-800">Tanggal Kejadian <span class="text-red-600">*</span></label>
            <input id="tanggal_kejadian" name="tanggal_kejadian" type="date" value="{{ old('tanggal_kejadian', isset($laporan) && $laporan->tanggal_kejadian ? $laporan->tanggal_kejadian->format('Y-m-d') : '') }}" required class="flood-input">
            @error('tanggal_kejadian') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        @if ($isEdit)
            <div class="form-group">
                <label for="status" class="mb-1 block text-sm font-semibold text-gray-800">Status</label>
                <select id="status" name="status" required class="flood-input">
                    @foreach (['Menunggu', 'Diproses', 'Selesai'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $laporan->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endif
    </div>

    <div class="form-group">
        <label for="keterangan" class="mb-1 block text-sm font-semibold text-gray-800">Keterangan Kejadian <span class="text-red-600">*</span></label>
        <textarea id="keterangan" name="keterangan" rows="3" required placeholder="Jelaskan kondisi banjir saat ini" class="flood-input">{{ old('keterangan', $laporan->keterangan ?? '') }}</textarea>
        @error('keterangan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="foto" class="mb-1 block text-sm font-semibold text-gray-800">Foto Kejadian @unless ($isEdit)<span class="text-red-600">*</span>@else<span class="font-normal text-gray-500">(opsional)</span>@endunless <span class="font-normal text-gray-500">(JPG/JPEG/PNG maks. 2 MB)</span></label>
        <input id="foto" name="foto" type="file" accept=".jpg,.jpeg,.png,image/jpeg,image/png" @required(!$isEdit) class="block w-full rounded-md border border-gray-200 bg-[#f2f7f5] text-sm text-gray-700 file:mr-4 file:border-0 file:bg-teal-100 file:px-4 file:py-2 file:text-sm file:font-medium">
        @error('foto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        @if ($isEdit && $laporan->foto)
            <img src="{{ route('laporan.foto', $laporan, false) }}" alt="Foto laporan saat ini" class="mt-3 h-32 w-48 rounded-md object-cover">
            <p class="mt-1 text-xs text-gray-500">Unggah foto baru untuk mengganti foto saat ini.</p>
        @endif
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        @unless (request()->routeIs('lapor.form'))
            <a href="{{ $cancelUrl }}" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
        @endunless
        <button type="submit" class="flood-button">
            {{ $isEdit ? 'Simpan Perubahan' : 'Kirim Laporan' }}
        </button>
    </div>
</form>
