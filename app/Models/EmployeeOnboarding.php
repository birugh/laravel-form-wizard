<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    protected $fillable = [
        'status',
        'personal_information',
        'job_details',
        'access_rights',
        'evidences',
        'created_by',
    ];

    protected $casts = [
        'personal_information' => 'array',
        'job_details' => 'array',
        'access_right' => 'array',
        'evidences' => 'array',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
