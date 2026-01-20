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
        Schema::table('packages', function (Blueprint $table) {
            // Media
            $table->string('thumbnail')->nullable()->after('slug');
            $table->json('gallery')->nullable()->after('thumbnail');

            // Basic Info
            $table->string('location')->nullable()->after('destination_id');
            $table->text('map_embed_url')->nullable()->after('location');
            $table->string('category')->default('Adventure')->after('map_embed_url');
            $table->decimal('rating', 3, 2)->default(5.00)->after('category');
            $table->integer('review_count')->default(0)->after('rating');

            // Pricing & Details
            $table->date('departure_date')->nullable()->after('duration_nights');
            $table->date('return_date')->nullable()->after('departure_date');
            $table->integer('max_participants')->nullable()->after('return_date');
            // 'destinations' list (distinct from main destination_id)
            $table->json('destinations_list')->nullable()->after('max_participants');

            // Content
            $table->text('short_description')->nullable()->after('name');
            $table->longText('long_description')->nullable()->after('short_description');
            $table->json('highlights')->nullable()->after('long_description');
            $table->json('faqs')->nullable()->after('exclusions'); // exclusions is existing

            // Status
            $table->string('status')->default('published')->after('is_exclusive');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn([
                'thumbnail',
                'gallery',
                'location',
                'map_embed_url',
                'category',
                'rating',
                'review_count',
                'departure_date',
                'return_date',
                'max_participants',
                'destinations_list',
                'short_description',
                'long_description',
                'highlights',
                'faqs',
                'status'
            ]);
        });
    }
};
