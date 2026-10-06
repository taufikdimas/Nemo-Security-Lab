<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (Schema::hasColumn('assets', 'client_id')) {
                return;
            }

            $table->foreignId('client_id')
                ->nullable()
                ->after('owner_department')
                ->constrained('clients')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            if (! Schema::hasColumn('assets', 'client_id')) {
                return;
            }

            // PENTING: dropForeign harus dijalankan juga di SQLite.
            //
            // SQLite menolak `ALTER TABLE ... DROP COLUMN` selama definisi
            // foreign key di tabel masih menunjuk kolom itu:
            //   "unknown column client_id in foreign key definition"
            //
            // dropForeign() memaksa Laravel me-build ulang tabel di SQLite,
            // sehingga FK bisa dilepas lebih dulu lalu kolomnya dibuang.
            // FK lain di tabel (created_by) tetap dipertahankan.
            //
            // PRAGMA foreign_keys=OFF / withoutForeignKeyConstraints() TIDAK
            // memperbaiki ini — keduanya sudah diuji dan tetap gagal, karena
            // masalahnya konsistensi definisi skema, bukan enforcement FK.
            $table->dropForeign(['client_id']);
            $table->dropColumn('client_id');
        });
    }
};