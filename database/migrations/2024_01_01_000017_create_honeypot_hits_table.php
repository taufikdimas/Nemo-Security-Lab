<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('honeypot_hits', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip_address');
            $table->text('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->text('payload')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('severity')->default('low');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('honeypot_hits');
    }
};
