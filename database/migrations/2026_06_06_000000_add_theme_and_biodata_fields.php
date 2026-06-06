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
        // Add theme preference to users
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme_preference')->default('light')->after('password');
        });

        // Add biodata fields to gurus
        Schema::table('gurus', function (Blueprint $table) {
            $table->text('bio')->nullable()->after('subject');
            $table->string('photo_path')->nullable()->after('bio');
            $table->string('website')->nullable()->after('photo_path');
            $table->string('social_media_twitter')->nullable()->after('website');
            $table->string('social_media_instagram')->nullable()->after('social_media_twitter');
            $table->string('social_media_linkedin')->nullable()->after('social_media_instagram');
            $table->json('achievements')->nullable()->after('social_media_linkedin');
            $table->json('certifications')->nullable()->after('achievements');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('theme_preference');
        });

        Schema::table('gurus', function (Blueprint $table) {
            $table->dropColumn([
                'bio',
                'photo_path',
                'website',
                'social_media_twitter',
                'social_media_instagram',
                'social_media_linkedin',
                'achievements',
                'certifications',
            ]);
        });
    }
};
