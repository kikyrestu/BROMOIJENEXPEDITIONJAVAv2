<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('adjustment_type')->comment('percentage, fixed');
            $table->decimal('amount', 15, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('page_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained()->cascadeOnDelete();
            $table->json('content_snapshot');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('title_text')->nullable();
            $table->string('disk')->default('public');
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('media_assets');
        Schema::dropIfExists('page_revisions');
        Schema::dropIfExists('price_adjustments');
    }
};
