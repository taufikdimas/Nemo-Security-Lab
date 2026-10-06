<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@garuda-siber.internal')->first();
        $user = User::where('email', 'user@garuda-siber.internal')->first();

        $products = [
            [
                'title' => 'Security Assessment Report',
                'description' => 'Laporan hasil security assessment untuk klien korporat.',
                'category' => 'Reports',
                'price' => 15000000,
                'stock' => 5,
                'status' => 'active',
                'created_by' => $admin->id,
                'sku' => 'SKU-SAR-001',
            ],
            [
                'title' => 'Penetration Testing Service',
                'description' => 'Jasa penetration testing untuk aplikasi web.',
                'category' => 'Services',
                'price' => 25000000,
                'stock' => 1,
                'status' => 'active',
                'created_by' => $admin->id,
                'sku' => 'SKU-PTS-002',
            ],
            [
                'title' => 'Incident Response Retainer',
                'description' => 'Paket incident response tahunan.',
                'category' => 'Services',
                'price' => 50000000,
                'stock' => 2,
                'status' => 'active',
                'created_by' => $user->id,
                'sku' => 'SKU-IRR-003',
            ],
            [
                'title' => 'Compliance Audit Package',
                'description' => 'Audit kepatuhan ISO 27001 dan PCI DSS.',
                'category' => 'Audit',
                'price' => 30000000,
                'stock' => 3,
                'status' => 'active',
                'created_by' => $admin->id,
                'sku' => 'SKU-CAP-004',
            ],
            [
                'title' => 'Vulnerability Database Access',
                'description' => 'Akses tahunan ke basis data kerentanan Nemo.',
                'category' => 'Subscriptions',
                'price' => 10000000,
                'stock' => 10,
                'status' => 'active',
                'created_by' => $user->id,
                'sku' => 'SKU-VDA-005',
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}