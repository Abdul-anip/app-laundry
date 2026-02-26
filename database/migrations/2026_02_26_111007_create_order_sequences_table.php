<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel ini menyimpan counter per tahun untuk generate order code secara atomic.
     * Menggunakan pendekatan "dedicated sequence table" untuk menghindari race condition.
     */
    public function up(): void
    {
        Schema::create('order_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year')->unique(); // e.g. 2026
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });

        // Seed sequence untuk tahun berjalan berdasarkan data order yang sudah ada
        $currentYear = (int) date('Y');
        $lastOrder = DB::table('orders')
            ->whereYear('created_at', $currentYear)
            ->orderByDesc('id')
            ->value('order_code');

        $lastNumber = 0;
        if ($lastOrder) {
            // Ambil 4 digit terakhir dari kode seperti "LDRY-2026-0042"
            $parts = explode('-', $lastOrder);
            $lastNumber = (int) end($parts);
        }

        DB::table('order_sequences')->insert([
            'year'        => $currentYear,
            'last_number' => $lastNumber,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sequences');
    }
};