<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(): View
    {
        $laporans = Laporan::latest()->paginate(10);

        return view('laporan.index', compact('laporans'));
    }

    public function publicForm(): View
    {
        return view('laporan.public');
    }

    public function history(): View
    {
        $laporans = auth()->user()
            ->laporans()
            ->latest()
            ->paginate(8);

        return view('laporan.history', compact('laporans'));
    }

    public function track(string $trackingToken): View
    {
        $laporan = Laporan::where('tracking_token', $trackingToken)->firstOrFail();
        $user = auth()->user();

        abort_unless($user && ! $user->isPetugas() && $laporan->user_id === $user->id, 404);

        return view('laporan.track', compact('laporan'));
    }

    public function submitByWarga(Request $request): RedirectResponse
    {
        $laporan = $this->createLaporan($request);

        if ($request->user() && ! $request->user()->isPetugas()) {
            return redirect()
                ->route('riwayat.index')
                ->with('success', 'Laporan banjir berhasil dikirim. Anda dapat melihat riwayat laporan di bawah ini.');
        }

        return redirect()
            ->route('lapor.form')
            ->with('success', 'Laporan banjir berhasil dikirim. Terima kasih atas informasinya.');
    }

    public function create(): View
    {
        return view('laporan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $laporan = $this->createLaporan($request);

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan banjir berhasil ditambahkan.');
    }

    private function createLaporan(Request $request): Laporan
    {
        $validated = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'tinggi_air' => ['required', 'integer', 'min:0'],
            'tanggal_kejadian' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'foto' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }

        $validated['user_id'] = $request->user()?->id;

        return Laporan::create($validated);
    }

    public function show(Laporan $laporan): View
    {
        return view('laporan.show', compact('laporan'));
    }

    public function foto(Laporan $laporan): StreamedResponse
    {
        abort_unless($laporan->foto && Storage::disk('public')->exists($laporan->foto), 404);

        return Storage::disk('public')->response($laporan->foto);
    }

    public function edit(Laporan $laporan): View
    {
        return view('laporan.edit', compact('laporan'));
    }

    public function update(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'nama_pelapor' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:30'],
            'lokasi' => ['required', 'string', 'max:255'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'tinggi_air' => ['required', 'integer', 'min:0'],
            'tanggal_kejadian' => ['required', 'date'],
            'keterangan' => ['required', 'string'],
            'status' => ['required', 'in:Menunggu,Diproses,Selesai'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($laporan->status === 'Selesai' && $validated['status'] !== 'Selesai') {
            return back()
                ->withErrors(['status' => 'Status laporan yang sudah selesai tidak dapat diubah kembali.'])
                ->withInput();
        }

        $fotoLama = $laporan->foto;
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('laporan', 'public');
        }

        $laporan->update($validated);

        if (isset($validated['foto']) && $fotoLama) {
            Storage::disk('public')->delete($fotoLama);
        }

        return redirect()
            ->route('laporan.show', $laporan)
            ->with('success', 'Laporan banjir berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Laporan $laporan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:Menunggu,Diproses,Selesai'],
        ]);

        if ($laporan->status === 'Selesai' && $validated['status'] !== 'Selesai') {
            return back()
                ->withErrors(['status' => 'Status laporan yang sudah selesai tidak dapat diubah kembali.']);
        }

        $laporan->update($validated);

        return back()->with('success', 'Status penanganan laporan berhasil diperbarui.');
    }

    public function destroy(Laporan $laporan): RedirectResponse
    {
        $foto = $laporan->foto;
        $laporan->delete();

        if ($foto) {
            Storage::disk('public')->delete($foto);
        }

        return redirect()
            ->route('laporan.index')
            ->with('success', 'Laporan banjir berhasil dihapus.');
    }
}
