CRM Store Management System
<p align="center">
<img src="https://img.shields.io/badge/Laravel-v11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel Version">
<img src="https://img.shields.io/badge/Status-Active-success?style=for-the-badge" alt="Status">
<img src="https://img.shields.io/badge/License-MIT-blue?style=for-the-badge" alt="License">
</p>
Tentang Sistem
CRM Store Management System adalah aplikasi manajemen toko dan pelanggan yang komprehensif, dirancang untuk membantu bisnis mengelola operasional toko, visitor, dan tim lapangan dengan lebih efisien. Sistem ini menyediakan platform terpadu untuk monitoring performa toko dan aktivitas lapangan secara real-time.
Fitur Utama

Management Store - Kelola data toko, profil, dan informasi operasional secara terpusat
Visitor Management - Tracking dan analisis data pengunjung toko
Store Reporting - Dashboard dan laporan performa toko yang detail dan real-time
User Management - Manajemen pengguna sistem dengan kontrol akses penuh
Role Management - Sistem role-based access control (RBAC) yang fleksibel
Mobile App - Aplikasi mobile untuk field reporting dan monitoring lapangan

Teknologi
Aplikasi ini dibangun menggunakan:

Backend: Laravel Framework (PHP)
Frontend Web: Blade Templates / Livewire
Mobile: Flutter 
Database: MySQL 
Authentication: Laravel Sanctum
API: RESTful API

Modul Sistem
1. Store Management
Mengelola seluruh informasi toko termasuk:

Profil dan data toko
Lokasi dan area coverage
Jam operasional
Kategori dan jenis toko
Status operasional

2. Visitor Management
Tracking aktivitas visitor dengan fitur:

Data visitor dan history kunjungan
Analisis traffic toko
Customer segmentation
Visitor behavior tracking

3. Store Reporting
Dashboard dan laporan lengkap meliputi:

Performance metrics per toko
Sales dan revenue tracking
Comparative analysis
Export reports (PDF/Excel)
Real-time monitoring

4. User Management
Kontrol penuh atas pengguna sistem:

CRUD user accounts
Profile management
Activity logging
Multi-level authentication

5. Role Management
Sistem permission yang robust:

Custom role creation
Granular permissions
Role assignment
Access control lists (ACL)

6. Mobile Field Reporting
Aplikasi mobile untuk tim lapangan:

Submit laporan kunjungan toko
Upload foto dan dokumentasi
GPS tracking dan check-in
Offline mode support
Real-time sync dengan server

Instalasi
Requirements

PHP >= 8.2
Composer
MySQL/PostgreSQL
Node.js & NPM

Langkah Instalasi
bash# Clone repository
git clone https://github.com/kangcepu/crm_sales_management.git

# Masuk ke direktori project
cd crm-store-management

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate --seed

# Run aplikasi
php artisan serve
Default Credentials
Setelah instalasi, gunakan kredensial berikut:

Super Admin: admin@crsales@test / password

Mobile App Setup
Dokumentasi lengkap untuk setup aplikasi mobile dapat ditemukan di folder /mobile-app/README.md
API Documentation
API documentation tersedia di /api/documentation setelah aplikasi berjalan, atau akses melalui Postman Collection yang tersedia di /docs/postman.
Contributing
Kami menerima kontribusi dari komunitas! Silakan baca CONTRIBUTING.md untuk panduan kontribusi.
Security
Jika Anda menemukan celah keamanan dalam sistem, mohon laporkan ke security@example.com. Semua laporan keamanan akan ditangani dengan prioritas tinggi.
License
Sistem ini adalah open-source software yang dilisensikan di bawah MIT license.
Support
Untuk dukungan dan pertanyaan:

Email: khalid@mesproject.id
Documentation: khalid.mesproject.id
Community: mesproject.id


<p align="center">Dibuat dengan semangat untuk memudahkan manajemen toko dan tim lapangan</p>
