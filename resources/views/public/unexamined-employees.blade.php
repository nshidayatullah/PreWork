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
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                        <i class="fas fa-heartbeat text-white text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white">Monitoring Pemeriksaan Kesehatan</h1>
                        <p class="text-purple-100">Daftar Karyawan Belum Melakukan Pemeriksaan</p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-white font-semibold text-lg">
                        {{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                    <div class="text-purple-100 text-sm" id="current-time"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Stats Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Total Dijadwalkan -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 fade-in">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3">
                        <i class="fas fa-users text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Total Dijadwalkan</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalScheduled) }}</p>
                    </div>
                </div>
            </div>

            <!-- Sudah Periksa -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 fade-in">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Sudah Periksa</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalExamined) }}</p>
                    </div>
                </div>
            </div>

            <!-- Belum Periksa -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 fade-in">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-full p-3 animate-pulse-slow">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Belum Periksa</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($totalUnexamined) }}</p>
                    </div>
                </div>
            </div>

            <!-- Persentase -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 fade-in">
                <div class="flex items-center">
                    <div class="bg-purple-100 rounded-full p-3">
                        <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500">Persentase Selesai</h3>
                        <p class="text-2xl font-bold text-gray-900">{{ $percentage }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white rounded-xl shadow-md p-6 mb-8 fade-in">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-lg font-semibold text-gray-900">Progress Pemeriksaan Hari Ini</h3>
                <span class="text-sm text-gray-500">{{ $totalExamined }}/{{ $totalScheduled }}</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-3 rounded-full transition-all duration-1000 ease-out" 
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden fade-in">
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">
                        <i class="fas fa-list-ul text-purple-600 mr-2"></i>
                        Daftar Karyawan Belum Periksa
                    </h2>
                    <div class="flex items-center space-x-4">
                        <button onclick="refreshData()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                        <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors duration-200">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>

            @if($employees->count() > 0)
                <div class="overflow-x-auto">
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
            @else
                <div class="text-center py-12">
                    <div class="mx-auto h-24 w-24 text-gray-400">
                        <i class="fas fa-check-circle text-6xl text-green-400"></i>
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Semua Karyawan Sudah Melakukan Pemeriksaan!</h3>
                    <p class="mt-2 text-gray-500">Tidak ada karyawan yang belum melakukan pemeriksaan hari ini.</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="mt-12 bg-gradient-to-r from-purple-900 to-purple-800 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Code Credit -->
                <div class="text-center mb-6">
                    <div class="flex items-center justify-center space-x-3 mb-4">
                        <div class="bg-white/20 backdrop-blur-sm rounded-full p-2">
                            <i class="fas fa-code text-white text-lg"></i>
                        </div>
                    </div>
                </div>

                <!-- Inspirational Quote -->
                <div class="border-t border-purple-700 pt-6">
                    <div class="text-center">
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-6 max-w-4xl mx-auto">
                            <div class="flex items-start space-x-4">
                                <div class="text-purple-300 text-3xl font-serif">"</div>
                                <div class="flex-1">
                                    <p id="quote-text" class="text-white text-lg italic leading-relaxed mb-3">
                                        Loading inspirational quote...
                                    </p>
                                    <p id="quote-author" class="text-purple-200 text-sm font-medium">
                                        — Loading...
                                    </p>
                                </div>
                                <div class="text-purple-300 text-3xl font-serif self-end">"</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="border-t border-purple-700 pt-6 mt-6">
                    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-purple-200">
                        <div class="mb-4 md:mb-0">
                            <p> <span id="last-updated-footer"></span></p>
                        </div>
                        <div class="text-center md:text-right">
                            <p>© {{ date('Y') }} Sistem Monitoring Pemeriksaan Kesehatan</p>
                            <p class="text-xs text-purple-300 mt-1">Made with ❤️ for Healthcare Excellence</p>
                            <p class="text-xs text-purple-300 mt-1">code by Ns. Hidayatullah</p>
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
            document.getElementById('last-updated').textContent = timeString;
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