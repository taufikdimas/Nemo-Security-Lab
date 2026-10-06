<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class AssetSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@garuda-siber.internal')->first();
        $noc = User::where('email', 'rizky.ramadhan@garuda-siber.internal')->first();
        $infra = User::where('email', 'agus.prasetyo@garuda-siber.internal')->first();
        $dba = User::where('email', 'fajar.nugroho@garuda-siber.internal')->first();
        $auditor = User::where('email', 'putri.lestari@garuda-siber.internal')->first();
        $nocLead = User::where('email', 'hendra.kusuma@garuda-siber.internal')->first();
        $ti = User::where('email', 'sinta.maharani@garuda-siber.internal')->first();
        $responder = User::where('email', 'maya.sari@garuda-siber.internal')->first();

        $assets = [
            ['srv-backup-01', '10.20.0.15', 'server', 'Ubuntu 22.04.4 LTS', 'IT Infrastructure', $admin,
                'Backup runner: /usr/local/sbin/garuda-nightly-backup.sh | agent config: /etc/secureops/backup-agent.conf | jadwal: /etc/cron.d/garuda-siber'],
            ['srv-proxy-01', '10.20.0.10', 'server', 'CentOS Stream 9', 'IT Infrastructure', $infra,
                'Reverse proxy untuk service internal. Managed via ansible.'],
            ['fw-core-02', '10.20.0.2', 'firewall', 'PAN-OS 11.1.4', 'NOC', $noc,
                'Firewall utama, policy review tiap quarters.'],
            ['wks-soc-14', '10.20.30.14', 'workstation', 'Windows 11 23H2', 'SOC', $admin,
                'Workstation analis shift malam.'],
            ['srv-ad-01', '10.20.0.20', 'server', 'Windows Server 2022 10.0.20348', 'IT Infrastructure', $infra,
                'Active Directory domain garuda-siber.internal.'],
            ['sw-core-01', '10.20.0.3', 'switch', 'Cisco IOS-XE 17.9.4a', 'NOC', $noc,
                'Core switch L3, VLAN management.'],
            ['wks-noc-07', '10.20.30.7', 'workstation', 'Windows 11 23H2', 'NOC', $nocLead,
                'Konsol NOC, akses equipment via jump host.'],
            ['srv-log-01', '10.20.0.30', 'server', 'Ubuntu 22.04.4 LTS', 'SOC', $ti,
                'Log aggregator, retensi 90 hari.'],
            ['wks-fin-22', '10.20.40.22', 'workstation', 'Windows 10 22H2', 'Finance', $noc,
                'Milik divisi finance, subnet terbatas.'],
            ['ap-lobby-03', '10.20.50.3', 'access-point', 'Aruba AP-515', 'NOC', $noc,
                'Access point area lobby dan-floor.'],
            ['sw-edge-05', '10.20.0.5', 'switch', 'Cisco Catalyst 9300', 'NOC', $nocLead,
                'Edge switch uplink ke ISP.'],
            ['wks-hr-09', '10.20.40.9', 'workstation', 'Windows 11 23H2', 'HR', $infra,
                'Mesin Fingerprint SDK absensi.'],
            ['srv-dns-01', '10.20.0.1', 'server', 'BIND 9.18', 'NOC', $noc,
                'DNS internal resolver.'],
            ['wks-soc-21', '10.20.30.21', 'workstation', 'Windows 11 23H2', 'SOC', $responder,
                'Workstation incident response.'],
            ['srv-vm-03', '10.20.0.40', 'hypervisor', 'VMware ESXi 8.0.2', 'IT Infrastructure', $dba,
                'Host virtualisasi, 18 VM aktif.'],
            ['fw-edge-01', '10.20.0.1', 'firewall', 'FortiGate 200F 7.2.5', 'NOC', $noc,
                'Edge firewall facing WAN.'],
            ['wks-dev-18', '10.20.60.18', 'workstation', 'Ubuntu 24.04 LTS', 'IT Infrastructure', $dba,
                'Dev workstation, akses staging.'],
            ['srv-mail-01', '10.20.0.25', 'server', 'Postfix 3.8', 'IT Infrastructure', $infra,
                'Mail gateway internal, signature spam.'],
        ];

        /*
         |--------------------------------------------------------------------------
         | Link asset → klien
         |--------------------------------------------------------------------------
         | Incident tidak punya kolom klien langsung; ia hanya punya asset_id.
         | Supaya portal Klien menampilkan incident yang relevan (bukan kosong),
         | sebagian aset ditautkan ke profil kliennya.
         |
         | ASUMSI DEMO: hostname di bawah adalah aset yang SecureOps host untuk
         | klien tersebut. Dipilih hanya agar tiap akun demo punya incident.
         */
        $clientAssets = [
            'srv-proxy-01' => 'PT. Bank Mandiri Digital',
            'fw-edge-01' => 'PT. Bank Mandiri Digital',
            'srv-vm-03' => 'PT. Bank Mandiri Digital',
            'srv-dns-01' => 'PT. Klik Pintar',
            'ap-lobby-03' => 'PT. Klik Pintar',
            'srv-log-01' => 'PT. Nusantara Digital',
            'wks-dev-18' => 'PT. Nusantara Digital',
        ];

        $clientIds = Client::query()
            ->whereIn('name', array_values($clientAssets))
            ->orWhereIn('company', array_values($clientAssets))
            ->pluck('id', 'name')
            ->merge(
                Client::query()
                    ->whereIn('company', array_values($clientAssets))
                    ->pluck('id', 'company')
            );

        foreach ($assets as $index => $asset) {
            [$hostname, $ip, $type, $os, $dept, $owner, $notes] = $asset;

            $clientName = $clientAssets[$hostname] ?? null;
            $clientId = $clientName ? $clientIds->get($clientName) : null;

            // Idempotensi: tanpa guard, `Asset::create` di dalam loop akan
            // menduplikasi seluruh 18 aset setiap kali seeder dijalankan ulang.
            $existing = Asset::where('hostname', $hostname)->first();

            if ($existing) {
                // Backfill tautan klien pada aset yang sudah ada. Field lain
                // sengaja tidak disentuh supaya data existing tidak berubah.
                if ($clientId !== null && $existing->client_id !== $clientId) {
                    $existing->client_id = $clientId;
                    $existing->save();
                }

                continue;
            }

            Asset::create([
                'hostname' => $hostname,
                'ip_address' => $ip,
                'asset_type' => $type,
                'os_version' => $os,
                'owner_department' => $dept,
                'client_id' => $clientId,
                'last_scan_date' => Carbon::now()->subDays(($index * 4) + 2)->toDateString(),
                'notes' => $notes,
                'created_by' => $owner?->id,
            ]);
        }
    }
}
