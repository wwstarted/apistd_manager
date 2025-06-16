<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable =[
        'name','class_id'
    ];

    public function students(){
        return $this->hasMany(Student::class);
    }

    public function classes(){
        return $this->belongsTo(Classes::class,'class_id');
    }
}
