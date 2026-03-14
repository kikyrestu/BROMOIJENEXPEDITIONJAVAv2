<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->string('auto_load')->default('none')->after('target')
                  ->comment('none = manual only, destination_packages = auto-load destinations with their packages as sub-items');
        });
    }

    public function down(): void
    {
        Schema::table('navigation_menus', function (Blueprint $table) {
            $table->dropColumn('auto_load');
        });
    }
};
