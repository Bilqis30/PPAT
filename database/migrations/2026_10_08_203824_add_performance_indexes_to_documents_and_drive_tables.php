<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->index('document_date');
            $table->index(['type', 'sub_type']);
            $table->index('created_at');
            $table->index('tanggal_acara');
        });

        Schema::table('drive_files', function (Blueprint $table) {
            $table->index('created_at');
        });

        Schema::table('drive_folders', function (Blueprint $table) {
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['document_date']);
            $table->dropIndex(['type', 'sub_type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['tanggal_acara']);
        });

        Schema::table('drive_files', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('drive_folders', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }
};
