<div class="overflow-x-auto">
    <!-- Full Table Layout for all screen sizes -->
    <div>
        <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Tanggal</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Sistole</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Diastole</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Kategori</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Status</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Waktu</th>
                    @if($bloodPressures->contains('notes'))
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Catatan</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($bloodPressures->sortByDesc('date') as $bp)
                @php
                    $isToday = \Carbon\Carbon::parse($bp->date)->isToday();
                    $isYesterday = \Carbon\Carbon::parse($bp->date)->isYesterday();
                    $rowBgClass = $isToday ? 'bg-green-50 dark:bg-green-900/20' :
                                ($isYesterday ? 'bg-blue-50 dark:bg-blue-900/20' :
                                'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-750');
                    $borderLeftClass = $isToday ? 'border-l-4 border-l-green-500' :
                                ($isYesterday ? 'border-l-4 border-l-blue-400' : 'border-l-4 border-l-transparent');

                    $tanggalIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('DD MMMM YYYY');
                    $hariIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('dddd');
                    $waktuPemeriksaan = \Carbon\Carbon::parse($bp->created_at)->locale('id')->timezone(config('app.timezone'))->isoFormat('HH:mm');

                    $sistole = $bp->sistole;
                    $diastole = $bp->diastole;

                    if ($sistole >= 180 || $diastole >= 120) {
                        $category = 'Krisis Hipertensi';
                        $categoryClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                    } elseif ($sistole >= 140 || $diastole >= 90) {
                        $category = 'Hipertensi';
                        $categoryClass = 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
                    } elseif ($sistole >= 130 || $diastole >= 80) {
                        $category = 'Pra-Hipertensi';
                        $categoryClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
                    } elseif ($sistole >= 120) {
                        $category = 'Normal Tinggi';
                        $categoryClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
                    } else {
                        $category = 'Normal';
                        $categoryClass = 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
                    }

                    $statusBadgeClass = match($bp->status) {
                        'fit' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                        'unfit' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                        'under_observation' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                        'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                        default => 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-200'
                    };

                    $statusLabel = match($bp->status) {
                        'Fit To Work' => 'Fit To Work',
                        'Fit With Medical Therapy' => 'Fit With Medical Therapy',
                        'Unfit' => 'Unfit',
                        'Observasi' => 'Observasi',
                        default => ucfirst($bp->status),
                    };
                @endphp
                <tr class="{{ $rowBgClass }} {{ $borderLeftClass }} transition-colors duration-150">
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="font-medium {{ $isToday ? 'text-green-700 dark:text-green-300' : ($isYesterday ? 'text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-100') }}">
                                {{ \Carbon\Carbon::parse($bp->date)->format('d/m/Y') }}
                            </div>
                            @if($isToday)
                                <span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full">Hari Ini</span>
                            @elseif($isYesterday)
                                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded-full">Kemarin</span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ $hariIndonesia }}
                        </div>
                    </td>

                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center">
                            <span class="font-semibold text-lg {{ $sistole >= 140 ? 'text-red-600 dark:text-red-400' : ($sistole >= 130 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                                {{ $sistole }}
                            </span>
                            <span class="ml-1 text-xs text-gray-500">mmHg</span>
                        </div>
                    </td>

                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="flex items-center">
                            <span class="font-semibold text-lg {{ $diastole >= 90 ? 'text-red-600 dark:text-red-400' : ($diastole >= 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                                {{ $diastole }}
                            </span>
                            <span class="ml-1 text-xs text-gray-500">mmHg</span>
                        </div>
                    </td>

                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $categoryClass }} inline-flex items-center">
                            {{ $category }}
                        </span>
                    </td>

                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $statusBadgeClass }} inline-flex items-center">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $waktuPemeriksaan }}
                        </div>
                    </td>

                    @if($bloodPressures->contains('notes'))
                    <td class="py-4 px-4 border-b border-gray-100 dark:border-gray-700 max-w-xs">
                        @if($bp->notes)
                            <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2" title="{{ $bp->notes }}">
                                {{ $bp->notes }}
                            </div>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 italic">-</span>
                        @endif
                    </td>
                    @endif
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-12 px-4 text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex flex-col items-center justify-center">
                            <i class="fas fa-heartbeat text-5xl mb-4 text-gray-300"></i>
                            <p class="text-xl font-medium mb-2">Belum Ada Riwayat Pemeriksaan</p>
                            <p class="text-gray-500 dark:text-gray-400 max-w-md">
                                Data riwayat tekanan darah akan muncul setelah pemeriksaan dilakukan
                            </p>
                        </div>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if($bloodPressures->count() > 0)
    @php
        // Definisikan variabel di awal untuk digunakan di seluruh section
        $latestBP = $bloodPressures->sortByDesc('created_at')->first();
        $avgSistole = $bloodPressures->avg('sistole');
        $avgDiastole = $bloodPressures->avg('diastole');
    @endphp

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <!-- Summary Cards -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Pemeriksaan</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $bloodPressures->count() }}</div>
        </div>

        <!-- Card Terakhir Diperiksa yang Diperbaiki -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="text-sm text-gray-600 dark:text-gray-400">Terakhir Diperiksa</div>
            @if ($latestBP)
                @php
                    $checkDateTime = \Carbon\Carbon::parse($latestBP->created_at)->timezone(config('app.timezone'));
                @endphp
                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    @if ($checkDateTime->isToday())
                        Hari ini
                    @elseif ($checkDateTime->isYesterday())
                        Kemarin
                    @else
                        {{ $checkDateTime->locale('id')->isoFormat('DD MMM') }}
                    @endif
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $checkDateTime->locale('id')->isoFormat('HH:mm') }} WITA
                </div>
            @else
                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">-</div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="text-sm text-gray-600 dark:text-gray-400">Rata-rata</div>
            <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ number_format($avgSistole, 0) }}/{{ number_format($avgDiastole, 0) }} mmHg
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
            Menampilkan {{ $bloodPressures->count() }} riwayat pemeriksaan tekanan darah
        </p>
    </div>
    @endif
</div>
