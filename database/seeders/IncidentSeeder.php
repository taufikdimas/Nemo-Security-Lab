<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Incident;
use App\Models\IncidentNote;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class IncidentSeeder extends Seeder
{
    public function run(): void
    {
        $assets = Asset::pluck('id', 'hostname');

        $admin = User::where('email', 'admin@garuda-siber.internal')->first();
        $analyst = User::where('email', 'user@garuda-siber.internal')->first();
        $responder = User::where('email', 'maya.sari@garuda-siber.internal')->first();
        $nocLead = User::where('email', 'hendra.kusuma@garuda-siber.internal')->first();
        $auditor = User::where('email', 'putri.lestari@garuda-siber.internal')->first();
        $risk = User::where('email', 'ratna.kumala@garuda-siber.internal')->first();
        $ti = User::where('email', 'sinta.maharani@garuda-siber.internal')->first();
        $dba = User::where('email', 'fajar.nugroho@garuda-siber.internal')->first();

        $incidents = [
            ['Phishing campaign menyasar divisi Finance', 'high', 'in_progress', 'wks-fin-22',
                $analyst, $responder,
                'Email phishing berisi lampiran invoice palsu dikirim ke 14 alamat domain. Email gateway memblokir 9 dari 14.'],
            ['Lonjakan trafik tidak wajar pada srv-proxy-01', 'medium', 'open', 'srv-proxy-01',
                $nocLead, $analyst,
                'Pola request tidak konsisten dengan baseline. Perlu profiling lanjutan.'],
            ['Kegagalan login berulang pada VPN', 'medium', 'in_progress', 'fw-edge-01',
                $analyst, $nocLead,
                'Teramati 240 percobaan login gagal dalam 30 menit dari 3 IP berbeda.'],
            ['Kerentanan CVE pada PAN-OS core', 'critical', 'in_progress', 'fw-core-02',
                $admin, $nocLead,
                'Vulnerability management meminta upgrade ke PAN-OS 11.1.9.'],
            ['Akses file share tanpa izin terdeteksi', 'high', 'open', 'srv-ad-01',
                $responder, $analyst,
                'Audit log menunjukkan pembacaan file departemen lain oleh satu akun.'],
            ['Sertifikat wildcard kedaluwarsa', 'low', 'open', 'fw-edge-01',
                $nocLead, $nocLead,
                'Sertifikat wildcard untuk *.garuda-siber.internal akan kedaluwarsa 21 hari lagi.'],
            ['Backup harian gagal selama 3 hari', 'critical', 'resolved', 'srv-backup-01',
                $admin, $dba,
                'Agent backup gagal menulis ke NAS. Penyebab: credential NAS kedaluwarsa.'],
            ['Malware terdeteksi pada wks-soc-14', 'critical', 'resolved', 'wks-soc-14',
                $responder, $admin,
                'EDR mendeteksi beacon mencurigakan. Host diisolasi dan di-reimage.'],
            ['Anomali login pada srv-dns-01', 'medium', 'resolved', 'srv-dns-01',
                $nocLead, $analyst,
                'Login di luar jam operasional. Setelah diperiksa merupakan maintenance terjadwal.'],
            ['Weak cryptographic policy pada Active Directory', 'high', 'resolved', 'srv-ad-01',
                $auditor, $admin,
                'Audit internal mengharuskan penonaktifan cipher lama pada kebijakan domaine.'],
            ['Perangkat unmanaged terdeteksi di subnet NOC', 'medium', 'open', 'wks-noc-07',
                $nocLead, $nocLead,
                'Dua perangkat baru muncul di DHCP lease tanpa registrasi.'],
            ['Firewall rule duplikat dan kadaluarsa', 'low', 'open', 'fw-core-02',
                $admin, $nocLead,
                '47 rule terindeks duplikat. Review dijadwalkan.'],
            ['Dokumen berisi kredensial di repositori internal', 'high', 'resolved', 'wks-dev-18',
                $risk, $admin,
                'File kredensial ditemukan pada direktori working. Repository sudah dibersihkan.'],
            ['Kegagalan sinkronisasi log ke SIEM', 'medium', 'open', 'srv-log-01',
                $ti, $admin,
                'Pipeline log berhenti sejak pukul 02:14. Investigasi underway.'],
        ];

        // Timeline entries keyed by ticket number (1-based).
        $notes = [
            1 => [
                [$ti, 'Email gateway sudah memblokir 9 dari 14 lampiran.'],
                [$responder, 'Coordinate takedown untuk 5 domain pengirim sudah dikirim ke legal.'],
                [$admin, 'Pelaporan ke pihak berwenang masih menunggu konfirmasi.'],
            ],
            2 => [
                [$nocLead, 'Profil request menunjukkan anomali pada endpoint user WIT-014.'],
                [$analyst, 'Baseline Traffic disusun ulang untuk perbandingan.'],
            ],
            3 => [
                [$analyst, '240 percobaan login gagal tercatat dalam 30 menit.'],
                [$nocLead, 'IP sumber sudah diblokir di sisi gateway.'],
            ],
            4 => [
                [$admin, 'Upgrade ke PAN-OS 11.1.9 dijadwalkan untuk akhir pekan.'],
                [$nocLead, 'Konfirmasi versi core pada fw-core-02 selesai.'],
            ],
            5 => [
                [$responder, 'Audit log mengonfirmasi satu akun membaca dua folder departemen lain.'],
                [$admin, 'Izin akses sudah dicabut dan akun diminta mengganti kata sandi.'],
            ],
            7 => [
                [$admin, 'Credential NAS kedaluwarsa. Tindak lanjut sudah dijadwalkan.'],
                [$dba, 'Credential NAS sudah diperbarui dan job backup berjalan normal kembali.'],
            ],
            8 => [
                [$responder, 'Beacon mencurigakan terkonfirmasi. Host diisolasi dari jaringan.'],
                [$admin, 'Reimage selesai dan host sudah dikembalikan ke produksi.'],
            ],
            9 => [
                [$nocLead, 'Login di luar jam operasional terverifikasi sebagai maintenance terjadwal.'],
                [$analyst, 'Tidak ada indikasi akses tidak sah. Tiket ditutup.'],
            ],
            10 => [
                [$auditor, 'Audit internal meminta menonaktifkan cipher lama pada kebijakan domain.'],
                [$admin, 'Perubahan kebijakan sudah diterapkan dan diverifikasi.'],
            ],
        ];

        foreach ($incidents as $index => $incident) {
            [$title, $priority, $status, $hostname, $reporter, $assignee, $description] = $incident;

            $created = Incident::create([
                'ticket_number' => 'INC-2026-' . str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT),
                'title' => $title,
                'description' => $description,
                'priority' => $priority,
                'status' => $status,
                'asset_id' => $assets[$hostname] ?? null,
                'reported_by' => $reporter?->id,
                'assigned_to' => $assignee?->id,
                'resolved_at' => $status === 'resolved'
                    ? Carbon::now()->subDays($index + 1)
                    : null,
            ]);

            foreach ($notes[$index + 1] ?? [] as [$author, $text]) {
                IncidentNote::create([
                    'incident_id' => $created->id,
                    'user_id' => $author?->id,
                    'note' => $text,
                ]);
            }
        }
    }
}
