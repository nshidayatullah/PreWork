<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Karyawan Belum Periksa - {{ date('d/m/Y', strtotime($today)) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21a8',
                            900: '#581c87',
                            950: '#3b0764'
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        /* Mobile Compact Styles */
        @media (max-width: 768px) {
            .mobile-compact-header {
                padding: 16px 0 !important;
            }

            .mobile-compact-title {
                font-size: 18px !important;
                line-height: 24px !important;
            }

            .mobile-compact-subtitle {
                font-size: 12px !important;
            }

            .mobile-compact-date {
                font-size: 14px !important;
            }

            .mobile-compact-time {
                font-size: 11px !important;
            }

            .mobile-stats-grid {
                grid-template-columns: repeat(2, 1fr) !important;
                gap: 12px !important;
                margin-bottom: 16px !important;
            }

            .mobile-stat-card {
                padding: 12px !important;
                border-radius: 8px !important;
            }

            .mobile-stat-icon {
                padding: 8px !important;
                width: 32px !important;
                height: 32px !important;
            }

            .mobile-stat-icon i {
                font-size: 14px !important;
            }

            .mobile-stat-label {
                font-size: 10px !important;
                margin-bottom: 2px !important;
            }

            .mobile-stat-value {
                font-size: 18px !important;
                font-weight: 700 !important;
            }

            .mobile-progress-card {
                padding: 12px !important;
                margin-bottom: 16px !important;
            }

            .mobile-progress-title {
                font-size: 14px !important;
                margin-bottom: 8px !important;
            }

            .mobile-progress-bar {
                height: 6px !important;
            }

            .mobile-table-header {
                padding: 12px 16px !important;
            }

            .mobile-table-title {
                font-size: 16px !important;
            }

            .mobile-buttons {
                gap: 8px !important;
            }

            .mobile-button {
                padding: 8px 12px !important;
                font-size: 12px !important;
                border-radius: 6px !important;
            }

            .mobile-card-item {
                padding: 12px !important;
                margin-bottom: 8px !important;
                border-radius: 8px !important;
            }

            .mobile-card-header {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 8px;
            }

            .mobile-card-info {
                flex: 1;
            }

            .mobile-card-badges {
                display: flex;
                flex-direction: column;
                gap: 4px;
                align-items: flex-end;
            }

            .mobile-card-name {
                font-size: 14px !important;
                font-weight: 600 !important;
                line-height: 18px !important;
                margin-bottom: 2px;
            }

            .mobile-card-nrp {
                font-size: 11px !important;
                color: #7c3aed !important;
                font-weight: 500 !important;
            }

            .mobile-card-details {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 8px;
                margin-top: 8px;
            }

            .mobile-detail-item {
                font-size: 10px !important;
            }

            .mobile-detail-label {
                color: #6b7280 !important;
                text-transform: uppercase;
                font-weight: 500;
                margin-bottom: 1px;
            }

            .mobile-detail-value {
                color: #374151 !important;
                font-weight: 500;
            }

            .mobile-badge {
                padding: 3px 8px !important;
                font-size: 9px !important;
                border-radius: 12px !important;
                font-weight: 600 !important;
                white-space: nowrap;
            }

            .mobile-footer {
                margin-top: 24px !important;
                padding: 12px 0 !important;
            }

            .mobile-footer-content {
                padding: 12px !important;
            }

            .mobile-quote {
                padding: 8px !important;
                margin: 8px 0 !important;
            }

            .mobile-quote-text {
                font-size: 12px !important;
                line-height: 16px !important;
                margin-bottom: 6px !important;
            }

            .mobile-quote-author {
                font-size: 9px !important;
            }

            .mobile-quote-mark {
                font-size: 16px !important;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 mobile-compact-header">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 md:space-x-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-2 md:p-3">
                        <i class="fas fa-heartbeat text-white text-lg md:text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-white mobile-compact-title">Monitoring Pemeriksaan Kesehatan</h1>
                        <p class="text-purple-100 text-xs md:text-sm mobile-compact-subtitle">Daftar Karyawan Belum Melakukan Pemeriksaan</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-white font-semibold text-sm md:text-lg mobile-compact-date">
                        <span class="hidden md:inline">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</span>
                        <span class="md:hidden">{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('D MMM') }}</span>
                    </div>
                    <div class="text-purple-100 text-xs md:text-sm mobile-compact-time" id="current-time"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-4 md:mb-8 mobile-stats-grid">
            <!-- Total Dijadwalkan -->
            <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-blue-500 fade-in mobile-stat-card">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-2 md:p-3 mobile-stat-icon">
                        <i class="fas fa-users text-blue-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-4">
                        <h3 class="text-xs md:text-sm font-medium text-gray-500 mobile-stat-label">Total Dijadwalkan</h3>
                        <p class="text-lg md:text-2xl font-bold text-gray-900 mobile-stat-value">{{ number_format($totalScheduled) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sudah Periksa -->
            <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-green-500 fade-in mobile-stat-card">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-2 md:p-3 mobile-stat-icon">
                        <i class="fas fa-check-circle text-green-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-4">
                        <h3 class="text-xs md:text-sm font-medium text-gray-500 mobile-stat-label">Sudah Periksa</h3>
                        <p class="text-lg md:text-2xl font-bold text-gray-900 mobile-stat-value">{{ number_format($totalExamined) }}</p>
                    </div>
                </div>
            </div>

            <!-- Belum Periksa -->
            <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-red-500 fade-in mobile-stat-card">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-full p-2 md:p-3 animate-pulse-slow mobile-stat-icon">
                        <i class="fas fa-exclamation-triangle text-red-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-4">
                        <h3 class="text-xs md:text-sm font-medium text-gray-500 mobile-stat-label">Belum Periksa</h3>
                        <p class="text-lg md:text-2xl font-bold text-gray-900 mobile-stat-value">{{ number_format($totalUnexamined) }}</p>
                    </div>
                </div>
            </div>

            <!-- Persentase -->
            <div class="bg-white rounded-xl shadow-md p-4 md:p-6 border-l-4 border-purple-500 fade-in mobile-stat-card">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-2 md:p-3 mobile-stat-icon">
                        <i class="fas fa-chart-pie text-purple-600 text-lg md:text-xl"></i>
                    </div>
                    <div class="ml-2 md:ml-4">
                        <h3 class="text-xs md:text-sm font-medium text-gray-500 mobile-stat-label">Persentase Selesai</h3>
                        <p class="text-lg md:text-2xl font-bold text-gray-900 mobile-stat-value">{{ $percentage }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-md p-4 md:p-6 mb-4 md:mb-8 fade-in mobile-progress-card">
            <div class="flex items-center justify-between mb-2 md:mb-3">
                <h3 class="text-sm md:text-lg font-semibold text-gray-900 mobile-progress-title">Progress Pemeriksaan Hari Ini</h3>
                <span class="text-xs md:text-sm text-gray-500">{{ $totalExamined }}/{{ $totalScheduled }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2 md:h-3 mobile-progress-bar">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2 md:h-3 rounded-full transition-all duration-1000 ease-out"
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>

        <!-- Table / Mobile Cards -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden fade-in">
            <div class="px-4 md:px-6 py-3 md:py-4 bg-gray-50 border-b border-gray-200 mobile-table-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mobile-table-title">
                        <i class="fas fa-list-ul text-purple-600 mr-2"></i>
                        <span class="hidden md:inline">Daftar Karyawan Belum Periksa</span>
                        <span class="md:hidden">Belum Periksa</span>
                    </h2>
                    <div class="flex items-center mobile-buttons">
                        <button onclick="refreshData()" class="bg-purple-600 hover:bg-purple-700 text-white px-3 md:px-4 py-2 rounded-lg transition-colors duration-200 mobile-button">
                            <i class="fas fa-sync-alt mr-1 md:mr-2"></i><span class="hidden md:inline">Refresh</span>
                        </button>
                    </div>
                </div>
            </div>

            @if($employees->count() > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Karyawan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Departemen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perusahaan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shift</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($employees as $index => $employee)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-semibold text-purple-600">{{ $employee->nrp }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $employee->nama_karyawan }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->jabatan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $employee->departemen }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $employee->perusahaan }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $employee->shift == 'shift 1' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                            {{ strtoupper($employee->shift) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View -->
                <div class="md:hidden">
                    @foreach($employees as $index => $employee)
                        <div class="bg-white border-b border-gray-100 hover:bg-gray-50 transition-colors duration-150 mobile-card-item">
                            <div class="mobile-card-header">
                                <div class="mobile-card-info">
                                    <div class="mobile-card-name text-gray-900">{{ $employee->nama_karyawan }}</div>
                                    <div class="mobile-card-nrp">{{ $employee->nrp }}</div>
                                </div>
                                <div class="mobile-card-badges">
                                    <span class="mobile-badge bg-blue-100 text-blue-800">
                                        {{ $employee->departemen }}
                                    </span>
                                    <span class="mobile-badge {{ $employee->shift == 'shift 1' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        {{ strtoupper($employee->shift) }}
                                    </span>
                                </div>
                            </div>

                            <div class="mobile-card-details">
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">Jabatan</div>
                                    <div class="mobile-detail-value">{{ \Str::limit($employee->jabatan, 20) }}</div>
                                </div>
                                <div class="mobile-detail-item">
                                    <div class="mobile-detail-label">Perusahaan</div>
                                    <div class="mobile-detail-value">{{ \Str::limit($employee->perusahaan, 20) }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Mobile Footer Info -->
                    <div class="p-4 bg-gray-50 text-center">
                        <p class="text-xs text-gray-500">
                            Menampilkan {{ $employees->count() }} dari {{ $totalUnexamined }} karyawan belum periksa
                        </p>
                    </div>
                </div>
            @else
                <div class="text-center py-8 md:py-12">
                    <div class="mx-auto h-16 md:h-24 w-16 md:w-24 text-gray-400">
                        <i class="fas fa-check-circle text-4xl md:text-6xl text-green-400"></i>
                    </div>
                    <h3 class="mt-3 md:mt-4 text-base md:text-lg font-medium text-gray-900">Semua Karyawan Sudah Melakukan Pemeriksaan!</h3>
                    <p class="mt-1 md:mt-2 text-sm text-gray-500">Tidak ada karyawan yang belum melakukan pemeriksaan hari ini.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-6 md:mt-12 bg-gradient-to-r from-purple-900 to-purple-800 text-white mobile-footer">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 md:py-6 mobile-footer-content">
                <!-- Code Credit -->
                <div class="text-center mb-3 md:mb-4">
                    <div class="flex items-center justify-center space-x-2 md:space-x-3 mb-2 md:mb-3">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-1.5 md:p-2">
                            <i class="fas fa-code text-white text-sm md:text-lg"></i>
                        </div>
                        <div>
                            <p class="text-purple-100 text-xs md:text-sm">Developed by</p>
                            <p class="text-white font-semibold text-sm md:text-lg">Ns. Hidayatullah</p>
                        </div>
                    </div>
                </div>

                <!-- Inspirational Quote -->
                <div class="border-t border-purple-700 pt-3 md:pt-4">
                    <div class="text-center">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-2 md:p-4 max-w-4xl mx-auto mobile-quote">
                            <div class="flex items-start space-x-2 md:space-x-3">
                                <div class="text-purple-300 text-lg md:text-2xl font-serif mobile-quote-mark">"</div>
                                <div class="flex-1">
                                    <p id="quote-text" class="text-white text-xs md:text-base italic leading-relaxed mb-1 md:mb-2 mobile-quote-text">
                                        Loading inspirational quote...
                                    </p>
                                    <p id="quote-author" class="text-purple-200 text-xs md:text-sm font-medium mobile-quote-author">
                                        — Loading...
                                    </p>
                                </div>
                                <div class="text-purple-300 text-lg md:text-2xl font-serif self-end mobile-quote-mark">"</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="border-t border-purple-700 pt-2 md:pt-4 mt-2 md:mt-4">
                    <div class="flex flex-col md:flex-row justify-between items-center text-xs md:text-sm text-purple-200">
                        <div class="mb-1 md:mb-0">
                            <p>Terakhir diperbarui: <span id="last-updated-footer"></span></p>
                        </div>
                        <div class="text-center md:text-right">
                            <p class="text-xs md:text-sm">© {{ date('Y') }} Sistem Monitoring Pemeriksaan Kesehatan</p>
                            <p class="text-xs text-purple-300 mt-0.5">Made with ❤️ for Healthcare Excellence</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Database quotes dari tokoh terkenal
        const inspirationalQuotes = [
            {
                text: "Kesehatan adalah mahkota di kepala orang sehat yang hanya dapat dilihat oleh orang yang sakit.",
                author: "Imam Syafi'i"
            },
            {
                text: "Kesehatan bukan segalanya, tapi tanpa kesehatan segalanya bukan apa-apa.",
                author: "Arthur Schopenhauer"
            },
            {
                text: "Mencegah lebih baik daripada mengobati.",
                author: "Benjamin Franklin"
            },
            {
                text: "Tubuh yang sehat adalah tempat tinggal jiwa yang bahagia, tubuh yang lemah adalah penjara jiwa.",
                author: "Francis Bacon"
            },
            {
                text: "Kesehatan adalah kekayaan sejati, bukan emas dan perak.",
                author: "Mahatma Gandhi"
            },
            {
                text: "Orang yang sehat memiliki seribu keinginan, orang yang sakit hanya memiliki satu.",
                author: "Pepatah India"
            },
            {
                text: "Investasi terbaik yang bisa kamu lakukan adalah dalam kesehatan dirimu sendiri.",
                author: "Warren Buffett"
            },
            {
                text: "Tubuh manusia adalah kuil dan untuk menjaganya tetap murni adalah tugas kita.",
                author: "B.K.S. Iyengar"
            },
            {
                text: "Kesehatan mental sama pentingnya dengan kesehatan fisik.",
                author: "Prince William"
            },
            {
                text: "Dokter masa depan tidak akan memberikan obat, tetapi akan mengajak pasiennya untuk peduli pada tubuh manusia, nutrisi, dan penyebab serta pencegahan penyakit.",
                author: "Thomas Edison"
            },
            {
                text: "Hidup sehat bukan tentang menjadi sempurna, tetapi tentang menjadi lebih baik dari kemarin.",
                author: "Unknown"
            },
            {
                text: "Kesehatan adalah kondisi dimana tubuh, pikiran, dan jiwa berada dalam harmoni yang sempurna.",
                author: "Sri Sri Ravi Shankar"
            },
            {
                text: "Jaga tubuhmu. Ini satu-satunya tempat yang kamu miliki untuk hidup.",
                author: "Jim Rohn"
            },
            {
                text: "Kesehatan membutuhkan makanan sehat, bukan obat-obatan.",
                author: "Hippocrates"
            },
            {
                text: "Energi dan ketekunan menaklukkan segalanya.",
                author: "Benjamin Franklin"
            },
            {
                text: "Perubahan yang kamu inginkan di dunia, mulailah dari dirimu sendiri.",
                author: "Mahatma Gandhi"
            },
            {
                text: "Kesuksesan bukanlah kunci kebahagiaan. Kebahagiaan adalah kunci kesuksesan.",
                author: "Albert Schweitzer"
            },
            {
                text: "Kebaikan adalah bahasa yang dapat didengar oleh orang tuli dan dilihat oleh orang buta.",
                author: "Mark Twain"
            },
            {
                text: "Pendidikan adalah senjata paling ampuh yang bisa kamu gunakan untuk mengubah dunia.",
                author: "Nelson Mandela"
            },
            {
                text: "Masa depan milik mereka yang percaya pada keindahan mimpi mereka.",
                author: "Eleanor Roosevelt"
            }
        ];

        // Fungsi untuk mendapatkan quote acak
        function getRandomQuote() {
            const randomIndex = Math.floor(Math.random() * inspirationalQuotes.length);
            return inspirationalQuotes[randomIndex];
        }

        // Fungsi untuk menampilkan quote
        function displayRandomQuote() {
            const quote = getRandomQuote();
            document.getElementById('quote-text').textContent = quote.text;
            document.getElementById('quote-author').textContent = `— ${quote.author}`;
        }

        // Update waktu real-time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID');
            const dateTimeString = now.toLocaleString('id-ID');

            document.getElementById('current-time').textContent = timeString;
            document.getElementById('last-updated-footer').textContent = dateTimeString;
        }

        // Refresh data
        function refreshData() {
            location.reload();
        }

        // Auto refresh setiap 5 menit
        setInterval(refreshData, 300000);

        // Update waktu setiap detik
        setInterval(updateTime, 1000);

        // Ganti quote setiap 30 detik
        setInterval(displayRandomQuote, 30000);

        // Initialize
        updateTime();
        displayRandomQuote();
    </script>
</body>
</html>
