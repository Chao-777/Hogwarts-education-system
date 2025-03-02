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
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('assessment_title', 100); 
            $table->text('instruction'); 
            $table->integer('required_review'); 
            $table->integer('max_score'); 
            $table->date('due_date'); 
            $table->time('time'); 
            $table->enum('type', ['student-select', 'teacher-assign']); 
            $table->foreignId('course_id')->constrained()->onDelete('cascade'); //link to the courses.
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
