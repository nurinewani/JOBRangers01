<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Crypt; // Import Crypt for encryption
use Illuminate\Support\Facades\Storage; // For storing files
use App\Notifications\RecruiterApplicationNotification;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public $table = 'users';

    const ROLE_USER = 0;
    const ROLE_RECRUITER = 1;
    const ROLE_ADMIN = 2;
    
    const RECRUITER_STATUS_PENDING = 'pending';
    const RECRUITER_STATUS_APPROVED = 'approved';
    const RECRUITER_STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'address',
        'role',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'duitnow_qr',
        'profile_picture',  // Added profile photo field
        'recruiter_request_status',  // Add this line
    ];

    public function getSpatieRoleAttribute()
    {
        return self::$roleMap[$this->attributes['role']] ?? null;
    }

    public function isAdmin()
    {
        return $this->role === 2; // Assuming 2 is ADMIN
    }

    public function isRecruiter()
    {
        return $this->role === 1; // Assuming 1 is RECRUITER
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class); // Assuming the model is named `Schedule`
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function recruiterApplication()
    {
        return $this->hasOne(RecruiterApplication::class);
    }

    public function applyForRecruiter($data)
    {
        // Send notification to admin
        $admins = User::where('role', self::ROLE_ADMIN)->get();
        
        foreach ($admins as $admin) {
            $admin->notify(new RecruiterApplicationNotification([
                'user_id' => $this->id,
                'user_name' => $this->name,
                'company_name' => $data['company_name'],
                'company_website' => $data['company_website'] ?? null,
                'motivation' => $data['motivation'] ?? null,
                // Add any other relevant data you want to pass
            ]));
        }
    }

    public function canApplyForRecruiter()
    {
        return $this->role === self::ROLE_USER;
    }

    // Encrypt bank details before saving them
    public function setBankAccountNumberAttribute($value)
    {
        $this->attributes['bank_account_number'] = ($value); // Encrypt account number
    }

    // Optional: You can add encryption for DuitNow QR if necessary
    public function setDuitNowQrAttribute($value)
    {
        $this->attributes['duitnow_qr'] = $value; // Save QR data directly
    }

    // Optional: You can add a method for saving/uploading DuitNow QR image URL
    // If you decide to save the actual QR code URL/image:
    public function setDuitNowQrImageAttribute($value)
    {
        // Assume we handle the file upload here and store the file URL.
        // Store the QR image URL
        $this->attributes['duitnow_qr'] = $value;
    }

    /**
     * Handle the upload and storage of profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @return string
     */
    public function uploadProfilePhoto($file)
    {
        // Save the file to the public disk and get the file path
        $filePath = $file->store('profile_picture', 'public');
        
        // Save the path to the database
        $this->profile_picture = $filePath;
        $this->save();

        return $filePath;  // Optional: Return the file path for further use
    }

    // Retrieve the full URL for the profile photo
    public function getProfilePhotoUrl()
    {
        return $this->profile_picture ? Storage::url($this->profile_picture) : null;  // Return full URL
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Add this method to set default role
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (!isset($user->role)) {
                $user->role = self::ROLE_USER; // Set default role to USER (0)
            }
        });
    }

    public function hasRole($role)
    {
        return $this->roles->pluck('name')->contains($role);
    }

    /**
     * Get the job applications for the user
     */
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }
}
