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

        return DB::transaction(function () use ($year) {
            // INSERT OR IGNORE: buat row untuk tahun ini jika belum ada
            DB::table('order_sequences')->insertOrIgnore([
                'year'        => $year,
                'last_number' => 0,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            // ATOMIC: Lock row ini agar tidak ada request lain yang bisa membaca
            // Jika driver db mendukung skipLocked/noWait, lebih baik. Tapi ini cara teraman.
            $sequence = DB::table('order_sequences')
                ->where('year', $year)
                ->lockForUpdate()
                ->first();

            $newNumber = $sequence->last_number + 1;

            DB::table('order_sequences')
                ->where('year', $year)
                ->update([
                    'last_number' => $newNumber,
                    'updated_at'  => now(),
                ]);

            return 'LDRY-' . $year . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }, 3); // 3 adalah jumlah retries jika terjadi deadlock
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