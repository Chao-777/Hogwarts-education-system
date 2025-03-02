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
        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('rating')->nullable()->change();  // Change the rating column to be nullable
        });
    }
    
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->integer('rating')->nullable(false)->change();  // Revert the change if needed
        });
    }
};
