<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add icon and description to categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->string('icon')->nullable()->after('type');
            $table->text('description')->nullable()->after('icon');
            $table->integer('sort_order')->default(0)->after('description');
            $table->boolean('show_in_navbar')->default(true)->after('sort_order');
        });

        // Add category_id foreign key to packages table
        Schema::table('packages', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('category')->constrained('categories')->nullOnDelete();
        });

        // Migrate existing string-based category data to category records
        $packages = \App\Models\Package::whereNotNull('category')->where('category', '!=', '')->get();
        $categoryMap = [];

        foreach ($packages as $package) {
            $categoryName = trim($package->category);
            if (empty($categoryName)) continue;

            $slug = \Illuminate\Support\Str::slug($categoryName);
            if (!isset($categoryMap[$slug])) {
                $category = \App\Models\Category::where('slug', $slug)->first();
                if (!$category) {
                    $category = \App\Models\Category::create([
                        'name' => $categoryName,
                        'slug' => $slug,
                        'type' => 'package',
                        'show_in_navbar' => true,
                    ]);
                } else {
                    // Update existing category to be package type if needed
                    if ($category->type !== 'package') {
                        $category->update(['type' => 'package', 'show_in_navbar' => true]);
                    }
                }
                $categoryMap[$slug] = $category->id;
            }

            $package->update(['category_id' => $categoryMap[$slug]]);
        }
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['icon', 'description', 'sort_order', 'show_in_navbar']);
        });
    }
};
