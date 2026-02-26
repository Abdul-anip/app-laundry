<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class OrderCodeGenerator
{
    /**
     * Generate order code yang unik dan aman dari race condition.
     *
     * Menggunakan SELECT ... FOR UPDATE untuk database-level locking,
     * sehingga dua request bersamaan TIDAK akan mendapatkan kode yang sama.
     *
     * Format: LDRY-{YYYY}-{XXXX}
     * Contoh: LDRY-2026-0042
     *
     * PENTING: Method ini HARUS dipanggil di dalam DB::transaction() yang sudah ada.
     *
     * @param int|null $year Tahun target (default: tahun berjalan)
     * @return string Order code yang unik
     */
    public static function generate(?int $year = null): string
    {
        $year = $year ?? (int) date('Y');

        // INSERT OR IGNORE: buat row untuk tahun ini jika belum ada
        // Aman dijalankan berkali-kali (idempotent)
        DB::table('order_sequences')->insertOrIgnore([
            'year'        => $year,
            'last_number' => 0,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // ATOMIC: Lock row ini agar tidak ada request lain yang bisa membaca
        // nilai yang sama sebelum kita increment.
        // SELECT ... FOR UPDATE memblokir semua reader/writer lain pada row ini
        // hingga transaksi kita selesai (commit/rollback).
        $sequence = DB::table('order_sequences')
            ->where('year', $year)
            ->lockForUpdate()
            ->first();

        $newNumber = $sequence->last_number + 1;

        // Batas 9999 per tahun (4 digit). Jika melebihi, tambah digit otomatis.
        // Dengan 9999 order/tahun, ini cukup untuk laundry skala apapun.
        // Jika perlu lebih, ubah str_pad length di bawah menjadi 5.
        DB::table('order_sequences')
            ->where('year', $year)
            ->update([
                'last_number' => $newNumber,
                'updated_at'  => now(),
            ]);

        return 'LDRY-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Peek nomor terakhir tanpa increment (untuk display/preview saja).
     * JANGAN gunakan ini untuk generate order code aktual.
     */
    public static function peek(?int $year = null): int
    {
        $year = $year ?? (int) date('Y');

        $sequence = DB::table('order_sequences')
            ->where('year', $year)
            ->first();

        return $sequence?->last_number ?? 0;
    }
}