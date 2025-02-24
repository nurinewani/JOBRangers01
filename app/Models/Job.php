<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    use HasFactory;

    // Define the attributes that can be mass assigned
    protected $fillable = [
        'title',
        'description',
        'location',
        'latitude',
        'longitude',
        'date_from',
        'date_to',
        'salary',
        'start_time',
        'end_time',
        'application_deadline',
        'status',
        'duration',
        'recruiter_id',  // This is the key field for the relationship
    ];
    
    public function show($id)
    {
        $job = Job::findOrFail($id);
    
        // Pass job data to the view
        return view('jobmap', compact('job'));
    }

    public function getFormattedDeadlineAttribute()
    {
        return Carbon::parse($this->application_deadline)->format('M d, Y');
    }
    
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Rename this to creator to be more generic
    public function creator()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    // Helper method to get creator's name with role
    public function getCreatorWithRoleAttribute()
    {
        if (!$this->creator) {
            return 'Administrator';
        }

        $roleName = $this->creator->role == 2 ? 'Admin' : 'Recruiter';
        return "{$this->creator->name} ({$roleName})";
    }

    // Add time format accessors
    public function getFormattedStartTimeAttribute()
    {
        return Carbon::parse($this->start_time)->format('H:i');
    }

    public function getFormattedEndTimeAttribute()
    {
        return Carbon::parse($this->end_time)->format('H:i');
    }

    // Define status constants
    const STATUS_OPEN = 'open';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ACTIVE = 'active';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';

    // Define available statuses
    public static $statuses = [
        self::STATUS_OPEN => 'Open for Applications',
        self::STATUS_SCHEDULED => 'Scheduled',
        self::STATUS_ACTIVE => 'Active/Ongoing',
        self::STATUS_COMPLETED => 'Completed/Finished',
        self::STATUS_CLOSED => 'Closed',
    ];

    // Helper method to get status text
    public function getStatusTextAttribute()
    {
        return self::$statuses[$this->status] ?? 'Unknown Status';
    }

    // Helper method to check if job is open for applications
    public function isOpenForApplications()
    {
        return $this->status === self::STATUS_OPEN;
    }

    // Helper method to check if job is active
    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    // Helper method to check if job is scheduled
    public function isScheduled()
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    // Helper method to check if job is completed
    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    // Helper method to check if job is closed
    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    protected static function boot()
    {
        parent::boot();

        // Set default status to 'open' when creating a new job
        static::creating(function ($job) {
            if (!$job->status) {
                $job->status = self::STATUS_OPEN;
            }
        });

        // Auto-update status when saving
        static::saving(function ($job) {
            $now = Carbon::now();
            
            if ($job->date_from <= $now && $job->date_to > $now) {
                $job->status = self::STATUS_ACTIVE;
            } elseif ($job->date_to < $now) {
                $job->status = self::STATUS_COMPLETED;
            } elseif ($job->isDirty('status') && $job->status === self::STATUS_CLOSED) {
                // Allow manual closing of jobs
                $job->status = self::STATUS_CLOSED;
            } elseif (!$job->status) {
                $job->status = self::STATUS_OPEN;
            }

            // Optional: Add validation for status values
            $validStatuses = [
                self::STATUS_OPEN,
                self::STATUS_SCHEDULED,
                self::STATUS_ACTIVE,
                self::STATUS_COMPLETED,
                self::STATUS_CLOSED
            ];
            
            if (!in_array($job->status, $validStatuses)) {
                throw new \InvalidArgumentException('Invalid status value');
            }
        });
    }

    // Remove isPending method since we don't need it anymore
    
    // Update getFormattedStatus to ensure 'Open for Applications' is displayed
    public function getFormattedStatus()
    {
        return match($this->status) {
            self::STATUS_OPEN => 'Open for Applications',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_ACTIVE => 'Active/Ongoing',
            self::STATUS_COMPLETED => 'Completed/Finished',
            self::STATUS_CLOSED => 'Closed',
            default => 'Open for Applications'  // Default to 'Open for Applications'
        };
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Add a mutator to ensure status is always lowercase
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    // Define relationship with JobApplication
    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // If you need to cast dates
    protected $casts = [
        'date_from' => 'datetime',
        'date_to' => 'datetime',
        'application_deadline' => 'datetime',
    ];
}

