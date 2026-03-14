<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('destinations', 'sort_order')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->integer('sort_order')->default(0)->after('is_featured');
            });
        }

        // Set initial order based on existing IDs
        $destinations = \App\Models\Destination::orderBy('id')->get();
        foreach ($destinations as $i => $dest) {
            $dest->update(['sort_order' => $i]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('destinations', 'sort_order')) {
            Schema::table('destinations', function (Blueprint $table) {
                $table->dropColumn('sort_order');
            });
        }
    }
};
