<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOtherInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'skill_or_hobby',
        'non_academic_distinctions',
        'memberships'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}