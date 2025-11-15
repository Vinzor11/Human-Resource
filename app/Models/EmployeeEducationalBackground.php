<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducationalBackground extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'level',
        'school_name',
        'degree_course',
        'period_from',
        'period_to',
        'highest_level_units',
        'year_graduated',
        'honors_received'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}