<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('label')->nullable();
            $table->timestamps();

            $table->index(['token', 'used_at', 'expires_at']);
        });

        if (!Schema::hasColumn('testimonials', 'review_token_id')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->unsignedBigInteger('review_token_id')->nullable()->after('id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('testimonials', 'review_token_id')) {
            Schema::table('testimonials', function (Blueprint $table) {
                $table->dropColumn('review_token_id');
            });
        }

        Schema::dropIfExists('review_tokens');
    }
};
