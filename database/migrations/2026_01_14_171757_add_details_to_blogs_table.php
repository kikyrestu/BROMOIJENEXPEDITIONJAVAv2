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
        Schema::table('blogs', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('title');
            $table->string('category')->nullable()->after('category_id'); // String category as alternative
            $table->json('tags')->nullable()->after('category');
            $table->string('author_name')->nullable()->after('author_id');
            $table->string('read_time')->nullable()->after('body');
            $table->string('status')->default('draft')->after('slug');
            $table->boolean('is_featured')->default(false)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blogs', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'category', 'tags', 'author_name', 'read_time', 'status', 'is_featured']);
        });
    }
};
