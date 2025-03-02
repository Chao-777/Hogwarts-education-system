<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToReviewsTable extends Migration
{
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Add a unique constraint to prevent duplicates
            $table->unique(['student_id', 'reviewee_id', 'assessment_id']);
        });
    }

    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            // Drop the unique constraint in case of rollback
            $table->dropUnique(['student_id', 'reviewee_id', 'assessment_id']);
        });
    }
}
