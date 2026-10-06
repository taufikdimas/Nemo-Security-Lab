<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'service')->orderBy('id')->get()->keyBy('email');
        $admin = $users['admin@garuda-siber.internal'];
        $staff = $users['user@garuda-siber.internal'];

        $projects = [
            [
                'name' => 'Pentest - Web Application',
                'description' => 'Security assessment untuk aplikasi web klien, mencakup OWASP Top 10 dan alur autentikasi.',
                'client_name' => 'PT. Maju Jaya',
                'start_date' => '2026-01-01',
                'end_date' => '2026-02-01',
                'status' => 'completed',
                'created_by' => $admin->id,
                'members' => ['rizky.ramadhan@garuda-siber.internal', 'sinta.maharani@garuda-siber.internal', 'hendra.kusuma@garuda-siber.internal'],
                'comments' => [
                    ['user@garuda-siber.internal', 'Target sudah diidentifikasi. Mulai pemindaian subdomain.'],
                    ['admin@garuda-siber.internal', 'Laporan sementara sudah dibuat dan dikirim ke klien.'],
                    ['rizky.ramadhan@garuda-siber.internal', 'Retest selesai, seluruh temuan kritis sudah tertutup.'],
                ],
            ],
            [
                'name' => 'Internal Network Audit',
                'description' => 'Audit jaringan internal mencakup segmentasi, aturan firewall, dan deteksi anomali lalu lintas.',
                'client_name' => 'Internal',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-15',
                'status' => 'completed',
                'created_by' => $staff->id,
                'members' => ['agus.prasetyo@garuda-siber.internal', 'maya.sari@garuda-siber.internal', 'putri.lestari@garuda-siber.internal'],
                'comments' => [
                    ['admin@garuda-siber.internal', 'Kapture paket selesai pada trunk utama.'],
                    ['maya.sari@garuda-siber.internal', 'Segmentasi VLAN sudah direview bersama tim jaringan.'],
                ],
            ],
            [
                'name' => 'Assessment Sistem Perbankan',
                'description' => 'Penetration test sistem inti perbankan digital dengan fokus pada lapisan otorisasi dan API layanan.',
                'client_name' => 'PT. Bank Mandiri Digital',
                'start_date' => '2026-09-15',
                'end_date' => '2026-12-15',
                'status' => 'active',
                'created_by' => $admin->id,
                'members' => ['putri.lestari@garuda-siber.internal', 'fajar.nugroho@garuda-siber.internal', 'bayu.saputra@garuda-siber.internal', 'hendra.kusuma@garuda-siber.internal'],
                'comments' => [
                    ['admin@garuda-siber.internal', 'Aturan kerja sama sudah disetujui tim risiko klien.'],
                    ['putri.lestari@garuda-siber.internal', 'Enumerasi endpoint berjalan, tiga layanan sudah terpetakan.'],
                ],
            ],
            [
                'name' => 'Cloud Security Review - AWS',
                'description' => 'Penilaian keamanan beban kerja AWS: IAM, konfigurasi penyimpanan, dan paparan jaringan.',
                'client_name' => 'PT. Klik Pintar',
                'start_date' => '2026-08-01',
                'end_date' => '2026-11-30',
                'status' => 'active',
                'created_by' => $admin->id,
                'members' => ['sinta.maharani@garuda-siber.internal', 'ratna.kumala@garuda-siber.internal', 'maya.sari@garuda-siber.internal'],
                'comments' => [
                    ['sinta.maharani@garuda-siber.internal', 'Audit IAM selesai, beberapa wildcard action terdeteksi.'],
                    ['ratna.kumala@garuda-siber.internal', 'Tinjauan snapshot sedang berjalan untuk bucket data sensitif.'],
                    ['admin@garuda-siber.internal', 'Jadwal pengujian ulang disepakati untuk dua minggu terakhir.'],
                ],
            ],
            [
                'name' => 'Pentest Aplikasi Mobile',
                'description' => 'Review keamanan aplikasi Android dan iOS, termasuk penyimpanan kredensial dancertificate pinning.',
                'client_name' => 'PT. Sejahtera Abadi',
                'start_date' => '2026-10-01',
                'end_date' => '2026-12-20',
                'status' => 'active',
                'created_by' => $staff->id,
                'members' => ['rizky.ramadhan@garuda-siber.internal', 'bayu.saputra@garuda-siber.internal', 'ratna.kumala@garuda-siber.internal'],
                'comments' => [
                    ['user@garuda-siber.internal', 'Build rilis sudah diterima tim pengujian.'],
                ],
            ],
            [
                'name' => 'Kepatuhan ISO 27001',
                'description' => 'Pendampingan control ISO 27001 untuk divisi teknologi informasi internal.',
                'client_name' => 'Internal',
                'start_date' => '2026-07-01',
                'end_date' => '2027-01-15',
                'status' => 'on_hold',
                'created_by' => $admin->id,
                'members' => ['agus.prasetyo@garuda-siber.internal', 'putri.lestari@garuda-siber.internal', 'ratna.kumala@garuda-siber.internal'],
                'comments' => [
                    ['admin@garuda-siber.internal', 'Progres dihentikan sementara menunggu hasil audit pihak kedua.'],
                ],
            ],
            [
                'name' => 'Red Team - Simulasi Ransomware',
                'description' => 'Simulasi serangan ransomware terkoordinasi untuk mengukur deteksi dan waktu respons.',
                'client_name' => 'PT. Global Teknologi',
                'start_date' => '2026-05-01',
                'end_date' => '2026-06-15',
                'status' => 'completed',
                'created_by' => $admin->id,
                'members' => ['fajar.nugroho@garuda-siber.internal', 'hendra.kusuma@garuda-siber.internal', 'sinta.maharani@garuda-siber.internal', 'agus.prasetyo@garuda-siber.internal'],
                'comments' => [
                    ['fajar.nugroho@garuda-siber.internal', 'Akses awal diperoleh dalam 40 menit sesuai skenario.'],
                    ['admin@garuda-siber.internal', 'Deteksi EDR berhasil menghentikan pergerakan lateral.'],
                    ['hendra.kusuma@garuda-siber.internal', 'Debrief bersama tim SOC sudah dilaksanakan.'],
                ],
            ],
            [
                'name' => 'Review Arsitektur API',
                'description' => 'Penilaian desain arsitektur API dan skema otorisasi, dibatalkan sebelum fase pengujian.',
                'client_name' => 'PT. Nusantara Digital',
                'start_date' => '2026-04-01',
                'end_date' => '2026-05-01',
                'status' => 'cancelled',
                'created_by' => $staff->id,
                'members' => ['maya.sari@garuda-siber.internal', 'bayu.saputra@garuda-siber.internal', 'fajar.nugroho@garuda-siber.internal'],
                'comments' => [
                    ['user@garuda-siber.internal', 'Proyek dibatalkan karena klien menunda anggaran.'],
                ],
            ],
        ];

        foreach ($projects as $data) {
            $members = $data['members'];
            $comments = $data['comments'];
            unset($data['members'], $data['comments']);

            $project = Project::create($data);

            $project->members()->syncWithoutDetaching(
                collect($members)->map(fn ($email) => $users[$email]->id)->all()
            );

            foreach ($comments as [$email, $comment]) {
                ProjectComment::create([
                    'project_id' => $project->id,
                    'user_id' => $users[$email]->id,
                    'comment' => $comment,
                ]);
            }
        }
    }
}
