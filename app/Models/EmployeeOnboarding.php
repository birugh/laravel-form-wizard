<?php

namespace App\Models;

use App\OnboardingStatus;
use Illuminate\Database\Eloquent\Model;

class EmployeeOnboarding extends Model
{
    protected $fillable = [
        'status',
        'personal_information',
        'job_details',
        'access_rights',
        'created_by',
        'submitted_at',
    ];

    protected $casts = [
        'status' => OnboardingStatus::class,
        'personal_information' => 'array',
        'job_details' => 'array',
        'access_rights' => 'array',
        'submitted_at' => 'datetime',
    ];

    public function isDraft(): bool
    {
        return $this->status === OnboardingStatus::DRAFT;
    }

    public function isSubmitted(): bool
    {
        return $this->status === OnboardingStatus::SUBMITTED;
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
