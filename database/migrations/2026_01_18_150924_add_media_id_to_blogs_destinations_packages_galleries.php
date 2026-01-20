<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->foreignId('thumbnail_media_id')->nullable()->constrained('media')->nullOnDelete();
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->foreignId('thumbnail_media_id')->nullable()->constrained('media')->nullOnDelete();
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('thumbnail_media_id')->nullable()->constrained('media')->nullOnDelete();
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('image_media_id')->nullable()->constrained('media')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_media_id']);
            $table->dropColumn('thumbnail_media_id');
        });

        Schema::table('destinations', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_media_id']);
            $table->dropColumn('thumbnail_media_id');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['thumbnail_media_id']);
            $table->dropColumn('thumbnail_media_id');
        });

        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['image_media_id']);
            $table->dropColumn('image_media_id');
        });
    }
};
