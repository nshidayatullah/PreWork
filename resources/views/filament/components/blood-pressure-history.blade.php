<div class="overflow-x-auto">
    <!-- Mobile View (Card Layout) -->
    <div class="md:hidden space-y-4">
        @foreach($bloodPressures->sortByDesc('date') as $bp)
        @php
            // Check if date is today or yesterday
            $isToday = \Carbon\Carbon::parse($bp->date)->isToday();
            $isYesterday = \Carbon\Carbon::parse($bp->date)->isYesterday();

            // Indonesian date format
            $tanggalIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('DD MMMM YYYY');
            $hariIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('dddd');
            $waktuPemeriksaan = \Carbon\Carbon::parse($bp->created_at)->locale('id')->timezone(config('app.timezone'))->isoFormat('HH:mm');

            // Determine blood pressure category and color
            $category = '';
            $categoryClass = '';
            $sistole = $bp->sistole;
            $diastole = $bp->diastole;

            if ($sistole >= 180 || $diastole >= 120) {
                $category = 'Krisis Hipertensi';
                $categoryClass = 'bg-red-200 text-red-900 dark:bg-red-900 dark:text-red-100';
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

            // Status badge class
            $statusBadgeClass = match($bp->status) {
                'fit' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'unfit' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'under_observation' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200',
                default => 'bg-slate-100 text-slate-800 dark:bg-slate-900 dark:text-slate-200'
            };

            // Status label
            $statusLabel = match($bp->status) {
                'Fit To Work' => 'Fit To Work',
                'Fit With Medical Therapy' => 'Fit With Medical Therapy',
                'Unfit' => 'Unfit',
                'Observasi' => 'Observasi',
                default => ucfirst($bp->status),
            };
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border-l-4 {{ $isToday ? 'border-green-500' : ($isYesterday ? 'border-blue-400' : 'border-gray-200') }}">
            <div class="flex justify-between items-start">
                <div>
                    <div class="font-medium {{ $isToday ? 'text-green-700 dark:text-green-300' : ($isYesterday ? 'text-blue-700 dark:text-blue-300' : 'text-gray-900 dark:text-gray-100') }}">
                        {{ $tanggalIndonesia }}
                    </div>
                    <div class="text-xs {{ $isToday ? 'text-green-600 dark:text-green-400' : ($isYesterday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500') }}">
                        {{ $hariIndonesia }}, {{ $waktuPemeriksaan }}
                    </div>
                </div>
                @if($isToday)
                    <span class="px-2 py-1 bg-green-500 text-white text-xs rounded-full">Hari Ini</span>
                @elseif($isYesterday)
                    <span class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">Kemarin</span>
                @endif
            </div>

            <div class="mt-3 grid grid-cols-2 gap-2">
                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Sistole</div>
                    <div class="font-semibold text-lg {{ $sistole >= 140 ? 'text-red-600 dark:text-red-400' : ($sistole >= 130 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                        {{ $sistole }} <span class="text-xs">mmHg</span>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 p-2 rounded">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Diastole</div>
                    <div class="font-semibold text-lg {{ $diastole >= 90 ? 'text-red-600 dark:text-red-400' : ($diastole >= 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                        {{ $diastole }} <span class="text-xs">mmHg</span>
                    </div>
                </div>
            </div>

            <div class="mt-3 flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-medium {{ $categoryClass }}">
                    {{ $category }}
                </span>
                <span class="px-2 py-1 rounded text-xs font-medium {{ $statusBadgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>

            @if($bp->notes)
            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                <i class="fas fa-sticky-note mr-1"></i>
                {{ \Str::limit($bp->notes, 50) }}
            </div>
            @endif
        </div>
        @endforeach

        @if($bloodPressures->isEmpty())
        <div class="bg-white dark:bg-gray-800 p-8 text-center rounded-lg border border-gray-200 dark:border-gray-700">
            <i class="fas fa-heartbeat text-4xl mb-3 text-gray-300"></i>
            <p class="text-lg font-medium text-gray-700 dark:text-gray-300">Belum Ada Riwayat Pemeriksaan</p>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Data riwayat tekanan darah akan muncul setelah pemeriksaan dilakukan</p>
        </div>
        @endif
    </div>

    <!-- Desktop View (Table Layout) -->
    <div class="hidden md:block">
        <table class="w-full text-sm border border-gray-200 dark:border-gray-700 rounded-xl">
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-900">
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Tanggal Pemeriksaan</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Sistole</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Diastole</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Kategori</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Status</th>
                    <th class="text-left py-3 px-4 font-medium text-gray-700 dark:text-gray-300">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bloodPressures->sortByDesc('date') as $bp)
                @php
                    $isToday = \Carbon\Carbon::parse($bp->date)->isToday();
                    $isYesterday = \Carbon\Carbon::parse($bp->date)->isYesterday();
                    $rowBgClass = $isToday ? 'bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500' :
                                ($isYesterday ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400' :
                                'hover:bg-gray-50 dark:hover:bg-gray-800');

                    // Duplicate variable definitions for table view
                    $tanggalIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('DD MMMM YYYY');
                    $hariIndonesia = \Carbon\Carbon::parse($bp->date)->locale('id')->isoFormat('dddd');
                    $waktuPemeriksaan = \Carbon\Carbon::parse($bp->created_at)->locale('id')->timezone(config('app.timezone'))->isoFormat('HH:mm');

                    $sistole = $bp->sistole;
                    $diastole = $bp->diastole;

                    if ($sistole >= 180 || $diastole >= 120) {
                        $category = 'Krisis Hipertensi';
                        $categoryClass = 'bg-red-200 text-red-900 dark:bg-red-900 dark:text-red-100';
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
                <tr class="border-b border-gray-100 dark:border-gray-800 {{ $rowBgClass }}">
                    <td class="py-3 px-4">
                        <div class="font-medium {{ $isToday ? 'text-green-700 dark:text-green-300' : ($isYesterday ? 'text-blue-700 dark:text-blue-300' : '') }}">
                            {{ $tanggalIndonesia }}
                            @if($isToday)
                                <span class="ml-2 px-2 py-1 bg-green-500 text-white text-xs rounded-full">Hari Ini</span>
                            @elseif($isYesterday)
                                <span class="ml-2 px-2 py-1 bg-blue-500 text-white text-xs rounded-full">Kemarin</span>
                            @endif
                        </div>
                        <div class="text-xs {{ $isToday ? 'text-green-600 dark:text-green-400' : ($isYesterday ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500') }}">
                            {{ $hariIndonesia }}
                        </div>
                    </td>

                    <td class="py-3 px-4">
                        <div class="font-semibold text-lg {{ $sistole >= 140 ? 'text-red-600 dark:text-red-400' : ($sistole >= 130 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                            {{ $sistole }}
                        </div>
                        <div class="text-xs text-gray-500">mmHg</div>
                    </td>

                    <td class="py-3 px-4">
                        <div class="font-semibold text-lg {{ $diastole >= 90 ? 'text-red-600 dark:text-red-400' : ($diastole >= 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-900 dark:text-gray-100') }}">
                            {{ $diastole }}
                        </div>
                        <div class="text-xs text-gray-500">mmHg</div>
                    </td>

                    <td class="py-3 px-4">
                        <span class="px-3 py-1 rounded-full text-xs font-medium {{ $categoryClass }}">
                            {{ $category }}
                        </span>
                    </td>

                    <td class="py-3 px-4">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $statusBadgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </td>

                    <td class="py-3 px-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $waktuPemeriksaan }}
                        </div>
                        @if($bp->notes)
                            <div class="text-xs text-gray-500 mt-1" title="{{ $bp->notes }}">
                                <i class="fas fa-sticky-note mr-1"></i>
                                {{ \Str::limit($bp->notes, 20) }}
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach

                @if($bloodPressures->isEmpty())
                <tr>
                    <td colspan="7" class="py-8 px-4 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-heartbeat text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium">Belum Ada Riwayat Pemeriksaan</p>
                            <p class="text-sm">Data riwayat tekanan darah akan muncul setelah pemeriksaan dilakukan</p>
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

    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 mt-6 mb-6">
        <!-- Summary Cards -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="text-sm text-gray-600 dark:text-gray-400">Total Pemeriksaan</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $bloodPressures->count() }}</div>
        </div>

        <!-- Card Terakhir Diperiksa yang Diperbaiki -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
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
                        {{ $checkDateTime->locale('id')->isoFormat('DD MMMM YYYY') }}
                    @endif
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $checkDateTime->locale('id')->isoFormat('dddd, HH:mm') }} WIB
                </div>
            @else
                <div class="text-lg font-bold text-gray-900 dark:text-gray-100">-</div>
            @endif
        </div>

        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
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
