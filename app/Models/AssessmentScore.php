<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssessmentScore extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'assessment_id', 'score'];

public function user()
{
    return $this->belongsTo(User::class);
}

public function assessment()
{
    return $this->belongsTo(Assessment::class);
}

}
