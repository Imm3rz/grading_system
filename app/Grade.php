<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = ['student_id', 'subject', 'grade'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
