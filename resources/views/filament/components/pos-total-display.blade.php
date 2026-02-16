<div class="fi-fo-placeholder">
    <div class="grid gap-y-2">
        <div class="flex items-center justify-between gap-x-3">
            <span class="text-sm font-medium text-gray-700 dark:text-gray-200">
                TOTAL ORDER
            </span>
        </div>
        <div class="flex items-center gap-x-3">
            <div class="flex-1">
                <div class="rounded-lg bg-gradient-to-r from-primary-500 to-primary-600 p-4 shadow-lg">
                    <div class="text-center">
                        <p class="text-sm font-medium text-primary-100 mb-1">Total Pembayaran</p>
                        <p class="text-3xl font-bold text-white tracking-tight">
                            Rp {{ number_format($getTotal(), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
