<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'start',
        'end',
        'repeat',
        'repeat_days',
        'schedule_date'
    ];

    protected $casts = [
        'repeat_days' => 'json',
        'start' => 'datetime',
        'end' => 'datetime',
        'schedule_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
