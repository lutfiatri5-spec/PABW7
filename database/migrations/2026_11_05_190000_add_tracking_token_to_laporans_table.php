<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->string('tracking_token', 64)->nullable()->unique();
        });

        DB::table('laporan')
            ->whereNull('tracking_token')
            ->orderBy('id')
            ->chunkById(100, function ($laporans): void {
                foreach ($laporans as $laporan) {
                    DB::table('laporan')
                        ->where('id', $laporan->id)
                        ->update(['tracking_token' => Str::random(40)]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropUnique(['tracking_token']);
            $table->dropColumn('tracking_token');
        });
    }
};
