<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'faculty_code',
        'faculty_name',
        'description',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}

