<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        's_number',
        'password',
        'is_teacher',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * The courses that the user is enrolled in or teaching.
     */
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user');// Many-to-many relationship
    }

    
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    // Reviews that the student has submitted
    public function submittedReviews()
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    // Reviews that the student has received
    public function receivedReviews()
    {
        return $this->hasMany(Review::class, 'reviewee_id');
    }

    // Relationship with assessments for storing the score
    public function assessmentScores()
    {
        return $this->hasMany(AssessmentScore::class);
    }

    public function coursesTeaching()
    {
        return $this->belongsToMany(Course::class, 'course_user')->wherePivot('is_teacher', true);
    }

    // A user (student) gives many reviews
    public function reviewsGiven()
    {
        return $this->hasMany(Review::class, 'student_id'); // 'student_id' is the foreign key in the reviews table
    }

    // A user (reviewee) receives many reviews
    public function reviewsReceived()
    {
        return $this->hasMany(Review::class, 'reviewee_id'); // 'reviewee_id' is the foreign key in the reviews table
    }
}


