<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@garuda-siber.internal')->first();
        $analyst = User::where('email', 'user@garuda-siber.internal')->first();

        $documents = [
            [
                'original_name' => 'policy-keamanan-2026.pdf',
                'mime_type' => 'application/pdf',
                'folder' => 'documents',
                'description' => 'Dokumen kebijakan keamanan tahun 2026.',
                'is_public' => true,
                'owner' => $admin,
                'body' => "SecureOps Security Policy 2026\nInternal classification: Confidential\n",
            ],
            [
                'original_name' => 'panduan-onboarding-karyawan.pdf',
                'mime_type' => 'application/pdf',
                'folder' => 'documents',
                'description' => 'Panduan onboarding untuk karyawan baru.',
                'is_public' => true,
                'owner' => $admin,
                'body' => "Employee Onboarding Guide\nRevision 3\n",
            ],
            [
                'original_name' => 'inventaris-aset-jaringan.csv',
                'mime_type' => 'text/csv',
                'folder' => 'exports',
                'description' => 'Daftar inventaris aset jaringan internal.',
                'is_public' => true,
                'owner' => $analyst,
                'body' => "hostname,ip,environment\nsrv-backup-01,10.0.0.21,production\n",
            ],
            [
                'original_name' => 'laporan-insiden-triwulan.txt',
                'mime_type' => 'text/plain',
                'folder' => 'reports',
                'description' => 'Ringkasan insiden keamanan kuartal berjalan.',
                'is_public' => false,
                'owner' => $analyst,
                'body' => "Quarterly Incident Summary\nTotal incidents: 14\n",
            ],
            [
                'original_name' => 'runbook-backup-database.txt',
                'mime_type' => 'text/plain',
                'folder' => 'runbooks',
                'description' => 'Runbook pemulihan cadangan database.',
                'is_public' => false,
                'owner' => $admin,
                'body' => "Database Backup Runbook\nStep 1: verify snapshot timestamp\nStep 2: validate restore target\n",
            ],
            [
                'original_name' => 'konfigurasi-firewall-edge.conf',
                'mime_type' => 'text/plain',
                'folder' => 'configs',
                'description' => 'Konfigurasi firewall pada batas jaringan.',
                'is_public' => false,
                'owner' => $admin,
                'body' => "# Edge firewall baseline\nallow tcp 443\nallow tcp 22\n",
            ],
        ];

        foreach ($documents as $document) {
            $extension = explode('/', $document['mime_type'])[1];
            $storedName = Str::random(40) . '.' . $extension;

            $path = $document['folder'] . '/' . $storedName;

            Storage::disk('public')->put($path, $document['body']);

            File::updateOrCreate(
                ['path' => $path],
                [
                    'original_name' => $document['original_name'],
                    'stored_name' => $storedName,
                    'mime_type' => $document['mime_type'],
                    'file_size' => strlen($document['body']),
                    'uploaded_by' => $document['owner']?->id,
                    'description' => $document['description'],
                    'is_public' => $document['is_public'],
                ]
            );
        }
    }
}
