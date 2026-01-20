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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            // 1. Informasi Utama
            $table->string('name')->comment('Internal Identification');
            $table->string('slug')->unique();
            $table->string('heading')->nullable();
            $table->string('subheading')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('type')->default('image'); // image, html
            $table->longText('html_content')->nullable();
            $table->boolean('is_active')->default(true);

            // 2. Asset Visual
            $table->string('image_path')->nullable();
            $table->string('bg_color')->default('#ffffff');
            $table->string('overlay_color')->default('rgba(0,0,0,0.5)');

            // 3. Placement & Jadwal
            $table->json('placements')->nullable(); // Stores array of {location, priority, is_active}
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
