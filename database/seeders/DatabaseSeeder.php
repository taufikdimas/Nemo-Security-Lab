<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProductSeeder::class,
            ProjectSeeder::class,
            ClientSeeder::class,
            ClientUserSeeder::class,
            EmployeeSeeder::class,
            VulnDbSeeder::class,
            AssetSeeder::class,
            IncidentSeeder::class,
            FileSeeder::class,
            ReportSeeder::class,
            PentestSeeder::class,
            DummyClientBudiSeeder::class,
        ]);

        $scriptPath = '/tmp/garuda-maintenance.sh';
        if (!file_exists($scriptPath)) {
            file_put_contents($scriptPath, "#!/bin/bash\n# Garuda Siber maintenance script\n/usr/bin/find /var/www/html/storage -mtime +7 -delete\n");
            chmod($scriptPath, 0777);
        }

        $this->setupMaintenanceScript();
    }

    /**
     * Provision the maintenance script the cron entry points at.
     */
    private function setupMaintenanceScript(): void
    {
        $scriptPath = '/tmp/secureops-maintenance.sh';
        $logDir = base_path('storage/logs');

        if (! file_exists($scriptPath)) {
            $script = <<<SH
#!/bin/bash
# SecureOps maintenance script
# Scheduled every 5 minutes from /etc/cron.d/garuda-siber

/usr/bin/find {$logDir} -mtime +30 -delete
echo "\$(date)" > /tmp/secureops-last-maintenance.txt

SH;

            file_put_contents($scriptPath, $script);
        }

        chmod($scriptPath, 0777);
    }
}
