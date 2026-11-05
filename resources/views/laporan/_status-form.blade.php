<form action="{{ route('laporan.status.update', $laporan) }}" method="POST" class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
    @csrf
    @method('PATCH')
    <p class="mb-2 text-sm font-semibold text-gray-800">Status sekarang: {{ $laporan->status }}</p>
    @if (! in_array($laporan->status, ['Diproses', 'Selesai'], true))
        <button type="submit" name="status" value="Diproses" class="mt-2 inline-flex items-center rounded-md bg-blue-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-800">
            Tandai Diproses
        </button>
    @endif
    @if ($laporan->status !== 'Selesai')
        <button type="submit" name="status" value="Selesai" class="mt-2 inline-flex w-full items-center justify-center rounded-md bg-green-700 px-4 py-3 text-sm font-bold text-white transition hover:bg-green-800">
            Tandai Selesai
        </button>
    @else
        <p class="mt-2 rounded-md bg-green-100 px-4 py-3 text-center text-sm font-bold text-green-800">
            Laporan sudah selesai ditangani
        </p>
    @endif
    @error('status')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</form>
