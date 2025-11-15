<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLearningDevelopment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'title',
        'date_from',
        'date_to',
        'hours',
        'type_of_ld',
        'conducted_by',
        'venue'
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
        'hours' => 'integer'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}