VPS details are now fully assembled, including cloud provider (Tencent Cloud), hostname, operating system (Ubuntu...
Berikut adalah rincian lengkap mengenai isi dan kondisi VPS ini:
──────

### 1. Spesifikasi & Sistem Operasi

• Penyedia / Platform: Tencent Cloud (CVM)
• Sistem Operasi: Ubuntu 24.04.5 LTS (Noble Numbat) x86_64 (Kernel 6.8.0)
• Zona Waktu: Asia/Jakarta (WIB)
• RAM: 2 GB (terpakai ~680 MB, swap 2 GB)
• Penyimpanan (Disk): 40 GB (terpakai ~7.2 GB / 20%, sisa ~31 GB)
──────

### 2. Web Server & Lingkungan Web (LEMP Ready)

• Nginx: Berjalan aktif di port 80 (HTTP) dan port 443 (HTTPS siap).
• PHP: PHP 8.3 (PHP-FPM aktif) beserta ekstensi umum (sqlite3, mbstring, xml, bcmath, curl, zip, gd).
• Composer: v2.10.3 sudah terpasang.
• Direktori Web: /var/www/html (saat ini masih berisi halaman bawaan Nginx, belum ada aplikasi/website yang di-
deploy).
──────

### 3. Bahasa Pemrograman & Runtime

• Node.js: v20.20.2 & npm v10.8.2
• Python: Python 3.12.3
• Database Engine: sqlite3 (belum ada MySQL/PostgreSQL terpasang)
• Git: v2.43.0
──────

### 4. Container & AI / Developer Tools

• Docker: Docker Engine & Docker Compose v2 aktif (dengan konfigurasi userns-remap). Saat ini belum ada container
yang berjalan.
• Antigravity CLI (agy): Terpasang di ~/.local/bin/agy.
• OpenCode (opencode): Terpasang di ~/.opencode/bin/opencode.
──────
