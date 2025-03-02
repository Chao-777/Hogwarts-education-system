<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateReviewsTableWithUniqueConstraint extends Migration
{
    public function up()
    {
        Schema::dropIfExists('reviews');

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reviewee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('assessment_id')->constrained('assessments')->onDelete('cascade');
            $table->integer('rating')->unsigned();
            $table->text('review_text');
            $table->timestamps();

            // Add the unique constraint to prevent duplicate reviews
            $table->unique(['student_id', 'reviewee_id', 'assessment_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
}
