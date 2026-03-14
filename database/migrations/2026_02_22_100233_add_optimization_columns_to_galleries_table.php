<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->string('optimized_path')->nullable()->after('image_path');
            $table->string('thumbnail_path')->nullable()->after('optimized_path');
            $table->string('alt_text')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['optimized_path', 'thumbnail_path', 'alt_text']);
        });
    }
};
