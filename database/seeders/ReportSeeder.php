<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReportSeeder extends Seeder
{
    public function run(): void
    {
        $dir = storage_path('app/reports');

        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $reports = [
            1 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0007\nJudul      : Backup harian gagal selama 3 hari\nPrioritas  : Critical\nStatus     : Resolved\n\nRingkasan\n---------\nAgent backup gagal menulis arsip ke NAS selama tiga hari berturut-turut.\nDitemukan ketika verifikasi restore harian gagal pada 14:20 WIB.\n\nDampak\n------\nRPO (Recovery Point Objective) terlewati 72 jam. Backup tertua yang\ntersedia berumur 8 hari.\n\nTimeline\n--------\nHari 0  22:00 - Penulisan arsip gagal, error kredensial NAS.\nHari 1  22:00 - Ulangan otomatis gagal.\nHari 2  22:00 - Ulangan otomatis gagal.\nHari 3  06:15 - Tim NOC menemukan kegagalan restore terjadwal.\nHari 3  09:40 - Kredensial NAS diperbarui, job berhasil.\n\nRekomendasi\n------------\n1. Tambahkan alerting pada job backup yang gagal.\n2. Rotasi kredensial service account secara terjadwal.\n3. Uji restore bulanan untuk memvalidasi integritas arsip.\n",
            2 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0008\nJudul      : Malware terdeteksi pada wks-soc-14\nPrioritas  : Critical\nStatus     : Resolved\n\nRingkasan\n---------\nEndpoint detection dan response mendeteksi koneksi beacon keluar ke\nalamat IP di luar allowlist selama 45 menit.\n\nDampak\n------\nTidak ditemukan indikasi eksfiltrasi data. Host diisolasi sebelum\nakhir hari kerja.\n\nTimeline\n--------\nHari 0  10:12 - Alert beacon mencurigakan diterima tim SOC.\nHari 0  10:25 - Host diisolasi dari jaringan.\nHari 0  13:40 - Analisis forensik awal, tidak ada persistence.\nHari 1  09:00 - Host di-reimage dan dikembalikan ke pool analis.\n\nRekomendasi\n------------\n1. Perketat allowlist egress untuk subnet analis.\n2. Aktifkan modul memory scanning pada seluruh endpoint.\n3. Review daftar aplikasi yang diizinkan pada workstation analis.\n",
            3 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0001\nJudul      : Phishing campaign menyasar divisi Finance\nPrioritas  : High\nStatus     : In Progress\n\nRingkasan\n---------\nEmail phishing berisi lampiran HTML berisi form kredensial palsu\ndikirim ke 14 alamat domain, seluruhnya dari divisi Finance.\n\nDampak\n------\n9 dari 14 email diblokir gateway. 5 email sampai ke inbox.\nTidak ada kredensial yang tercatat ter-submit.\n\nTimeline\n--------\nHari 0  08:05 - User melapor email mencurigakan.\nHari 0  08:30 - Email ditandai phishing dan ditarik dari inbox.\nHari 0  09:15 - Blocker signature diperbarui.\n\nRekomendasi\n------------\n1. Aktifkan MFA pada seluruh akun divisi Finance.\n2. Effectivekan simulasi phishing pada triwulan berikutnya.\n3. Perbarui halaman informasi anti-phishing untuk tim.\n",
            4 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0004\nJudul      : Kerentanan CVE pada PAN-OS core\nPrioritas  : Critical\nStatus     : In Progress\n\nRingkasan\n---------\nPemindaian otomatis menemukan perangkat core berjalan pada versi\nfirmware yang terkena advisory Directory Traversal.\n\nDampak\n------\nBelum ada bukti eksploitasi. Eksposur terbatas pada interface\nmanajemen yang hanya dijangkau dari jaringan internal.\n\nTimeline\n--------\nHari 0  07:30 - Pemindaian terjadwal melaporkan temuan.\nHari 0  11:00 - Dikonfirmasi oleh tim NOC.\nHari 1  - Upgrade dijadwalkan pada jendela maintenance.\n\nRekomendasi\n------------\n1. Upgrade firmware core pada jendela maintenance berikutnya.\n2. Batasi akses interface manajemen ke subnet NOC.\n3. Tambahkan deteksi IoC pada log perangkat.\n",
            5 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0010\nJudul      : Weak cryptographic policy pada Active Directory\nPrioritas  : High\nStatus     : Resolved\n\nRingkasan\n---------\nAudit internal menemukan kebijakan enkripsi domain masih memuat\nalgoritma legacy yang tidak lagi direkomendasikan.\n\nDampak\n------\nRisiko downgrade cipher pada kanal autentikasi domain.\nTidak ditemukan penyalahgunaan pada log autentikasi.\n\nTimeline\n--------\nHari 0  10:00 - Audit internal menyelesaikan temuan.\nHari 1  02:00 - Kebijakan baru diterapkan.\nHari 1  06:00 - Verifikasi seluruh domain controller berhasil.\n\nRekomendasi\n------------\n1. Pantau kegagalan autentikasi setelah perubahan kebijakan.\n2. Dokumentasikan pengecualian cipher per departemen.\n3. Jadwalkan audit kriptografi berikutnya pada semester ini.\n",
            6 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0013\nJudul      : Dokumen berisi kredensial di repositori internal\nPrioritas  : High\nStatus     : Resolved\n\nRingkasan\n---------\nFile konfigurasi yang memuat kredensial service account ditemukan\npada direktori working di workstation developers.\n\nDampak\n------\nTidak ditemukan akses eksternal. Kredensial sudah dirotasi.\n\nTimeline\n--------\nHari 0  14:20 - Tim risiko menemukan file pada hasil scan.\nHari 0  15:00 - Akses file dicabut dan repositori dibersihkan.\nHari 1  09:00 - Kredensial terkait rotasi.\n\nRekomendasi\n------------\n1. Terapkan secret scanning pada pipeline integrasi.\n2. Batasi akses direktori working di workstation.\n3. Lakukan rotation terjadwal untuk seluruh service account.\n",
            7 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0002\nJudul      : Kredensial API terekspos pada log aplikasi\nPrioritas  : High\nStatus     : In Progress\n\nRingkasan\n---------\nToken API terstruktur tercatat pada berkas log aplikasi karena level
logging terlalu verbose pada lapisan middleware autentikasi.\n\nDampak\n------\nToken dapat dipakai ulang selama masa berlakunya untuk mengakses data
inventaris. Log yang bermasalah sudah dipindahkan ke arsip dengan akses
terbatas.\n\nTimeline\n--------\nHari 0  16:40 - Pemindaian log menemukan pola token berulang.
Hari 0  18:05 - Token dicabut dan diterbitkan ulang.
Hari 1  10:00 - Level logging diturunkan pada semua layanan.\n\nRekomendasi\n------------\n1. Jangan pernah menuliskan nilai kredensial ke log.
2. Terapkan pencabutan token secara otomatis saat rotasi.
3. Retensi log disingkatkan agar permukaan paparan berkurang.\n",
            8 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0006\nJudul      : Endpoint API internal dapat diakses dari jaringan publik\nPrioritas  : High\nStatus     : Resolved\n\nRingkasan\n---------\nPemindaian dari luar menemukan endpoint diagnostik yang dapat dijangkau
melalui load balancer tanpa pembatasan sumber.\n\nDampak\n------\nEndpoint tersebut membocorkan versi perangkat dan daftar host.
Tidak ditemukan data sensitif yang terekspos.\n\nTimeline\n--------\nHari 0  09:20 - Hasil pemindaian eksternal diterima.
Hari 0  11:45 - Aturan firewall sementara dipasang.
Hari 2  20:00 - Endpoint dipindahkan ke jaringan internal.\n\nRekomendasi\n------------\n1. Batasi seluruh endpoint diagnostik ke jaringan NOC.
2. Terapkan allowlist sumber pada load balancer.
3. Jadwalkan pemindaian eksternal rutin setiap triwulan.\n",
            9 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0003\nJudul      : Akun layanan dengan hak akses berlebihan\nPrioritas  : Medium\nStatus     : In Progress\n\nRingkasan\n---------\nAudit hak akses menemukan beberapa akun lama yang masih memegang
peran admin meskipun sudah dipindahkan ke divisi lain.\n\nDampak\n------\nRisikoprivilege escalation pada akun yang tidak lagi dipakai untuk
pekerjaan operasional harian.\n\nTimeline\n--------\nHari 0  13:00 - Audit kuartalan menemukan lima akun bermasalah.
Hari 1  09:30 - Empat akun langsung dikoreksi perannya.\n\nRekomendasi\n------------\n1. Terapkan tinjauan hak akses berkala setiap kuartal.
2. Cabut hak akses pada saat perpindahan jabatan.
3. Aktifkan verifikasi dua pihak untuk perubahan peran kritikal.\n",
            10 => "LAPORAN INSIDEN KEAMANAN\nSecureOps - PT Garuda Siber Nusantara\n========================================\n\nNomor Tiket : INC-2026-0005\nJudul      : Sertifikat TLS kedaluwarsa pada layanan internal\nPrioritas  : Medium\nStatus     : Resolved\n\nRingkasan\n---------\nPemindaian terjadwal menemukan dua layanan internal yang masih
memakai sertifikat kedaluwarsa lebih dari tiga bulan.\n\nDampak\n------\nKoneksi terenkripsi tetap aman namun indikator kepatuhan gagal
pada audit internal terakhir.\n\nTimeline\n--------\nHari 0  02:15 - Laporan kedaluwarsa dipulihkan.
Hari 1  01:00 - Sertifikat baru dipasang dan disublikasikan.
Hari 1  05:00 - Verifikasi rantai keystore berhasil.\n\nRekomendasi\n------------\n1. Aktifkan notifikasi kedaluwarsa 60 hari sebelum jatuh tempo.
2. Otomatiskan perpanjangan sertifikat untuk layanan internal.
3. Pantau masa berlaku sertifikat pada dashboard kepatuhan.\n",
        ];

        foreach ($reports as $id => $body) {
            file_put_contents($dir . '/' . $id . '.txt', $body);
        }
    }
}
