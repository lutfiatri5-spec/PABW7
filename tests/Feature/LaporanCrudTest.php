<?php

namespace Tests\Feature;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaporanCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_list_and_details_are_available(): void
    {
        $laporan = Laporan::create($this->reportData());

        $this->actingAs($this->createPetugas())
            ->get(route('laporan.index'))
            ->assertOk()
            ->assertSee('Status sekarang: Menunggu')
            ->assertSee('Tandai Selesai')
            ->assertSee('Tandai Diproses')
            ->assertSee($laporan->lokasi)
            ->assertSeeInOrder(['Tandai Diproses', 'Tandai Selesai']);

        $this->get(route('laporan.show', $laporan))
            ->assertOk()
            ->assertSee($laporan->nama_pelapor)
            ->assertSee($laporan->keterangan);
    }

    public function test_report_can_be_created_with_an_uploaded_photo(): void
    {
        Storage::fake('public');

        $response = $this->post(route('lapor.submit'), [
            ...$this->reportData(),
            'foto' => $this->fakePng('banjir.png'),
        ]);

        $laporan = Laporan::firstOrFail();

        $response->assertRedirect(route('lapor.form'));
        $this->assertDatabaseHas('laporan', ['lokasi' => 'Jalan Merdeka']);
        Storage::disk('public')->assertExists($laporan->foto);
        $this->assertSame('Menunggu', $laporan->status);

        $this->get(route('laporan.foto', $laporan))->assertOk();
        $this->get(route('laporan.track', $laporan->tracking_token))
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_warga_is_redirected_to_only_their_report_history(): void
    {
        Storage::fake('public');
        $warga = User::factory()->create();
        $laporanOrangLain = Laporan::create([
            ...$this->reportData(),
            'nama_pelapor' => 'Laporan Pengguna Lain',
        ]);

        $response = $this->actingAs($warga)->post(route('lapor.submit'), [
            ...$this->reportData(),
            'foto' => $this->fakePng('laporan-warga.png'),
        ]);

        $laporanMilikWarga = Laporan::where('user_id', $warga->id)->firstOrFail();

        $response->assertRedirect(route('riwayat.index'));
        $this->assertSame($warga->id, $laporanMilikWarga->user_id);
        $this->assertNull($laporanOrangLain->user_id);

        $this->get(route('riwayat.index'))
            ->assertOk()
            ->assertSee($laporanMilikWarga->lokasi)
            ->assertSee('Lihat status terbaru')
            ->assertDontSee('Laporan Pengguna Lain');

        $this->get(route('laporan.track', $laporanMilikWarga->tracking_token))
            ->assertOk()
            ->assertSee('Status terbaru')
            ->assertSee('Menunggu');

        $this->get(route('laporan.track', $laporanOrangLain->tracking_token))
            ->assertNotFound();
    }

    public function test_report_management_requires_authentication(): void
    {
        $laporan = Laporan::create($this->reportData());

        $this->get(route('laporan.index'))->assertRedirect(route('login'));
        $this->get(route('laporan.show', $laporan))->assertRedirect(route('login'));
        $this->get(route('laporan.edit', $laporan))->assertRedirect(route('login'));
        $this->delete(route('laporan.destroy', $laporan))->assertRedirect(route('login'));
    }

    public function test_public_form_shows_only_the_form_even_when_reports_exist(): void
    {
        Laporan::create($this->reportData());

        $this->get(route('lapor.form'))
            ->assertOk()
            ->assertSee('Form Laporan')
            ->assertDontSee('Batal')
            ->assertDontSee('Laporan Banjir Terbaru');
    }

    public function test_logged_in_warga_cannot_manage_reports(): void
    {
        $laporan = Laporan::create($this->reportData());

        $this->actingAs(User::factory()->create())
            ->get(route('laporan.index'))
            ->assertForbidden();

        $this->actingAs(User::factory()->create())
            ->get(route('laporan.show', $laporan))
            ->assertForbidden();

        $this->actingAs($this->createPetugas())
            ->get(route('laporan.track', $laporan->tracking_token))
            ->assertNotFound();
    }

    public function test_report_can_be_updated_and_deleted(): void
    {
        Storage::fake('public');
        $fotoLama = $this->fakePng('lama.png')->store('laporan', 'public');
        $laporan = Laporan::create([...$this->reportData(), 'foto' => $fotoLama]);

        $this->actingAs($this->createPetugas())
            ->put(route('laporan.update', $laporan), [
                ...$this->reportData(),
                'lokasi' => 'Jalan Baru',
                'status' => 'Diproses',
                'foto' => $this->fakePng('baru.png'),
            ])->assertRedirect(route('laporan.show', $laporan));

        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'lokasi' => 'Jalan Baru',
            'status' => 'Diproses',
        ]);
        Storage::disk('public')->assertMissing($fotoLama);
        $fotoBaru = $laporan->fresh()->foto;
        Storage::disk('public')->assertExists($fotoBaru);

        $this->delete(route('laporan.destroy', $laporan))
            ->assertRedirect(route('laporan.index'));

        $this->assertDatabaseMissing('laporan', ['id' => $laporan->id]);
        Storage::disk('public')->assertMissing($fotoBaru);
    }

    public function test_petugas_can_update_status_directly_from_report_page(): void
    {
        $warga = User::factory()->create();
        $laporan = Laporan::create([...$this->reportData(), 'user_id' => $warga->id]);

        $this->actingAs($this->createPetugas())
            ->from(route('laporan.index'))
            ->patch(route('laporan.status.update', $laporan), [
                'status' => 'Diproses',
            ])
            ->assertRedirect(route('laporan.index'))
            ->assertSessionHas('success', 'Status penanganan laporan berhasil diperbarui.');

        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Diproses',
        ]);

        $this->patch(route('laporan.status.update', $laporan), [
            'status' => 'Selesai',
        ])->assertRedirect(route('laporan.index'));

        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Selesai',
        ]);

        $this->actingAs($this->createPetugas())
            ->get(route('laporan.index'))
            ->assertOk()
            ->assertSee('Laporan sudah selesai ditangani')
            ->assertDontSee('Tandai Selesai')
            ->assertDontSee('Tandai Diproses');

        $this->actingAs($warga)
            ->get(route('laporan.track', $laporan->tracking_token))
            ->assertOk()
            ->assertSee('Selesai')
            ->assertSee('Laporan ini telah selesai ditangani.');
    }

    public function test_completed_report_cannot_be_reopened_as_in_progress(): void
    {
        $laporan = Laporan::create([
            ...$this->reportData(),
            'status' => 'Selesai',
        ]);

        $this->actingAs($this->createPetugas())
            ->from(route('laporan.index'))
            ->patch(route('laporan.status.update', $laporan), [
                'status' => 'Diproses',
            ])
            ->assertRedirect(route('laporan.index'))
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('laporan', [
            'id' => $laporan->id,
            'status' => 'Selesai',
        ]);
    }

    public function test_report_requires_valid_details_and_photo_types(): void
    {
        $this->from(route('lapor.form'))
            ->post(route('lapor.submit'), [
                ...$this->reportData(),
                'tinggi_air' => -1,
                'foto' => UploadedFile::fake()->create('dokumen.pdf', 10, 'application/pdf'),
            ])
            ->assertSessionHasErrors(['tinggi_air', 'foto']);
    }

    private function reportData(): array
    {
        return [
            'nama_pelapor' => 'Budi Santoso',
            'no_hp' => '08123456789',
            'lokasi' => 'Jalan Merdeka',
            'kecamatan' => 'Sukasari',
            'tinggi_air' => 50,
            'tanggal_kejadian' => now()->toDateString(),
            'keterangan' => 'Air mulai memasuki halaman rumah.',
        ];
    }

    private function fakePng(string $name): UploadedFile
    {
        $contents = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+jN5sAAAAASUVORK5CYII=');

        return UploadedFile::fake()->createWithContent($name, $contents);
    }

    private function createPetugas(): User
    {
        return tap(User::factory()->create(), function (User $user): void {
            $user->forceFill(['role' => 'petugas'])->save();
        });
    }
}
