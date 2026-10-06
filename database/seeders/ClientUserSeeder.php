<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Mengaitkan akun Client Portal ke profil kliennya.
 *
 * Kenapa seeder terpisah? DatabaseSeeder menjalankan UserSeeder SEBELUM
 * ClientSeeder (ClientSeeder butuh user admin sebagai created_by). Artinya
 * saat UserSeeder membuat akun client, baris `clients` belum ada dan
 * client_id tidak bisa diisi.
 *
 * UserSeeder tetap mencoba mengisi client_id langsung — itu jalan di DB yang
 * kliennya sudah ada. Seeder ini menutup kasusnya: setelah ClientSeeder
 * selesai, setiap akun role=client yang client_id-nya masih NULL
 * dicocokkan berdasarkan nama klien yang sudah ter-attach di metadata akun.
 *
 * Idempoten: aman dijalankan berulang kali.
 */
class ClientUserSeeder extends Seeder
{
    /**
     * Peta email akun client → nama klien (dicocokkan ke name atau company).
     */
    private const CLIENT_MAP = [
        'budi.santoso@bank-nusantara.co.id' => 'PT. Bank Mandiri Digital',
        'citra.dewi@logistik-prima.co.id' => 'PT. Klik Pintar',
        'doni@startupx.id' => 'PT. Nusantara Digital',
    ];

    public function run(): void
    {
        foreach (self::CLIENT_MAP as $email => $clientName) {
            $user = User::where('email', $email)->first();

            if (! $user) {
                $this->command?->warn("ClientUserSeeder: akun {$email} tidak ada, dilewati.");
                continue;
            }

            if ($user->client_id) {
                // Sudah tertaut (mis. oleh UserSeeder pada DB yang sudah ada).
                continue;
            }

            $client = Client::where('name', $clientName)
                ->orWhere('company', $clientName)
                ->first();

            if (! $client) {
                $this->command?->warn("ClientUserSeeder: klien {$clientName} tidak ditemukan, {$email} tetap tanpa client_id.");
                continue;
            }

            $user->client_id = $client->id;
            $user->save();

            $this->command?->info("ClientUserSeeder: {$email} → {$client->name}");
        }
    }
}