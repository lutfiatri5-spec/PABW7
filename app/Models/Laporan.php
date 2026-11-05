<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Laporan extends Model
{
    protected $table = 'laporan';

    protected $fillable = [
        'user_id',
        'nama_pelapor',
        'no_hp',
        'lokasi',
        'kecamatan',
        'tinggi_air',
        'tanggal_kejadian',
        'keterangan',
        'foto',
        'status',
    ];

    protected static function booted(): void
    {
        static::creating(function (Laporan $laporan): void {
            $laporan->tracking_token ??= Str::random(40);
        });
    }

    protected function casts(): array
    {
        return [
            'tanggal_kejadian' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}