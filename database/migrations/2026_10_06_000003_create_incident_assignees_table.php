<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_assignees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incident_id')->constrained('incidents')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['incident_id', 'user_id']);
        });

        // Backfill existing assigned_to into incident_assignees
        $existing = DB::table('incidents')
            ->whereNotNull('assigned_to')
            ->select('id', 'assigned_to', 'created_at', 'updated_at')
            ->get();

        foreach ($existing as $row) {
            DB::table('incident_assignees')->insertOrIgnore([
                'incident_id' => $row->id,
                'user_id' => $row->assigned_to,
                'created_at' => $row->created_at ?? now(),
                'updated_at' => $row->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_assignees');
    }
};
