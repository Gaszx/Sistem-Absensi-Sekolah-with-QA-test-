# Sistem Informasi E-Absensi Berbasis Laravel dan Docker

## Deskripsi Proyek
Proyek ini adalah sistem informasi manajemen absensi sekolah yang dibangun menggunakan framework Laravel. Pengembangan proyek ini difokuskan pada implementasi Containerization menggunakan Docker dan otomatisasi pipeline CI/CD (Continuous Integration/Continuous Deployment) untuk memenuhi standar pengujian dan konfigurasi perangkat lunak modern.

## Informasi Repositori dan Image
*   **Source Code**: [https://git.aztech.id/Bagasxx/absensi-laravel-docker](https://git.aztech.id/Bagasxx/absensi-laravel-docker)
*   **Docker Hub**: [https://hub.docker.com/r/bagasxx/aplikasi-absensi](https://hub.docker.com/r/bagasxx/aplikasi-absensi)
*   **Build System**: Gitea Actions (Automated)

## Identitas Pengembang
*   **Nama**: Bagas Sujiwo
*   **NIM**: 2306018
*   **Mata Kuliah**: Pengujian & Konfigurasi Perangkat Lunak (PKPL)
*   **Institusi**: Institut Teknologi Garut (ITG).

## Arsitektur Teknologi
Sistem ini dikonfigurasi untuk berjalan di dalam lingkungan kontainer guna memastikan konsistensi antara tahap pengembangan dan produksi.

*   **Framework**: Laravel 10/11.
*   **Web Server**: Apache (Bundled with PHP Image).
*   **Bahasa Pemrograman**: PHP 8.2.
*   **Frontend Tooling**: Node.js 20 & Vite (untuk kompilasi aset CSS/JS).
*   **Database**: MySQL/PostgreSQL (tergantung konfigurasi .env).

## Fitur Utama
*   **Otomatisasi CI/CD**: Setiap perubahan kode pada branch utama akan memicu build otomatis di Gitea Actions.
*   **Dockerized Environment**: Memudahkan deployment di berbagai server tanpa perlu instalasi manual dependensi lokal.
*   **Manajemen Aset Modern**: Integrasi Vite untuk performa frontend yang lebih cepat.

## Struktur Konfigurasi DevOps
1.  **Dockerfile**: Berisi instruksi pembuatan image, mulai dari instalasi ekstensi PHP hingga kompilasi aset menggunakan NPM.
2.  **Gitea Actions (docker.yml)**: Mengatur alur kerja otomatisasi mulai dari pengambilan kode (checkout), login ke Docker Hub, hingga proses build dan push image.

## Panduan Instalasi Lokal
Jika ingin menjalankan proyek ini di mesin lokal menggunakan Docker, ikuti langkah berikut:

1.  Clone repositori ini:
    ```bash
    git clone [https://git.aztech.id/Bagasxx/absensi-laravel-docker.git](https://git.aztech.id/Bagasxx/absensi-laravel-docker.git)
    ```
2.  Salin file konfigurasi environment:
    ```bash
    cp .env.example .env
    ```
3.  Jalankan Docker Compose:
    ```bash
    docker-compose up -d
    ```
4.  Aplikasi dapat diakses melalui browser pada alamat `http://localhost`.

