<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vuln_dbs', function (Blueprint $table) {
            $table->string('cve_id')->nullable()->after('id');
            // CVSS v3 runs 0.0-10.0, so 4 total digits are required; decimal(3,1)
            // would cap the score at 9.9 on engines that enforce precision.
            $table->decimal('cvss_score', 4, 1)->nullable()->after('severity');
            $table->text('affected_systems')->nullable()->after('category');
            $table->string('published_year', 4)->nullable()->after('affected_systems');
            $table->index('severity');
            $table->index('category');
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->string('employee_number')->nullable()->unique()->after('id');
            $table->date('joined_at')->nullable()->after('phone');
            $table->string('photo')->nullable()->after('employee_number');
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['employee_number', 'joined_at', 'photo']);
        });

        Schema::table('vuln_dbs', function (Blueprint $table) {
            $table->dropColumn(['cve_id', 'cvss_score', 'affected_systems', 'published_year']);
        });
    }
};
