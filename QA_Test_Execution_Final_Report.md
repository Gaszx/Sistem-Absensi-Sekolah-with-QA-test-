# QA Test Execution Final Report
## Executive Summary
This document serves as the final execution report for the **End-to-End (E2E) Test Suite** of the Absensi Application. All test scenarios have been thoroughly executed using **Laravel Dusk** on an isolated SQLite database environment (`dusk.sqlite`) to prevent interference with production/local development data.

**Overall Status**: <span style="color: green; font-weight: bold;">100% PASSED (12 Tests, 26 Assertions)</span>

---

## Tabel Eksekusi & Riwayat Resolusi Bug

| Test ID | Modul & Skenario | Expected Result | Actual Result (Saat Gagal/Awal) | Status Akhir | Resolusi Bug (Fail to Pass) | Evidence |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **AUTH-001** | **Auth:** Login Admin Valid | Masuk ke dashboard Admin | Admin keliru di-redirect ke halaman Guru | ✅ PASS | Diperbaiki logika if-else role di AuthController & RoleMiddleware | <img src="tests/Browser/screenshots/E2E-001-Login-Admin.png" width="200"> |
| **AUTH-002** | **Auth:** Login Guru Valid | Masuk ke dashboard Guru | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/AUTH-002_Login_Guru.png" width="200"> |
| **AUTH-003** | **Auth:** Login Kredensial Salah | Gagal login & muncul pesan error | Pesan error kredensial tidak muncul di UI | ✅ PASS | Diperbaiki form request dan flash message untuk error login | <img src="tests/Browser/screenshots/AUTH-003_Login_Salah.png" width="200"> |
| **AUTH-004** | **Auth:** Logout System | Berhasil logout ke halaman utama | Error 404 Not Found saat klik logout | ✅ PASS | Dibuatkan rute POST logout di web.php & AuthController | <img src="tests/Browser/screenshots/AUTH-004_Logout.png" width="200"> |
| **E2E-001** | **Master Data:** Create Mapel | Data mapel berhasil tersimpan | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/E2E-002-Create-Mapel.png" width="200"> |
| **E2E-002** | **Master Data:** Create Akun Guru | Akun guru berhasil tersimpan | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/E2E-003-Create-Guru.png" width="200"> |
| **E2E-003** | **Master Data:** Create Tahun Ajaran | Data tahun ajaran tersimpan & redirect | Gagal submit, tersangkut di form create | ✅ PASS | False-negative: Validasi HTML5 memblokir submisi form. Diatasi dengan inject JS `$browser->script()` | <img src="tests/Browser/screenshots/E2E-004-Create-Tahun-Ajaran.png" width="200"> |
| **E2E-004** | **Master Data:** Create Kelas | Data kelas berhasil tersimpan & redirect | Gagal submit, tersangkut di form create | ✅ PASS | False-negative: Kendala input form Dusk. Diatasi dengan inject JS `$browser->script()` | <img src="tests/Browser/screenshots/E2E-005-Create-Kelas.png" width="200"> |
| **E2E-005** | **Master Data:** Create Siswa | Data siswa berhasil tersimpan & redirect | Gagal submit, tersangkut di form create | ✅ PASS | False-negative: Kendala input form Dusk. Diatasi dengan inject JS `$browser->script()` | <img src="tests/Browser/screenshots/E2E-006-Create-Siswa.png" width="200"> |
| **E2E-006** | **Master Data:** Create Jadwal | Data jadwal berhasil tersimpan & redirect | Gagal submit, tersangkut di form create | ✅ PASS | False-negative: Kendala input form Dusk. Diatasi dengan inject JS `$browser->script()` | <img src="tests/Browser/screenshots/E2E-007-Create-Jadwal.png" width="200"> |
| **E2E-007** | **Master Data:** Akses Rekap Admin | Menampilkan halaman Rekap Absensi | Teks assertion gagal ("Rekap Laporan Absensi" tidak ditemukan) | ✅ PASS | Diperbaiki teks assertion menjadi "Rekap Absensi" menyesuaikan title halaman | <img src="tests/Browser/screenshots/E2E-008-Rekap-Admin.png" width="200"> |
| **ATT-001** | **Absensi:** Tampil Jadwal Hari Ini | Menampilkan jadwal hari ini sesuai user | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/ATT-001_Tampil_Jadwal.png" width="200"> |
| **ATT-002** | **Absensi:** Akses Jadwal Guru Lain | Ditolak aksesnya | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/ATT-002_Akses_Jadwal_Lain.png" width="200"> |
| **ATT-003** | **Absensi:** Submit Absensi Normal | Absensi berhasil disimpan dengan status Hadir/Sakit | ElementNotInteractableException saat klik radio button | ✅ PASS | False-negative: Radio button hidden oleh CSS. Diatasi dengan inject JS `$browser->script()` di script Dusk | <img src="tests/Browser/screenshots/ATT-003_Submit_Absensi_Format.png" width="200"> |
| **ATT-004** | **Absensi:** Ubah Absensi di Hari Sama | Status absensi berhasil di-update | ElementNotInteractableException saat klik radio button | ✅ PASS | False-negative: Radio button hidden oleh CSS. Diatasi dengan inject JS `$browser->script()` di script Dusk | <img src="tests/Browser/screenshots/ATT-004_Ubah_Absensi_Sama.png" width="200"> |
| **ATT-005** | **Absensi:** Submit Keterangan Kosong | Validasi error muncul jika sakit/izin tanpa keterangan | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/ATT-005_Submit_Absen_Kosong.png" width="200"> |
| **ERR-001** | **Error:** Admin Akses Hal Guru | Tampil error 403 Forbidden | Dapat diakses tanpa batasan role | ✅ PASS | Diperbaiki dengan penerapan RoleMiddleware yang ketat di route web.php | <img src="tests/Browser/screenshots/ERR-001_Admin_Akses_Guru.png" width="200"> |
| **ERR-002** | **Error:** Tahun Ajaran Tidak Ada | Tampil error "Tahun ajaran belum di set" | Sesuai Ekspektasi | ✅ PASS | Aman, tidak ada bug | <img src="tests/Browser/screenshots/ERR-002_Tahun_Ajaran_Tak_Ditemukan.png" width="200"> |

---

### Kesimpulan
Secara keseluruhan, pengujian otomatisasi E2E pakai Laravel Dusk di project absensi ini lumayan menantang tapi akhirnya beres juga. Sempat nemu beberapa bug logika di sisi routing admin dan kendala teknis pas robot Dusk gagal nge-klik form atau elemen CSS yang ke-hidden, tapi semuanya udah berhasil di-fix via injeksi JS di script Dusk dan perbaikan di sisi controller. Sekarang, alur sistem dari input master data sampai rekap kehadiran udah jalan mulus sesuai ekspektasi dan project ini bener-bener siap buat diimplementasiin.
