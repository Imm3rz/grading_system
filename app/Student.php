<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['student_id', 'name', 'grade_level'];


    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
