# Nemo Security Lab - PT Garuda Siber Nusantara (nemolabfix)

## Deskripsi Aplikasi
Nemo Security Lab adalah platform SecureOps hasil redesign menjadi **Platform Jasa Penetration Testing** (PT Garuda Siber Nusantara). Aplikasi ini menggabungkan dua lini layanan:
1. **SOC / Managed Security** - Monitoring insiden, manajemen aset, diagnostik jaringan
2. **Pentest & VAPT** - Engagement pentest, temuan kerentanan, laporan pentest

Platform ini memiliki portal terpisah untuk klien untuk melihat proyek, insiden, hasil pentest, dan laporan.

## Teknologi
- Laravel (PHP Framework)
- SQLite Database (`database/database.sqlite`)
- Bootstrap 5 + Bootstrap Icons
- Blade Templates

## Cara Menjalankan
```bash
cd Nemo-Security-Lab
composer install
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan serve
```
Akses: http://127.0.0.1:8000

## Role Pengguna
Terdapat 3 role utama:

| Role | Deskripsi | Akses |
|---|---|---|
| `admin` | Administrator sistem | Full access ke semua fitur internal + admin panel |
| `user` | Karyawan/Internal (SOC + Pentest Team) | Akses fitur internal (dashboard, insiden, aset, vulndb, pentest, proyek, klien, tim pentest, perangkat, laporan SOC, berkas, impor) |
| `client` | Klien korporat | Hanya akses Client Portal (`/portal/*`) |

> Catatan: Tidak ada role terpisah untuk "Tim Pentest". Semua karyawan role `user` bisa mengerjakan SOC dan Pentest sekaligus.

## Akun Default (Terverifikasi)
| Email | Password | Role |
|---|---|---|
| `admin@garuda-siber.internal` | `SecureOps#2024` | admin |
| `budi.santoso@bank-nusantara.co.id` | `ClientPortal#2024` | client |
| `citra.dewi@logistik-prima.co.id` | `ClientPortal#2024` | client |
| `svc.backup@garuda-siber.internal` | `G@rud4B4ckup#2024` | user |

## Fitur Aplikasi

### Internal (Admin & User)
- **Dashboard** - Ringkasan SOC & Pentest Overview
- **Insiden** - Manajemen insiden keamanan
- **Aset** - Inventori aset jaringan
- **Temuan & Kerentanan (VulnDB)** - Database temuan kerentanan
- **Pentest - Engagement** - Manajemen engagement pentest (scoping, in_progress, reporting, review, closed)
- **Pentest - Laporan Pentest** - Pembuatan & publikasi laporan pentest
- **Proyek** - Manajemen proyek assessment
- **Klien** - Manajemen data klien
- **Tim Pentest (Karyawan)** - Daftar karyawan yang terlibat
- **Diagnostik Jaringan** - Tools diagnostik jaringan
- **Riwayat Diagnostik** - Riwayat eksekusi diagnostik
- **Laporan (SOC)** - Generate & manajemen laporan SOC (.txt)
- **Berkas** - Manajemen file
- **Impor Data** - Import data
- **Admin Panel** (khusus admin) - Manajemen pengguna, berkas, konfigurasi, monitor ancaman, log aktivitas

### Client Portal (`/portal/*`)
- **Dashboard** - Ringkasan proyek, insiden, pentest
- **Proyek Saya** - Daftar proyek klien
- **Insiden** - Daftar insiden terkait klien
- **Hasil Pentest** - Daftar engagement pentest klien
- **Temuan Keamanan** - Daftar temuan pentest klien (tanpa PoC)
- **Laporan SOC** - Laporan SOC (.txt) untuk klien
- **Laporan Pentest** - Laporan pentest terpublikasi (client_visible=true, tanpa PoC)
- **Profil** - Pengaturan profil klien

## Modul Pentest (Detail)

### Tabel Database
- `pentest_engagements` - Data engagement pentest (code, title, type, status, client, lead_tester, scope, dll)
- `pentest_findings` - Temuan pentest per engagement (severity, status, category, description, impact, poc, recommendation, cvss, cve)
- `pentest_reports` - Laporan pentest (report_code, executive_summary, scope_and_methodology, conclusion, client_visible, published_at)

### Engagement Types
`web_application`, `infrastructure`, `mobile_application`, `red_team`, `social_engineering`, `cloud_review`, `code_review`

### Status Engagement
`scoping`, `in_progress`, `reporting`, `review`, `closed`, `on_hold`

### Severity Findings
`critical`, `high`, `medium`, `low`, `informational`

### Status Findings
`open`, `in_remediation`, `remediated`, `accepted_risk`, `false_positive`

## Kerentanan Lab (V1-V10) - TIDAK BOLEH DIUBAH
Aplikasi ini sengaja mengandung kerentanan untuk keperluan lab pembelajaran:

| ID | Lokasi | Deskripsi |
|---|---|---|
| V1 | `config/services.php` | Hardcoded credentials (backup service) |
| V2 | `app/Http/Controllers/Auth/LoginController.php` | Debug master key logic |
| V3 | `app/Http/Controllers/NetworkDiagnosticController.php` | LFI via `file_get_contents` |
| V4 | `app/Http/Controllers/AssetController.php` | SQL Injection via `whereRaw` dengan user input |
| V5 | `app/Models/User.php` | Weak token generation (MD5) |
| V6 | `app/Http/Controllers/ReportController.php` | Path Traversal |
| V7 | `app/Http/Middleware/HandleUserPreferences.php` | Unserialize RCE via cookie |
| V10 | `app/Http/Controllers/Admin/AdminFileController.php` | SSRF |

> V8/V9 juga ada sesuai environment lab. Semua kerentanan ini **dipertahankan utuh** dan telah diverifikasi aman dari sisi fungsional aplikasi.

## Honeypot
Sistem dilengkapi dengan Honeypot Trap (`app/Http/Middleware/HoneypotTrap`, `app/Support/Honeypot/*`) untuk mendeteksi akses path yang tidak terdaftar. Terdapat 31 lure yang terkonfigurasi.

## Flow Aplikasi

### Flow Internal (Admin/User)
1. Login → redirect berdasarkan role (admin→`/admin/dashboard`, user→`/dashboard`)
2. Manajemen SOC: Insiden → Aset → Temuan & Kerentanan
3. Manajemen Pentest: Engagement → Tambah Temuan → Buat Laporan Pentest → Publish (opsional client_visible)
4. Manajemen Umum: Proyek, Klien, Tim Pentest
5. Tools: Diagnostik Jaringan, Laporan (SOC), Berkas

### Flow Client Portal
1. Login sebagai client → redirect ke `/portal/dashboard`
2. Melihat Proyek Saya, Insiden terkait
3. Melihat Hasil Pentest (engagement milik klien)
4. Melihat Temuan Keamanan (tanpa PoC untuk keamanan)
5. Melihat Laporan SOC & Laporan Pentest (hanya yang relevan & client_visible)
6. Akses dibatasi hanya untuk data milik klien (validasi client_id/client_name)

### Flow Pentest
1. Buat Engagement (internal) → assign lead tester, tentukan scope & metodologi
2. Tambah Findings per engagement → isi detail temuan (severity, impact, poc, recommendation)
3. Buat Pentest Report dari engagement → isi executive summary, scope/methodology, conclusion
4. Publish report → bisa set client_visible=true agar muncul di portal klien
5. Klien melihat hasil pentest & laporan pentest (tanpa kolom PoC)

## Keamanan & Validasi
- Role-based access control (CheckRole middleware)
- Client data isolation (validasi kepemilikan engagement/finding/report)
- Portal terpisah dengan middleware `auth`, `prefs`, `role:client`
- Internal routes dilindungi middleware `internal`
- Honeypot trap untuk deteksi path mencurigakan
- PoC tidak ditampilkan di Client Portal

## File Penting
- `routes/web.php` - Routing aplikasi
- `app/Http/Controllers/Auth/LoginController.php` - Login & redirect
- `app/Http/Controllers/ClientPortal/ClientPortalController.php` - Client Portal
- `app/Http/Controllers/PentestEngagementController.php` - Modul Pentest
- `resources/views/layouts/app.blade.php` - Sidebar internal
- `resources/views/layouts/portal.blade.php` - Sidebar portal
- `database/database.sqlite` - Database

## Catatan Penting
- Semua kerentanan V1-V10 **TIDAK BOLEH disentuh**
- Jangan jalankan `migrate:fresh` tanpa konfirmasi
- Role system tidak diubah (tidak ada role baru)
- Portal client menggunakan controller asli `ClientPortal/ClientPortalController.php` + views `resources/views/portal/*`

## Catatan Ukuran ZIP
Tersedia 2 versi:
- `nemolabfix.zip` / `nemolabfix-clean.zip` (8.7MB) - Tanpa `vendor/` dan `node_modules/`. Cocok jika teman ingin `composer install` sendiri. **Wajib** jalankan `composer install` sebelum `php artisan serve`.
- `nemolabfix-full.zip` (78MB) - Sudah termasuk `vendor/` dan `node_modules/`. Bisa langsung dijalankan tanpa `composer install`/`npm install` (tergantung environment).

Untuk lab pembelajaran, versi clean (8.7MB) sudah cukup dan lebih ringan untuk dikirim.
