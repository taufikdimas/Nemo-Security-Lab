<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Client;
use App\Models\Incident;
use App\Models\PentestEngagement;
use App\Models\PentestFinding;
use App\Models\PentestReport;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DummyClientBudiSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?? User::first();

        // 1. Buat / Update Profil Client
        $client = Client::updateOrCreate(
            ['email' => 'budi.santoso@bank-nusantara.co.id'],
            [
                'name' => 'PT Bank Nusantara Tbk',
                'company' => 'Bank Nusantara',
                'phone' => '+62 21 555-8899',
                'address' => 'Gedung Menara Nusantara Lt. 18, Jl. Jend. Sudirman Kav. 25, Jakarta Selatan',
                'created_by' => $admin?->id,
            ]
        );

        // 2. Buat / Update Akun User Budi Santoso
        $user = User::updateOrCreate(
            ['email' => 'budi.santoso@bank-nusantara.co.id'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password'),
                'role' => 'client',
                'client_id' => $client->id,
                'position' => 'Head of Information Security',
                'department' => 'IT Security & Compliance',
                'phone' => '+62 812-3456-7890',
                'address' => 'Gedung Menara Nusantara Lt. 18, Jakarta Selatan',
                'bio' => 'Penanggung jawab operasional keamanan siber dan pemenuhan regulasi perbankan di PT Bank Nusantara Tbk.',
                'is_active' => true,
            ]
        );

        // 3. Buat Aset Klien
        $assetsData = [
            [
                'hostname' => 'prod-cbs-core01.bank-nusantara.co.id',
                'ip_address' => '10.200.10.15',
                'asset_type' => 'server',
                'os_version' => 'Red Hat Enterprise Linux 9.3',
                'owner_department' => 'Core Banking Division',
                'last_scan_date' => Carbon::now()->subDays(3),
            ],
            [
                'hostname' => 'api-gateway.bank-nusantara.co.id',
                'ip_address' => '10.200.20.50',
                'asset_type' => 'server',
                'os_version' => 'Ubuntu 22.04 LTS',
                'owner_department' => 'Digital Banking',
                'last_scan_date' => Carbon::now()->subDays(1),
            ],
            [
                'hostname' => 'fw-perimeter-pri.bank-nusantara.co.id',
                'ip_address' => '192.168.1.1',
                'asset_type' => 'firewall',
                'os_version' => 'FortiOS 7.4.2',
                'owner_department' => 'Network Security',
                'last_scan_date' => Carbon::now()->subDays(5),
            ],
            [
                'hostname' => 'db-cluster-node01.bank-nusantara.co.id',
                'ip_address' => '10.200.30.12',
                'asset_type' => 'storage',
                'os_version' => 'Oracle Linux 8.8',
                'owner_department' => 'Database Administration',
                'last_scan_date' => Carbon::now()->subDays(2),
            ],
        ];

        $createdAssets = [];
        foreach ($assetsData as $aData) {
            $createdAssets[] = Asset::updateOrCreate(
                ['hostname' => $aData['hostname']],
                array_merge($aData, [
                    'client_id' => $client->id,
                    'created_by' => $admin?->id,
                ])
            );
        }

        // 4. Buat Insiden Keamanan
        $incidentsData = [
            [
                'ticket_number' => 'INC-2026-0812',
                'title' => 'Anomali Lonjakan Traffic & Percobaan Brute Force pada Endpoint API Mobile Banking',
                'priority' => 'high',
                'status' => 'in_progress',
                'asset_id' => $createdAssets[1]->id,
                'description' => 'Terdeteksi lonjakan percobaan otentikasi gagal sebanyak 12.500 request dalam 15 menit dari IP range luar negeri. WAF telah diaktifkan untuk auto-block.',
                'reported_by' => $admin?->id,
                'created_at' => Carbon::now()->subHours(8),
            ],
            [
                'ticket_number' => 'INC-2026-0819',
                'title' => 'Percobaan Akses Tidak Sah pada Akun Database Administrator',
                'priority' => 'medium',
                'status' => 'open',
                'asset_id' => $createdAssets[3]->id,
                'description' => 'Sistem SIEM mendeteksi aktivitas login di luar jam kerja menggunakan akun servis backup database.',
                'reported_by' => $admin?->id,
                'created_at' => Carbon::now()->subHours(20),
            ],
            [
                'ticket_number' => 'INC-2026-0798',
                'title' => 'Kerentanan Pustaka OpenSSL Terdeteksi pada Perimeter Gateway',
                'priority' => 'low',
                'status' => 'resolved',
                'asset_id' => $createdAssets[2]->id,
                'description' => 'Vulnerability scan menemukan versi pustaka OpenSSL kedaluwarsa. Telah dilakukan patch update firmware.',
                'reported_by' => $admin?->id,
                'created_at' => Carbon::now()->subDays(4),
            ],
            [
                'ticket_number' => 'INC-2026-0740',
                'title' => 'Konfigurasi TLS Drift pada Server Core Banking',
                'priority' => 'critical',
                'status' => 'resolved',
                'asset_id' => $createdAssets[0]->id,
                'description' => 'Cipher suite lemah sempat aktif saat migrasi load balancer, telah dinonaktifkan kembali sesuai standar PCI-DSS.',
                'reported_by' => $admin?->id,
                'created_at' => Carbon::now()->subDays(12),
            ],
        ];

        foreach ($incidentsData as $iData) {
            Incident::updateOrCreate(
                ['ticket_number' => $iData['ticket_number']],
                $iData
            );
        }

        // 5. Buat Proyek Klien
        $projectsData = [
            [
                'name' => 'Core Banking API Security Hardening & Penetration Testing',
                'client_name' => $client->name,
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'description' => 'Penguatan arsitektur keamanan API Gateway, integrasi WAF, serta penetration testing menyeluruh untuk ekosistem Core Banking.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Mobile Banking 3.0 Source Code Review & API Assessment',
                'client_name' => $client->name,
                'status' => 'active',
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(30),
                'description' => 'Audit keamanan kode sumber aplikasi Android & iOS serta pengujian backend API Mobile Banking 3.0.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'ISO 27001 ISMS Compliance & Vulnerability Assessment H1-2026',
                'client_name' => $client->name,
                'status' => 'completed',
                'start_date' => Carbon::now()->subDays(120),
                'end_date' => Carbon::now()->subDays(30),
                'description' => 'Pemeriksaan kepatuhan keamanan informasi standar ISO/IEC 27001:2022 dan remediasi temuan audit internal.',
                'created_by' => $admin?->id,
            ],
            [
                'name' => 'Payment Gateway PCI-DSS Readiness & Pra-Audit',
                'client_name' => $client->name,
                'status' => 'on_hold',
                'start_date' => Carbon::now()->subDays(40),
                'end_date' => Carbon::now()->addDays(90),
                'description' => 'Persiapan dan pra-audit kepatuhan standar PCI-DSS v4.0 untuk infrastruktur pemrosesan kartu pembayaran.',
                'created_by' => $admin?->id,
            ],
        ];

        $createdProjects = [];
        foreach ($projectsData as $pData) {
            $createdProjects[] = Project::updateOrCreate(
                ['name' => $pData['name']],
                $pData
            );
        }

        // 6. Buat Engagement Pentest, Temuan Kerentanan, dan Laporan Resmi
        // Engagement 1: Active In Progress
        $eng1 = PentestEngagement::updateOrCreate(
            ['code' => 'ENG-2026-BN01'],
            [
                'title' => 'Mobile Banking 3.0 & API Penetration Testing',
                'client_id' => $client->id,
                'client_name' => $client->name,
                'project_id' => $createdProjects[1]->id,
                'type' => 'web_application',
                'status' => 'in_progress',
                'start_date' => Carbon::now()->subDays(12),
                'end_date' => Carbon::now()->addDays(14),
                'target_scope' => "https://api-mbank.bank-nusantara.co.id/v3/*\nhttps://ib.bank-nusantara.co.id\nAndroid App: id.co.banknusantara.mbank (v3.0.4)\niOS App: Bank Nusantara Mobile (v3.0.4)",
                'methodology' => "OWASP API Security Top 10 2023, OWASP Mobile Security Testing Guide (MSTG), PTES Standard",
                'created_by' => $admin?->id,
                'lead_tester_id' => $admin?->id,
            ]
        );

        // Findings for Engagement 1
        $findingsEng1 = [
            [
                'finding_id' => 'VULN-BN01-01',
                'title' => 'Broken Object Level Authorization (BOLA) pada Endpoint Transfer Dana Antar Bank',
                'severity' => 'critical',
                'status' => 'in_remediation',
                'affected_component' => 'POST /v3/transfers/interbank',
                'category' => 'broken_access_control',
                'cvss_score' => '9.3',
                'cve_id' => 'CWE-285',
                'description' => 'Parameter sender_account_id pada body request transfer dapat diganti dengan nomor rekening nasabah lain tanpa adanya verifikasi kesesuaian dengan token autentikasi sesi pengirim.',
                'impact' => 'Penyerang yang terautentikasi dapat memicu transfer dana dari saldo rekening nasabah manapun tanpa persetujuan korban.',
                'recommendation' => 'Validasi identitas akun pengirim wajib dilakukan langsung dari payload access token JWT di server backend, mengabaikan parameter akun pengirim pada body request.',
                'reported_by' => $admin?->id,
            ],
            [
                'finding_id' => 'VULN-BN01-02',
                'title' => 'Insecure Direct Object Reference (IDOR) pada Histori Transaksi Mutasi Rekening',
                'severity' => 'high',
                'status' => 'open',
                'affected_component' => 'GET /v3/statements/history/{accountNo}',
                'category' => 'broken_access_control',
                'cvss_score' => '7.8',
                'cve_id' => 'CWE-639',
                'description' => 'Endpoint riwayat mutasi rekening tidak memvalidasi otorisasi kepemilikan nomor rekening nasabah yang diminta pada path parameter URL.',
                'impact' => 'Kebocoran privasi histori transaksi, saldo rekening, dan data mutasi finansial seluruh nasabah perbankan.',
                'recommendation' => 'Terapkan pemeriksaan hak akses (account ownership claim checks) pada layer API Gateway dan Service Layer sebelum mengembalikan data transaksi.',
                'reported_by' => $admin?->id,
            ],
            [
                'finding_id' => 'VULN-BN01-03',
                'title' => 'Ketiadaan Proteksi Rate Limiting pada Endpoint Verifikasi OTP Login',
                'severity' => 'medium',
                'status' => 'open',
                'affected_component' => 'POST /v3/auth/verify-otp',
                'category' => 'security_misconfiguration',
                'cvss_score' => '6.1',
                'cve_id' => 'CWE-307',
                'description' => 'Percobaan verifikasi 6 digit OTP tidak dibatasi jumlah request per menit oleh server API.',
                'impact' => 'Potensi brute force kode OTP dalam jendela waktu aktif token sebelum OTP kedaluwarsa.',
                'recommendation' => 'Batasi maksimal 3 kali kegagalan OTP per 5 menit dengan exponential backoff dan invalidasi session jika threshold terlampaui.',
                'reported_by' => $admin?->id,
            ],
            [
                'finding_id' => 'VULN-BN01-04',
                'title' => 'Sensitive Data Exposure pada Logging HTTP Client Mobile App',
                'severity' => 'low',
                'status' => 'open',
                'affected_component' => 'Android Logcat / App Binary',
                'category' => 'sensitive_data_exposure',
                'cvss_score' => '3.4',
                'cve_id' => 'CWE-532',
                'description' => 'Header Authorization Bearer token dan sebagian informasi nomor kartu debit tercetak pada log output debug aplikasi mobile.',
                'impact' => 'Informasi sensitif berpotensi diekstraksi oleh aplikasi lain yang memiliki izin akses log pada perangkat Android yang sama.',
                'recommendation' => 'Nonaktifkan seluruh ProGuard/R8 debug logging dan HTTP logging interceptor pada build release production.',
                'reported_by' => $admin?->id,
            ],
        ];

        foreach ($findingsEng1 as $fData) {
            PentestFinding::updateOrCreate(
                ['finding_id' => $fData['finding_id'], 'engagement_id' => $eng1->id],
                $fData
            );
        }

        // Report for Engagement 1
        PentestReport::updateOrCreate(
            ['report_code' => 'RPT-2026-BN01-INT'],
            [
                'engagement_id' => $eng1->id,
                'title' => 'Laporan Intermediary Penetrasi Mobile Banking 3.0',
                'report_type' => 'preliminary',
                'status' => 'published',
                'client_visible' => true,
                'published_at' => Carbon::now()->subDays(2),
                'executive_summary' => "Pengujian penetrasi komprehensif dilakukan pada infrastruktur API Mobile Banking 3.0 PT Bank Nusantara Tbk. Selama fase pengujian ditemukan 1 temuan berisiko Critical (BOLA pada transfer dana) dan 1 temuan High (IDOR mutasi rekening). Tim penguji merekomendasikan penanganan prioritas sebelum rilis publik.",
                'scope_and_methodology' => "Pengujian metode blackbox dan greybox mencakup autentikasi JWT, otorisasi transaksi, integritas payload, dan ketahanan terhadap serangan OWASP API Security Top 10 2023.",
                'conclusion' => "Arsitektur aplikasi secara umum dirancang dengan baik, namun remedi pada validasi otorisasi objek di sisi server wajib diselesaikan dan diuji ulang (retest) sebelum sistem beroperasi penuh.",
                'authored_by' => $admin?->id,
            ]
        );

        // Engagement 2: Completed / Closed
        $eng2 = PentestEngagement::updateOrCreate(
            ['code' => 'ENG-2026-BN02'],
            [
                'title' => 'External Network & Perimeter Infrastructure Security Audit',
                'client_id' => $client->id,
                'client_name' => $client->name,
                'project_id' => $createdProjects[0]->id,
                'type' => 'infrastructure',
                'status' => 'closed',
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->subDays(15),
                'target_scope' => "202.158.40.0/24 (Bank Nusantara Perimeter IP Pool)\nPublic DNS: ns1.bank-nusantara.co.id, ns2.bank-nusantara.co.id",
                'methodology' => "NIST SP 800-115, OSSTMM v3, CIS Network Security Benchmarks",
                'created_by' => $admin?->id,
                'lead_tester_id' => $admin?->id,
            ]
        );

        // Findings for Engagement 2
        $findingsEng2 = [
            [
                'finding_id' => 'VULN-BN02-01',
                'title' => 'Exposed SNMP Read-Only Community String on Edge Router',
                'severity' => 'medium',
                'status' => 'remediated',
                'affected_component' => '202.158.40.1:161/udp',
                'category' => 'security_misconfiguration',
                'cvss_score' => '5.3',
                'cve_id' => 'CWE-200',
                'description' => 'Protokol SNMP v2c aktif dengan community string default "public" pada interface publik router perimeter.',
                'impact' => 'Penyerang dapat membaca informasi topologi jaringan dan statistik routing perangkat edge.',
                'recommendation' => 'Migrasikan ke SNMPv3 dengan otentikasi SHA dan enkripsi AES serta batasi akses via ACL.',
                'reported_by' => $admin?->id,
            ],
            [
                'finding_id' => 'VULN-BN02-02',
                'title' => 'Legacy TLS 1.0 & 1.1 Support on Secondary Load Balancer',
                'severity' => 'low',
                'status' => 'remediated',
                'affected_component' => '202.158.40.10:443/tcp',
                'category' => 'security_misconfiguration',
                'cvss_score' => '3.7',
                'cve_id' => 'CWE-326',
                'description' => 'Load balancer sekunder masih mendukung protokol kriptografi lawas TLS 1.0 dan TLS 1.1.',
                'impact' => 'Rentan terhadap serangan downgrade kriptografi dan tidak memenuhi standar PCI-DSS terbaru.',
                'recommendation' => 'Nonaktifkan TLS 1.0/1.1 dan wajibkan minimal TLS 1.2 serta aktifkan TLS 1.3.',
                'reported_by' => $admin?->id,
            ],
        ];

        foreach ($findingsEng2 as $fData) {
            PentestFinding::updateOrCreate(
                ['finding_id' => $fData['finding_id'], 'engagement_id' => $eng2->id],
                $fData
            );
        }

        // Report for Engagement 2
        PentestReport::updateOrCreate(
            ['report_code' => 'RPT-2026-BN02-FIN'],
            [
                'engagement_id' => $eng2->id,
                'title' => 'Laporan Final Audit Keamanan Infrastruktur Perimeter H1-2026',
                'report_type' => 'executive',
                'status' => 'published',
                'client_visible' => true,
                'published_at' => Carbon::now()->subDays(14),
                'executive_summary' => "Audit perimeter infrastruktur jaringan PT Bank Nusantara Tbk telah selesai dilaksanakan. Seluruh 2 temuan yang teridentifikasi telah berhasil diremediasi dan diverifikasi ulang dengan status aman.",
                'scope_and_methodology' => "External vulnerability scanning, port scanning, dan manual exploitation pada 254 IP address publik milik PT Bank Nusantara Tbk.",
                'conclusion' => "Infrastruktur perimeter memenuhi kriteria kepatuhan regulasi OJK SEOJK.03/2023 dan standar industri perbankan.",
                'authored_by' => $admin?->id,
            ]
        );

        // 7. Buat Berkas Dummy Laporan SOC (.txt)
        $reportsDir = storage_path('app/reports');
        if (!is_dir($reportsDir)) {
            mkdir($reportsDir, 0755, true);
        }

        $socReports = [
            'laporan-soc-bulanan-september-2026.txt' => "=================================================================\nSECUREOPS SOC - MONTHLY SECURITY OPERATIONS REPORT\n=================================================================\nKlien       : PT Bank Nusantara Tbk\nPeriode     : 01 September 2026 - 30 September 2026\nKlasifikasi : RAHASIA / CONFIDENTIAL\n\n1. RINGKASAN MONITORING\n- Total Event Log Diproses : 142.580.920 events\n- Total Insiden Teridentifikasi : 4 insiden\n- Rata-rata Mean Time to Detect (MTTD) : 8.4 menit\n- Rata-rata Mean Time to Respond (MTTR) : 34.2 menit\n\n2. STATUS INSIDEN\n- INC-2026-0812 (High) : Brute Force API Gateway -> In Progress (WAF rule enabled)\n- INC-2026-0819 (Medium) : Unauthorized DBA Login -> Open (Investigasi)\n- INC-2026-0798 (Low) : OpenSSL Patching -> Resolved\n- INC-2026-0740 (Critical) : TLS Config Drift -> Resolved\n\n3. REKOMENDASI SOC\n- Pertahankan threshold blocking WAF pada endpoint autentikasi.\n- Perketat rotasi credential service account database.\n=================================================================",
            'laporan-threat-intelligence-q3-2026.txt' => "=================================================================\nSECUREOPS THREAT INTELLIGENCE BRIEFING - Q3 2026\n=================================================================\nKlien       : PT Bank Nusantara Tbk\nTarget Sektor : Perbankan & Jasa Keuangan Digital\n\n1. TREN ANCAMAN AKTIF\n- Kampanye Phishing bertema pembaruan mobile banking menyasar nasabah bank nasional.\n- Eksploitasi kerentanan zero-day pada VPN gateway pihak ketiga.\n- Peningkatan serangan credential stuffing terhadap API endpoint perbankan.\n\n2. INDIKATOR KOMPROMISI (IOCs)\n- IP Malicious Terverifikasi : 185.220.101.5, 45.154.255.89, 194.26.29.112\n- SHA256 Malicious APK : a1b2c3d4e5f67890abcdef1234567890abcdef1234567890abcdef1234567890\n\n3. MITIGASI DIREKOMENDASIKAN\n- Terapkan geoblocking pada IP origin berisiko tinggi.\n- Wajibkan multi-factor authentication (MFA) untuk seluruh akses remote admin.\n=================================================================",
        ];

        foreach ($socReports as $filename => $content) {
            file_put_contents($reportsDir . DIRECTORY_SEPARATOR . $filename, $content);
        }
    }
}
