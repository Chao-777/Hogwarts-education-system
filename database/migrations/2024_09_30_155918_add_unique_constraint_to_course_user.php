<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUniqueConstraintToCourseUser extends Migration
{
    public function up()
    {
        Schema::table('course_user', function (Blueprint $table) {
            // Add a unique constraint to prevent duplicate enrollments
            $table->unique(['user_id', 'course_id']);
        });
    }

    public function down()
    {
        Schema::table('course_user', function (Blueprint $table) {
            // Drop the unique constraint if we roll back the migration
            $table->dropUnique(['user_id', 'course_id']);
        });
    }
}
