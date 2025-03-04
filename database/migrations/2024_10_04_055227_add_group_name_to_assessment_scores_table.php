<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->string('group_name')->nullable();  // Add group name for teacher-assigned groups
        });
    }
    
    public function down()
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            $table->dropColumn('group_name');
        });
    }
};
