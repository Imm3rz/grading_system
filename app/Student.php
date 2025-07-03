<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'grade_level', 'parent_email', 'parent_phone'];



    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
