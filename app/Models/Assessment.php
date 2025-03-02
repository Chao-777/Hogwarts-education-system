<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    // Fillable fields
    protected $fillable = [
        'assessment_title',
        'instruction',
        'required_review',
        'max_score',
        'due_date',
        'time',
        'type',
        'course_id'
    ];
    
    protected $casts = [
        'due_date' => 'date',
    ];

    // Define relationship with Course
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship to the reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }


    public function assessmentScores()
    {
        return $this->hasMany(AssessmentScore::class)
                    ->withTimestamps();
    }
}