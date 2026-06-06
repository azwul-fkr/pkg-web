<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAchievementsAndCertificationsToGurusTable extends Migration
{
    public function up(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->json('achievements')->nullable()->after('social_media_linkedin');
            $table->json('certifications')->nullable()->after('achievements');
        });
    }

    public function down(): void
    {
        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn(['achievements', 'certifications']);
        });
    }
}