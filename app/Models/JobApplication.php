<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model
{
    use HasFactory;

    // Add these constants for status values
    const STATUS_APPLIED = 'applied';
    const STATUS_APPROVED = 'approved';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_DECLINED = 'declined';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WITHDRAWN = 'withdrawn';

    protected $fillable = [
        'user_id',
        'job_id',
        'status',
    ];

    // Define relationship with User and Job models
    public function job()
    {
        return $this->belongsTo(Job::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method to get all valid statuses
    public static function getValidStatuses()
    {
        return [
            self::STATUS_APPLIED,
            self::STATUS_APPROVED,
            self::STATUS_ACCEPTED,
            self::STATUS_DECLINED,
            self::STATUS_REJECTED,
            self::STATUS_WITHDRAWN
        ];
    }
}
