<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-filament::icon icon="heroicon-o-calendar-days" class="w-6 h-6 text-primary-500" />
                <span>Kunjungan Terbaru & Filter</span>
            </div>
        </x-slot>

        <x-slot name="description">
            Cari data pengunjung berdasarkan ruangan rapat dan tanggal kunjungan.
        </x-slot>

        <!-- Filters Form -->
        <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-800">
            {{ $this->form }}
        </div>

        <!-- Grid Cards (Individual Visits) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($this->visits as $visit)
                <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden shadow-sm flex flex-col h-full hover:shadow-md transition-shadow">
                    
                    @if(empty($this->data['department_id']))
                        <!-- Header: Ruangan -->
                        <div class="bg-gray-50 dark:bg-gray-800 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <div class="flex items-center gap-2 text-primary-600 dark:text-primary-400">
                                <x-filament::icon icon="heroicon-o-building-office-2" class="w-5 h-5" />
                                <h3 class="font-bold text-sm truncate" title="{{ $visit->department->name ?? '-' }}">
                                    {{ $visit->department->name ?? '-' }}
                                </h3>
                            </div>
                        </div>
                    @endif

                    <!-- Body: Pengunjung -->
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 rounded-full bg-primary-100 dark:bg-primary-900/50 flex items-center justify-center text-primary-600 dark:text-primary-400 font-bold text-lg border border-primary-200 dark:border-primary-800">
                                    {{ substr($visit->guest->name ?? 'G', 0, 2) }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0 pt-1">
                                <p class="text-base font-bold text-gray-900 dark:text-white truncate">
                                    {{ $visit->guest->name ?? '-' }}
                                </p>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 truncate mt-0.5">
                                    {{ $visit->guest->agency_name ?: 'Personal / Umum' }}
                                </p>
                            </div>
                        </div>

                        <!-- Footer Info -->
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between text-xs">
                            <div class="flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                <x-filament::icon icon="heroicon-o-clock" class="w-4 h-4" />
                                <span>{{ \Carbon\Carbon::parse($visit->visit_time)->format('H:i') }}</span>
                            </div>
                            <span class="font-semibold px-2.5 py-1 rounded-full bg-gray-100 dark:bg-gray-800 {{ match($visit->status) {
                                'completed' => 'text-success-600 dark:text-success-400',
                                'pending' => 'text-warning-600 dark:text-warning-400',
                                'rejected' => 'text-danger-600 dark:text-danger-400',
                                default => 'text-primary-600 dark:text-primary-400'
                            } }}">
                                {{ match($visit->status) {
                                    'completed' => 'Selesai',
                                    'pending' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    default => ucfirst($visit->status)
                                } }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-12 text-gray-400 dark:text-gray-500 bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 border-dashed">
                    <x-filament::icon icon="heroicon-o-inbox" class="w-12 h-12 mb-3 opacity-30" />
                    <p class="text-base font-medium">Tidak ada data pengunjung</p>
                    <p class="text-sm opacity-70 mt-1">Belum ada kunjungan pada tanggal atau ruangan tersebut.</p>
                </div>
            @endforelse
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
