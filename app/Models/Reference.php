<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'first_name',
        'middle_initial',
        'surname',
        'address',
        'telephone_no'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}