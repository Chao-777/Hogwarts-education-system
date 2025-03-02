<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'reviewee_id',
        'assessment_id',
        'review_text',
    ];

    // Relationship with the assessment
    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    // Relationship with the student who submitted the review
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship with the student being reviewed
    public function reviewee()
    {
        return $this->belongsTo(User::class, 'reviewee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'student_id'); 
    }


}