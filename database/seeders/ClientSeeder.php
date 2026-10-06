<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', '!=', 'service')->orderBy('id')->get()->keyBy('email');
        $admin = $users['admin@garuda-siber.internal'];
        $staff = $users['user@garuda-siber.internal'];

        $clients = [
            [
                'name' => 'PT. Maju Jaya',
                'email' => 'info@majujaya.co.id',
                'phone' => '021-123456',
                'company' => 'Maju Jaya Group',
                'address' => 'Jakarta',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'PT. Sejahtera Abadi',
                'email' => 'contact@sejahtera.co.id',
                'phone' => '021-654321',
                'company' => 'Sejahtera Abadi',
                'address' => 'Bandung',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'PT. Global Teknologi',
                'email' => 'hello@globaltech.co.id',
                'phone' => '021-111222',
                'company' => 'Global Teknologi',
                'address' => 'Surabaya',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'PT. Bank Mandiri Digital',
                'email' => 'security@bankmandiridigital.co.id',
                'phone' => '021-500111',
                'company' => 'Bank Mandiri Digital',
                'address' => 'Jakarta',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'PT. Klik Pintar',
                'email' => 'it@klikpintar.io',
                'phone' => '021-222333',
                'company' => 'Klik Pintar',
                'address' => 'Jakarta',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'PT. Nusantara Digital',
                'email' => 'contact@nusantaradigital.co.id',
                'phone' => '021-777888',
                'company' => 'Nusantara Digital',
                'address' => 'Yogyakarta',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'PT. Cendana Mulia',
                'email' => 'admin@cendanamulia.co.id',
                'phone' => '031-334455',
                'company' => 'Cendana Mulia',
                'address' => 'Surabaya',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'PT. Anugerah Pratama',
                'email' => 'cs@anugerahpratama.co.id',
                'phone' => '021-998877',
                'company' => 'Anugerah Pratama',
                'address' => 'Bekasi',
                'created_by' => $admin->id,
            ],
            [
                'name' => 'CV. Sumber Makmur',
                'email' => 'halo@sumbermakmur.co.id',
                'phone' => '0271-556677',
                'company' => 'Sumber Makmur',
                'address' => 'Solo',
                'created_by' => $staff->id,
            ],
            [
                'name' => 'PT. Solusi Teknindo',
                'email' => 'info@teknindo.co.id',
                'phone' => '021-443322',
                'company' => 'Teknindo Solution',
                'address' => 'Jakarta',
                'created_by' => $admin->id,
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
