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
        Schema::table('tests', function (Blueprint $table) {
            if (!Schema::hasColumn('tests', 'test_type')) {
                $table->enum('test_type', ['self', 'final'])->after('type');
            }
            if (!Schema::hasColumn('tests', 'grade')) {
                $table->unsignedTinyInteger('grade')->nullable()->after('test_type');
            }
            if (!Schema::hasColumn('tests', 'status')) {
                $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('in_progress')->after('grade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tests', function (Blueprint $table) {
            $table->dropColumn(['test_type', 'grade', 'status']);
        });
    }
};