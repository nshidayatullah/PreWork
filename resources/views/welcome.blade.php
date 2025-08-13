<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik PPABIB - Sistem Manajemen Kesehatan Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'medical-blue': '#1e40af',
                        'medical-light': '#3b82f6',
                        'medical-dark': '#1e3a8a',
                        'medical-accent': '#60a5fa'
                    }
                }
            }
        }
    </script>
    <style>
        .medical-pattern {
            background-image:
                radial-gradient(circle at 2px 2px, rgba(59, 130, 246, 0.1) 1px, transparent 0);
            background-size: 40px 40px;
        }

        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .pulse-animation {
            animation: pulse-custom 2s infinite;
        }

        @keyframes pulse-custom {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .gradient-text {
            background: linear-gradient(45deg, #1e40af, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-100 medical-pattern">
    <!-- Header -->
    <header class="relative z-10">
        <nav class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-medical-blue rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.5 4h-3V2.5c0-.83-.67-1.5-1.5-1.5h-6c-.83 0-1.5.67-1.5 1.5V4h-3C3.67 4 3 4.67 3 5.5v13c0 .83.67 1.5 1.5 1.5h15c.83 0 1.5-.67 1.5-1.5v-13c0-.83-.67-1.5-1.5-1.5zM9.5 3h5v1h-5V3zm8 15.5h-11v-11h11v11z"/>
                            <path d="M13.5 10h-3c-.28 0-.5.22-.5.5s.22.5.5.5h3c.28 0 .5-.22.5-.5s-.22-.5-.5-.5z"/>
                            <path d="M13.5 12h-3c-.28 0-.5.22-.5.5s.22.5.5.5h3c.28 0 .5-.22.5-.5s-.22-.5-.5-.5z"/>
                            <path d="M13.5 14h-3c-.28 0-.5.22-.5.5s.22.5.5.5h3c.28 0 .5-.22.5-.5s-.22-.5-.5-.5z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-medical-dark">Klinik PPABIB</h1>
                        <p class="text-sm text-gray-600">Health Management System</p>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="#features" class="text-gray-700 hover:text-medical-blue transition-colors">Fitur</a>
                    <a href="#about" class="text-gray-700 hover:text-medical-blue transition-colors">Tentang</a>
                    <a href="#contact" class="text-gray-700 hover:text-medical-blue transition-colors">Kontak</a>
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Medical Icons -->
        <div class="absolute inset-0 overflow-hidden opacity-5">
            <div class="absolute top-20 left-20 floating-animation">
                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/99d8db7b-077f-4c2d-9634-1e367b958258.png" alt="Medical stethoscope icon floating in background" class="w-15 h-15">
            </div>
            <div class="absolute top-40 right-32 floating-animation" style="animation-delay: -2s;">
                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/c2716382-aa31-49df-9ec1-e56c0d96b485.png" alt="Heart rate monitor icon floating in background" class="w-12 h-12">
            </div>
            <div class="absolute bottom-40 left-40 floating-animation" style="animation-delay: -4s;">
                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/677586e9-a2b0-474e-960e-fd7da4b170dc.png" alt="Medical cross icon floating in background" class="w-18 h-18">
            </div>
            <div class="absolute bottom-20 right-20 floating-animation" style="animation-delay: -1s;">
                <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4da7cfe1-5d29-4adc-a345-b272dd66e5f9.png" alt="Medical chart icon floating in background" class="w-14 h-14">
            </div>
        </div>

        <div class="container mx-auto px-6 text-center relative z-10">
            <div class="max-w-4xl mx-auto">
                <!-- Main Logo/Icon -->
                <div class="mb-8 flex justify-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-medical-blue to-medical-light rounded-2xl flex items-center justify-center pulse-animation shadow-2xl">
                        <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl md:text-7xl font-bold mb-6 gradient-text leading-tight">
                    Sistem Manajemen<br>
                    <span class="text-medical-blue">Kesehatan Karyawan</span>
                </h1>

                <p class="text-xl md:text-2xl text-gray-600 mb-8 leading-relaxed max-w-3xl mx-auto">
                    Platform terintegrasi untuk monitoring kesehatan, jadwal kerja, dan pemeriksaan rutin karyawan
                    dengan teknologi modern dan interface yang user-friendly.
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="https://klinik-ppabib.site/medic"
                       class="group bg-medical-blue hover:bg-medical-dark text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 hover:shadow-xl flex items-center space-x-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        <span>Login ke Sistem</span>
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/>
                        </svg>
                    </a>

                    <button onclick="scrollToFeatures()" class="group border-2 border-medical-blue text-medical-blue hover:bg-medical-blue hover:text-white px-8 py-4 rounded-xl font-semibold text-lg transition-all duration-300 transform hover:scale-105 flex items-center space-x-3">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                        </svg>
                        <span>Lihat Fitur</span>
                    </button>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto">
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-blue-100">
                        <div class="text-3xl font-bold text-medical-blue mb-2">24/7</div>
                        <div class="text-gray-600">Monitoring Kesehatan</div>
                    </div>
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-blue-100">
                        <div class="text-3xl font-bold text-medical-blue mb-2">100%</div>
                        <div class="text-gray-600">Data Terintegrasi</div>
                    </div>
                    <div class="bg-white/70 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-blue-100">
                        <div class="text-3xl font-bold text-medical-blue mb-2">Real-time</div>
                        <div class="text-gray-600">Laporan Instant</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Fitur Unggulan</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Solusi komprehensif untuk manajemen kesehatan karyawan yang efisien dan modern
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/8a19a395-31e2-4737-aae0-9f8b4401d026.png" alt="Employee roster management system icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Manajemen Roster</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola jadwal kerja karyawan dengan mudah, termasuk shift, cuti, dan tugas luar
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/4ff93b2b-a6b8-4481-b431-7dcad41ef1c6.png" alt="Blood pressure monitoring system icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Monitoring Tekanan Darah</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Pantau dan catat tekanan darah karyawan secara berkala dengan sistem peringatan otomatis
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/bd467c71-5cbd-4de0-adc6-0ad259a55f19.png" alt="Health reports dashboard icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Laporan Kesehatan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Generate laporan kesehatan komprehensif dalam format PDF untuk evaluasi berkala
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/bad82d10-0f50-4c7f-a495-45f57c81a0c5.png" alt="Real-time dashboard analytics icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Dashboard Real-time</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Visualisasi data kesehatan dan statistik karyawan dalam dashboard yang informatif
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/bde21827-423d-41d9-994d-12b8b2672e61.png" alt="Employee database management icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Database Karyawan</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kelola data karyawan lengkap dengan riwayat kesehatan dan informasi kepegawaian
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-gradient-to-br from-blue-50 to-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow border border-blue-100">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mb-6">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/3dbc6cc7-2fec-45df-8844-35c43270277d.png" alt="Mobile responsive interface icon" class="w-8 h-8">
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Mobile Responsive</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Akses sistem dari perangkat apapun dengan interface yang responsif dan user-friendly
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-blue-50 to-blue-100">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-800 mb-6">Tentang Klinik PPABIB</h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        Klinik PPABIB adalah platform digital terdepan untuk manajemen kesehatan karyawan.
                        Kami menyediakan solusi terintegrasi yang menggabungkan teknologi modern dengan
                        pendekatan medis yang profesional.
                    </p>
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        Dengan fokus pada efisiensi, keakuratan, dan kemudahan penggunaan, sistem kami
                        membantu perusahaan dalam menjaga kesehatan karyawan secara optimal.
                    </p>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="text-center">
                            <div class="text-3xl font-bold text-medical-blue mb-2">500+</div>
                            <div class="text-gray-600">Karyawan Terdaftar</div>
                        </div>
                        <div class="text-center">
                            <div class="text-3xl font-bold text-medical-blue mb-2">99.9%</div>
                            <div class="text-gray-600">Uptime System</div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="bg-white rounded-2xl shadow-2xl p-8">
                        <img src="https://storage.googleapis.com/workspace-0f70711f-8b4e-4d94-86f1-2a93ccde5887/image/e091a75b-7029-458b-98cc-2dc562db9338.png" alt="Modern medical workspace showing healthcare professionals using digital tablets and monitoring systems" class="w-full h-80 object-cover rounded-xl">
                    </div>
                    <div class="absolute -top-6 -right-6 w-24 h-24 bg-medical-blue rounded-xl flex items-center justify-center pulse-animation">
                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Hubungi Kami</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Butuh bantuan atau ingin mengetahui lebih lanjut? Tim kami siap membantu Anda
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Alamat</h3>
                    <p class="text-gray-600">PT. Putra Perkasa Abadi - Site BIB</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Telepon Whats App</h3>
                    <p class="text-gray-600">+62 823-5160-9922</p>
                </div>

                <div class="text-center p-6">
                    <div class="w-16 h-16 bg-medical-blue rounded-xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">Email</h3>
                    <p class="text-gray-600">she-medic.ppa.bib@ppa.co.id</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-medical-dark text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-medical-light rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.5 4h-3V2.5c0-.83-.67-1.5-1.5-1.5h-6c-.83 0-1.5.67-1.5 1.5V4h-3C3.67 4 3 4.67 3 5.5v13c0 .83.67 1.5 1.5 1.5h15c.83 0 1.5-.67 1.5-1.5v-13c0-.83-.67-1.5-1.5-1.5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Klinik PPABIB</h3>
                            <p class="text-blue-200">Health Management System</p>
                        </div>
                    </div>
                    <p class="text-blue-200 mb-4 max-w-md">
                        Solusi digital terdepan untuk manajemen kesehatan karyawan dengan teknologi modern dan interface yang user-friendly.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Fitur</h4>
                    <ul class="space-y-2 text-blue-200">
                        <li>Manajemen Roster</li>
                        <li>Monitoring Kesehatan</li>
                        <li>Laporan PDF</li>
                        <li>Dashboard Analytics</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Bantuan</h4>
                    <ul class="space-y-2 text-blue-200">
                        <li><a href="#" class="hover:text-white transition-colors">Panduan User</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Support</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kontak</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-blue-800 mt-8 pt-8 text-center text-blue-200">
                <p>© 2025 Klinik PPABIB. All rights reserved. Powered by Ns. Hidayatullah.</p>
            </div>
        </div>
    </footer>

    <script>
        function scrollToFeatures() {
            document.getElementById('features').scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Smooth scrolling for all anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Add loading animation
        window.addEventListener('load', function() {
            document.body.classList.add('loaded');
        });
    </script>
</body>
</html>

