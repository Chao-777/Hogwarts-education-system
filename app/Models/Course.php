<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    // If your table name is not "courses", specify it like this:
    // protected $table = 'courses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_code',
        'course_name',
    ];

    /**
     * The users that belong to the course (students and teachers).
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'course_user');
    }

    // Fetch only students (where 'is_teacher' is false)
    public function students()
    {
        return $this->users()->where('is_teacher', false)
                             ->distinct();
    }

    // Fetch only teachers (where 'is_teacher' is true)
    public function teachers()
    {
        return $this->users()->where('is_teacher', true);
    }

    // each course has many assessments
    public function assessments()
    {
        return $this->hasMany(Assessment::class);
    }

}