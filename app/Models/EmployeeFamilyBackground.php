<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeFamilyBackground extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'relation',
        'surname',
        'first_name',
        'middle_name',
        'name_extension',
        'occupation',
        'employer',
        'business_address',
        'telephone_no'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}