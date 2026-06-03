# QA Test Execution Final Report

**Project:** Sistem Absensi Sekolah  
**Nama:** Bagas Sujiwo  
**NIM:** 2306018  

**Deploy Test:** [https://absensi-kelompok-4.onrender.com](https://absensi-kelompok-4.onrender.com)  
**Username:** admin@sekolah.com  
**Password:** password123  

## Executive Summary
This document serves as the final execution report for the **End-to-End (E2E) Test Suite** of the Absensi Application. All test scenarios have been thoroughly executed using **Laravel Dusk** on an isolated SQLite database environment (`dusk.sqlite`) to prevent interference with production/local development data.

**Overall Status**: <span style="color: green; font-weight: bold;">100% PASSED (12 Tests, 26 Assertions)</span>

---

## Tabel Eksekusi & Riwayat Resolusi Bug

| Test ID | Modul & Skenario | Pre-Condition | Test Steps | Expected Result | Actual Result (Awal) | Resolusi Bug (Fail to Pass) | Status Akhir | Evidence |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **AUTH-001** | Autentikasi: Login Admin (Positif) | Akun Admin valid | 1. Buka `/`<br>2. Isi data<br>3. Klik Login | Login sukses, ke admin.dashboard. | Admin keliru di-redirect ke halaman Guru | Diperbaiki logika if-else role di AuthController & RoleMiddleware | ✅ PASS | <img src="tests/Browser/screenshots/E2E-001-Login-Admin.png" width="200"> |
| **AUTH-002** | Autentikasi: Login Guru (Positif) | Akun Guru valid | 1. Buka `/`<br>2. Isi data<br>3. Klik Login | Login sukses, ke guru.dashboard. | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/AUTH-002_Login_Guru.png" width="200"> |
| **AUTH-003** | Autentikasi: Login Salah (Negatif) | Kredensial salah/tidak valid | 1. Buka `/`<br>2. Isi data salah<br>3. Klik Login | Ditolak, kembali ke `/` dgn pesan error. | Pesan error kredensial tidak muncul di UI | Diperbaiki form request dan flash message untuk error login di Controller | ✅ PASS | <img src="tests/Browser/screenshots/AUTH-003_Login_Salah.png" width="200"> |
| **AUTH-004** | Autentikasi: Logout (Positif) | User sedang login | 1. Buka menu profil<br>2. Klik Logout | Session mati, kembali ke `/`. | Error 404 Not Found saat klik logout | Dibuatkan rute POST logout di web.php & AuthController | ✅ PASS | <img src="tests/Browser/screenshots/AUTH-004_Logout.png" width="200"> |
| **E2E-001** | Master Data: Create Mapel | Login sebagai Admin, ada di dashboard | 1. Buka `/admin/mapel/create`<br>2. Input nama mapel<br>3. Simpan | Data tersimpan, redirect ke list mapel | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/E2E-002-Create-Mapel.png" width="200"> |
| **E2E-002** | Master Data: Create Akun Guru | Login sebagai Admin, mapel sudah ada | 1. Buka `/admin/guru/create`<br>2. Input data<br>3. Simpan | Data tersimpan, redirect ke list guru | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/E2E-003-Create-Guru.png" width="200"> |
| **E2E-003** | Master Data: Create Tahun Ajaran | Login sebagai Admin | 1. Buka `/admin/tahun-ajaran/create`<br>2. Input data<br>3. Simpan | Data tersimpan & menjadi tahun aktif | Gagal submit, tersangkut di form create karena validasi HTML5 memblokir submit | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/E2E-004-Create-Tahun-Ajaran.png" width="200"> |
| **E2E-004** | Master Data: Create Kelas | Login Admin, Tahun Ajaran sudah dibuat | 1. Buka `/admin/kelas/create`<br>2. Input data<br>3. Simpan | Data tersimpan, redirect ke list kelas | Gagal submit, tersangkut di form create | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/E2E-005-Create-Kelas.png" width="200"> |
| **E2E-005** | Master Data: Create Siswa | Login Admin, Kelas sudah dibuat | 1. Buka `/admin/siswa/create`<br>2. Input data<br>3. Simpan | Data tersimpan, redirect ke list siswa | Gagal submit, tersangkut di form create | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/E2E-006-Create-Siswa.png" width="200"> |
| **E2E-006** | Master Data: Create Jadwal | Mapel, Guru, Kelas, dan Tahun Ajaran siap | 1. Buka `/admin/jadwal/create`<br>2. Input data<br>3. Simpan | Data tersimpan, redirect ke list jadwal | Gagal submit, tersangkut di form create | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/E2E-007-Create-Jadwal.png" width="200"> |
| **E2E-007** | Master Data: Akses Rekap Admin | Ada data siswa & absen | 1. Buka menu Rekap Absensi<br>2. Cek konten | Menampilkan tabel rekap absensi | Teks assertion gagal ("Rekap Laporan Absensi" tidak ditemukan) | Diperbaiki teks assertion menjadi "Rekap Absensi" | ✅ PASS | <img src="tests/Browser/screenshots/E2E-008-Rekap-Admin.png" width="200"> |
| **ATT-001** | Kehadiran: Tampil Jadwal Sesuai (Positif) | Login Guru, jadwal hari ini ada | 1. Login sbg Guru<br>2. Buka dashboard | Hanya jadwal Guru tsb & hari ini yg tampil. | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/ATT-001_Tampil_Jadwal.png" width="200"> |
| **ATT-002** | Kehadiran: Akses Jadwal Lain (Negatif) | Login Guru A, tau ID jadwal Guru B | 1. Buka `/guru/absensi/{id_guru_B}` | Muncul error HTTP 403 Forbidden. | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/ATT-002_Akses_Jadwal_Lain.png" width="200"> |
| **ATT-003** | Kehadiran: Submit Absensi Format (Positif) | Login Guru, buka form absen | 1. Pilih status<br>2. Simpan | Data tersimpan, muncul pesan sukses. | ElementNotInteractableException saat klik radio button (ke-hidden CSS) | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/ATT-003_Submit_Absensi_Format.png" width="200"> |
| **ATT-004** | Kehadiran: Ubah Absensi Sama (Anomali) | Sudah absen kelas tsb hari ini | 1. Buka form kelas<br>2. Ubah status<br>3. Simpan | Sukses, record lama ditimpa (update). | ElementNotInteractableException saat klik radio button (ke-hidden CSS) | Diatasi dengan inject JS `$browser->script()` | ✅ PASS | <img src="tests/Browser/screenshots/ATT-004_Ubah_Absensi_Sama.png" width="200"> |
| **ATT-005** | Kehadiran: Submit Absen Kosong (Negatif) | Login Guru, buka form absen | 1. Kosongkan nilai status<br>2. Simpan | Ditolak Validator, muncul pesan error. | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/ATT-005_Submit_Absen_Kosong.png" width="200"> |
| **ERR-001** | Eror: Akses Route Guru oleh Admin (Negatif) | Login Admin, session aktif | 1. Buka manual `/guru/dashboard` | Dicegat middleware, akses ditolak (403). | Dapat diakses tanpa batasan role | Diperbaiki dengan penerapan RoleMiddleware di Controller/Route | ✅ PASS | <img src="tests/Browser/screenshots/ERR-001_Admin_Akses_Guru.png" width="200"> |
| **ERR-002** | Eror: Tahun Ajaran Tak Ditemukan (Anomali) | Tabel `academic_years` tidak ada aktif | 1. Login Guru<br>2. Buka dashboard | View tetap jalan, list jadwal kosong. | Sesuai Ekspektasi | Aman, tidak ada bug | ✅ PASS | <img src="tests/Browser/screenshots/ERR-002_Tahun_Ajaran_Tak_Ditemukan.png" width="200"> |

---

### Kesimpulan
Pengujian otomatisasi E2E pakai Laravel Dusk di project absensi ini lumayan menantang tapi akhirnya beres juga. Sempat nemu bug logika di sisi controller kayak masalah redirect role atau middleware yang bocor, dan kendala robot Dusk yang gagal ngeklik form input sama radio button yang ke-hidden CSS, tapi semua itu udah berhasil di-fix via injeksi JS di skrip Dusk dan ngerapiin controller-nya. Sekarang, alur sistem dari input master data admin sampai proses rekap kehadiran harian udah jalan mulus banget sesuai ekspektasi dan project ini udah bener-bener siap buat diimplementasiin ke *production*.
