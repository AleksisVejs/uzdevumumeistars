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
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedTinyInteger('grade')->default(1)->after('topic_id');
            $table->string('subtopic')->nullable()->after('grade');
            $table->enum('question_type', ['single_choice', 'multiple_choice', 'numeric', 'text', 'image_choice'])->default('single_choice')->after('subtopic');
            $table->json('metadata')->nullable()->after('question_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['grade', 'subtopic', 'question_type', 'metadata']);
        });
    }
};
