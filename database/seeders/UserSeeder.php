<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'name' => 'Bagus Wicaksono',
                'email' => 'admin@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'admin',
                'department' => 'Security Operations',
                'position' => 'Head of SOC',
                'phone' => '081234567890',
                'address' => 'Jakarta Selatan',
            ],
            [
                'name' => 'Dewi Anggraini',
                'email' => 'user@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'SOC',
                'position' => 'Security Analyst',
                'phone' => '081298765432',
                'address' => 'Jakarta Selatan',
            ],
            [
                'name' => 'Backup Service Account',
                'email' => 'backup@garuda-siber.internal',
                'password' => 'B@ckupS3rv1ce2024!',
                'role' => 'user',
                'department' => 'IT Infrastructure',
                'position' => 'Backup Service Account',
                'phone' => '0215550100',
                'address' => 'Jakarta Pusat',
            ],
            [
                'name' => 'Backup Service',
                'email' => 'svc.backup@garuda-siber.internal',
                'password' => 'G@rud4B4ckup#2024',
                'role' => 'user',
                'department' => 'IT Infrastructure',
                'position' => 'Backup Agent Service',
                'phone' => '0215550200',
                'address' => 'Jakarta Pusat',
            ],
            [
                'name' => 'Rizky Ramadhan',
                'email' => 'rizky.ramadhan@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'NOC',
                'position' => 'Network Engineer',
                'phone' => '081377700112',
                'address' => 'Bandung',
            ],
            [
                'name' => 'Sinta Maharani',
                'email' => 'sinta.maharani@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'SOC',
                'position' => 'Threat Intelligence Analyst',
                'phone' => '081311220033',
                'address' => 'Jakarta Selatan',
            ],
            [
                'name' => 'Agus Prasetyo',
                'email' => 'agus.prasetyo@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'IT Infrastructure',
                'position' => 'Systems Engineer',
                'phone' => '081299900122',
                'address' => 'Tangerang',
            ],
            [
                'name' => 'Putri Lestari',
                'email' => 'putri.lestari@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'Compliance',
                'position' => 'IT Auditor',
                'phone' => '081288800455',
                'address' => 'Surabaya',
            ],
            [
                'name' => 'Hendra Kusuma',
                'email' => 'hendra.kusuma@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'NOC',
                'position' => 'NOC Shift Lead',
                'phone' => '081266600677',
                'address' => 'Bekasi',
            ],
            [
                'name' => 'Maya Sari',
                'email' => 'maya.sari@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'SOC',
                'position' => 'Incident Responder',
                'phone' => '081255500888',
                'address' => 'Depok',
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar.nugroho@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'IT Infrastructure',
                'position' => 'Database Administrator',
                'phone' => '081244400999',
                'address' => 'Jakarta Utara',
            ],
            [
                'name' => 'Ratna Kumala',
                'email' => 'ratna.kumala@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'Compliance',
                'position' => 'Risk Analyst',
                'phone' => '081233300100',
                'address' => 'Yogyakarta',
            ],
            [
                'name' => 'Bayu Saputra',
                'email' => 'bayu.saputra@garuda-siber.internal',
                'password' => 'SecureOps#2024',
                'role' => 'user',
                'department' => 'NOC',
                'position' => 'Network Administrator',
                'phone' => '081222200200',
                'address' => 'Semarang',
            ],

            /*
             |--------------------------------------------------------------------------
             | Akun Client Portal
             |--------------------------------------------------------------------------
             | Dikunci ke tiga klien yang PUNYA proyek ter-seed, supaya portalnya
             | benar-benar punya data untuk dilihat (PT. Cendana Mulia s/d
             | PT. Solusi Teknindo sengaja tidak dipakai — nol proyek).
             |
             | Password demo, sama seperti akun lab lain.
             */
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@bank-nusantara.co.id',
                'password' => 'ClientPortal#2024',
                'role' => 'client',
                'department' => null,
                'position' => 'IT Manager',
                'phone' => '081200001111',
                'address' => 'Jakarta Pusat',
                'client_name' => 'PT. Bank Mandiri Digital',
            ],
            [
                'name' => 'Citra Dewi',
                'email' => 'citra.dewi@logistik-prima.co.id',
                'password' => 'ClientPortal#2024',
                'role' => 'client',
                'department' => null,
                'position' => 'Head of IT',
                'phone' => '081200002222',
                'address' => 'Surabaya',
                'client_name' => 'PT. Klik Pintar',
            ],
            [
                'name' => 'Doni Pratama',
                'email' => 'doni@startupx.id',
                'password' => 'ClientPortal#2024',
                'role' => 'client',
                'department' => null,
                'position' => 'CTO',
                'phone' => '081200003333',
                'address' => 'Jakarta Selatan',
                'client_name' => 'PT. Nusantara Digital',
            ],
        ];

        foreach ($accounts as $account) {
            // Idempotensi: seeder ini memakai `new User([...]); save()` tanpa
            // cek keberadaan, sehingga re-run akan menduplikasi seluruh akun.
            // Guard ini mencegah duplikasi tanpa menghapus user yang sudah ada.
            if (User::where('email', $account['email'])->exists()) {
                continue;
            }

            $user = new User([
                'name' => $account['name'],
                'email' => $account['email'],
                'password' => Hash::make($account['password']),
                'role' => $account['role'],
                'department' => $account['department'],
                'position' => $account['position'],
                'phone' => $account['phone'],
                'address' => $account['address'],
                'is_active' => true,
            ]);

            $user->created_at = Carbon::parse('2024-01-01 00:00:00');
            $user->api_token = User::generateApiToken($user->email, $user->created_at);
            $user->save();

            // Akun client harus tertaut ke profil kliennya, kalau tidak
            // ClientPortalController akan menolak dengan 403.
            if (($account['role'] ?? null) === 'client' && ! empty($account['client_name'])) {
                $client = Client::where('name', $account['client_name'])
                    ->orWhere('company', $account['client_name'])
                    ->first();

                if ($client) {
                    $user->client_id = $client->id;
                    $user->save();
                }
            }
        }
    }
}
