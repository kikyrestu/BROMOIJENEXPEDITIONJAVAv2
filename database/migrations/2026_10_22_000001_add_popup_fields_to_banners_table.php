<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->boolean('popup_enabled')->default(false)->after('is_active');
            $table->unsignedInteger('popup_delay_seconds')->default(3)->after('popup_enabled');
            $table->string('popup_frequency')->default('once_per_user')->after('popup_delay_seconds');
            // once_per_user | once_per_session | always | every_n_days
            $table->unsignedInteger('popup_interval_days')->nullable()->after('popup_frequency');
            $table->json('popup_pages')->nullable()->after('popup_interval_days'); // ["*"] | ["home", "packages"]
            $table->string('popup_device')->default('all')->after('popup_pages');
            // all | mobile | desktop
            $table->unsignedInteger('popup_max_width')->default(480)->after('popup_device');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn([
                'popup_enabled', 'popup_delay_seconds', 'popup_frequency',
                'popup_interval_days', 'popup_pages', 'popup_device', 'popup_max_width',
            ]);
        });
    }
};