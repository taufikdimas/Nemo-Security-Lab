### 1. 🟢 Yang Dibuat / Ditambah (Created / Added)

#### A. Database & Migrasi

• database/migrations/2026_10_06_000003_create_incident_assignees_table.php: Tabel pivot untuk mendukung multi-analis/multi-assignee pada tiket
insiden.
• database/migrations/2026_10_04_000001_add_client_id_to_users_table.php: Penambahan kolom relasi client_id pada tabel users.
• database/migrations/2026_10_04_000002_add_client_id_to_assets_table.php: Penambahan relasi kepemilikan aset dengan klien.

#### B. Fitur & Controller Baru

• BackupController.php: Controller endpoint GET /.backup (skenario lab: endpoint dump backup internal yang membocorkan kredensial svc.backup).
• dump.blade.php: View tampilan arsip backup plaintext untuk endpoint /.backup.

#### C. Dokumentasi & Panduan

• nemolabfix.md: Dokumentasi lengkap arsitektur, akun default, flow aplikasi (SOC, Pentest, Client Portal), dan daftar kerentanan lab (V1–V10).
• vps.md: Rincian spesifikasi & konfigurasi environment VPS Tencent Cloud (Ubuntu 24.04, Nginx, PHP 8.3, Docker, runtime).
• review.md: Dokumen audit arsitektur mendalam, review fitur, alur kerja per-role, dan temuan bug.
──────

### 2. 🟡 Yang Diperbarui / Diperbaiki (Updated / Fixed)

#### A. Controller & Logic Backend

• **IncidentController.php**:
• Dukungan multiple assignees (penugasan banyak analis dalam satu tiket insiden).
• Filter daftar analis hanya untuk staf internal (mengecualikan role client dari dropdown penugasan).
• Penambahan pencatatan jejak audit ActivityLog pada update status insiden.
• **ClientController.php**:
• Perbaikan bug string literal pada validasi email unik (unique:clients,email,...).
• Penambahan return redirect() pada method update (sebelumnya menyebabkan blank screen / 200 kosong).
• **ProjectController.php**:
• Perbaikan data visibility: Admin dapat melihat semua proyek; anggota tim (ProjectMember) sekarang dapat melihat dan mengakses detail
proyek yang ditugaskan ke mereka.
• **EmployeeController.php & VulnDbController.php**:
• Menghapus filter restriksi where('created_by') pada halaman index agar direktori karyawan dan basis data kerentanan (CVE) dapat dilihat
secara global oleh seluruh staf analis SOC.
• Penambahan upload avatar/foto profil dan pengelolaan tag keahlian (skills).
• **ProfileController.php** & **ClientPortalController.php**:
• Perbaikan penyimpanan avatar profil, regenerasi token API, dan isolasi data portal klien berbasis client_id (anti-IDOR).
• **LoginController.php**:
• Perbaikan alur redirect login: Akun dengan role client langsung diarahkan ke /portal/dashboard tanpa double redirect.
• **web.php**:
• Pendaftaran route /.backup di atas catch-all.
• Perapian grouping route internal (RestrictClientAccess) dan client portal.

#### B. Model Eloquent

• **Incident.php**: Penambahan relasi assignees() (belongsToMany) ke model User melalui pivot incident_assignees.
• **Employee.php**: Penambahan casting skills ke tipe array, relasi user, dan helper URL avatar.
• **User.php**: Penambahan relasi ke insiden yang ditugaskan (assignedIncidents).

#### C. Tampilan / Blade Views (UI/UX)

• Modul Insiden (resources/views/incidents/):
• Update create.blade.php & edit.blade.php dengan input multi-select / checkbox analis.
• Update show.blade.php untuk menampilkan daftar badge seluruh analis yang ditugaskan dan form catatan investigasi (Incident Notes).
• Update index.blade.php dengan filter status & badge keparahan yang lebih responsif.
• Modul Karyawan (resources/views/employees/):
• Update form create, edit, show, dan index untuk menampilkan foto profil, kontak, sertifikasi, dan badge skills.
• Modul Klien, Proyek, VulnDB & Aset (resources/views/{clients,projects,vulndb,assets}/):
• Penyesuaian antarmuka, konsistensi tema dark cyber, breadcrumb, dan perbaikan tombol aksi.
• Admin Panel & Berkas (resources/views/admin/ & resources/views/files/):
• Perapian tampilan file manager, upload modal, viewer dokumen, dan form manajemen user.
• Client Portal (resources/views/portal/):
• Peningkatan tampilan dashboard.blade.php dan halaman profil klien.

──────

### 3. 🔴 Yang Dihapus / Dibersihkan (Deleted / Cleaned Up)

• Folder Backup Redundant: Direktori resources/views.bak._/ (folder duplikat 40+ file views lama sebelum restrukturisasi).
• File Sisa Pengujian Exploit: Berkas sisa injeksi pengujian di storage/app/reports/ (1.php, 1.txt%00.php, dan .env yang tersasar).
• File Cadangan Sementara: Berkas .bak lama seperti database/database.sqlite.bak, welcome.blade.php.bak._, login.blade.php.bak.\*.
• Route Duplikat: Deklarasi ganda Route::get('/reports/download') yang sebelumnya tertulis dua kali di web.php.
