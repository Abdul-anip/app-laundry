<x-filament-panels::page>
    <x-filament::section>
        {{ $this->form }}
    </x-filament::section>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total_orders'] }}</p>
                </div>
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <x-heroicon-o-shopping-bag class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Revenue</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($this->stats['total_revenue'], 0, ',', '.') }}</p>
                </div>
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-full">
                    <x-heroicon-o-banknotes class="w-6 h-6 text-green-600 dark:text-green-400" />
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Weight</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['total_weight'] }} kg</p>
                </div>
                <div class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <x-heroicon-o-scale class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                </div>
            </div>
        </x-filament::section>

        <x-filament::section>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Online / Offline</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $this->stats['online_orders'] }} / {{ $this->stats['offline_orders'] }}</p>
                </div>
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <x-heroicon-o-users class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                </div>
            </div>
        </x-filament::section>
    </div>

    {{ $this->table }}
</x-filament-panels::page>
