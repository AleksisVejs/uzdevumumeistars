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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('email_notifications')->default(true);
            $table->boolean('test_reminders')->default(true);
            $table->boolean('weekly_progress_emails')->default(true);
            $table->boolean('achievement_notifications')->default(true);
            $table->string('language', 5)->default('lv');
            $table->string('timezone', 50)->default('Europe/Riga');
            $table->json('preferences')->nullable(); // For future extensibility
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
