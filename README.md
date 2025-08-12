# 🏥 PreWork - Healthcare Monitoring System

[![Laravel](https://img.shields.io/badge/Laravel-v10.x-red.svg)](https://laravel.com)
[![Filament](https://img.shields.io/badge/Filament-v3.x-orange.svg)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Sistem monitoring pemeriksaan kesehatan karyawan berbasis web yang dibangun dengan Laravel dan Filament. Dirancang untuk memantau dan melacak pemeriksaan tekanan darah harian karyawan dengan tampilan dashboard yang professional dan real-time monitoring.

## ✨ Features

### 🖥️ Admin Dashboard

-   **📊 Interactive Charts** - Grafik harian dan bulanan dengan filter periode
-   **👥 Employee Management** - Kelola data karyawan (NRP, nama, jabatan, departemen)
-   **📅 Roster Management** - Atur jadwal shift karyawan (shift 1, shift 2, off, cuti, dll)
-   **💓 Blood Pressure Tracking** - Catat hasil pemeriksaan tekanan darah harian
-   **📈 Period Reports** - Laporan periode dengan export Excel
-   **🎨 Custom Purple Theme** - Tema sidebar purple yang elegant

### 🌐 Public Monitoring

-   **📱 Real-time Dashboard** - Monitoring publik tanpa login
-   **📋 Employee List** - Daftar karyawan yang belum melakukan pemeriksaan
-   **📊 Live Statistics** - Statistik pemeriksaan hari ini (total, sudah periksa, belum periksa)
-   **🔄 Auto Refresh** - Update otomatis setiap 5 menit
-   **💬 Inspirational Quotes** - Quotes motivasi dari tokoh terkenal
-   **📱 Mobile Responsive** - Tampilan optimal di semua device

### 🎯 Key Highlights

-   **Filter by Shift** - Focus pada shift 1 dan shift 2 saja
-   **Status Tracking** - Monitor status pemeriksaan real-time
-   **Professional UI** - Interface modern dengan Tailwind CSS
-   **Export Reports** - Export laporan ke Excel
-   **Print Ready** - Siap untuk dicetak

## 🚀 Quick Start

### Prerequisites

-   PHP 8.1+
-   Composer
-   Node.js & NPM
-   MySQL 5.7+

### Installation

1. **Clone Repository**

    ```bash
    git clone https://github.com/nshidayatullah/PreWork.git
    cd PreWork
    ```

2. **Install Dependencies**

    ```bash
    # Install PHP dependencies
    composer install

    # Install Node.js dependencies
    npm install
    ```

3. **Environment Setup**

    ```bash
    # Copy environment file
    cp .env.example .env

    # Generate application key
    php artisan key:generate
    ```

4. **Database Configuration**

    ```bash
    # Edit .env file with your database credentials
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password
    ```

5. **Run Migrations**

    ```bash
    php artisan migrate
    ```

6. **Build Assets**

    ```bash
    # Development
    npm run dev

    # Production
    npm run build
    ```

7. **Start Development Server**
    ```bash
    php artisan serve
    ```

## 📱 Usage

### Admin Panel

-   **URL**: `http://localhost:8000/medic`
-   **Features**: Dashboard, Employee management, Roster scheduling, Blood pressure tracking

### Public Monitoring

-   **URL**: `http://localhost:8000/monitoring`
-   **Features**: Real-time employee examination status, statistics, auto-refresh

## 🗄️ Database Structure

### Main Tables

-   **employees** - Karyawan (NRP, Name, Position, Department, Company, Status)
-   **rosters** - Jadwal shift (employee_id, date, shift, month)
-   **blood_pressures** - Hasil pemeriksaan (employee_id, date, sistole, diastole, status)

### Key Relationships

-   Employee → hasMany → Rosters
-   Employee → hasMany → BloodPressures
-   Roster → belongsTo → Employee
-   BloodPressure → belongsTo → Employee

## 🎨 Screenshots

### Admin Dashboard

![Dashboard](https://via.placeholder.com/800x400/6b21a8/ffffff?text=Admin+Dashboard+with+Purple+Theme)

### Public Monitoring

![Public Monitoring](https://via.placeholder.com/800x400/667eea/ffffff?text=Public+Monitoring+Page)

### Period Reports

![Reports](https://via.placeholder.com/800x400/9333ea/ffffff?text=Period+Reports+with+Charts)

## 🔧 Configuration

### Custom Theme

The application uses a custom purple theme located in:

```
resources/css/filament/medic/theme.css
```

### Filament Configuration

```php
// app/Providers/Filament/MedicPanelProvider.php
$panel->viteTheme('resources/css/filament/medic/theme.css')
```

## 📊 API Endpoints

### Public API

```bash
GET /public/api/unexamined-employees
```

Returns JSON data of employees who haven't been examined today.

## 🚀 Deployment

### Production Build

```bash
# Build assets
npm run build

# Optimize for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Server Requirements

-   PHP 8.1+ with extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
-   MySQL 5.7+ or MariaDB 10.3+
-   Web server (Apache/Nginx)

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m '✨ Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Developer

**Ns. Hidayatullah**

-   GitHub: [@nshidayatullah](https://github.com/nshidayatullah)
-   Email: [your.email@example.com](mailto:your.email@example.com)

## 🙏 Acknowledgments

-   [Laravel](https://laravel.com) - The PHP Framework
-   [Filament](https://filamentphp.com) - Admin Panel Framework
-   [Tailwind CSS](https://tailwindcss.com) - CSS Framework
-   [Chart.js](https://chartjs.org) - Charts Library
-   [FontAwesome](https://fontawesome.com) - Icons

---

<div align="center">

**Made with ❤️ for Healthcare Excellence**

_"Kesehatan adalah mahkota di kepala orang sehat yang hanya dapat dilihat oleh orang yang sakit." - Imam Syafi'i_

</div>
# awalshift
