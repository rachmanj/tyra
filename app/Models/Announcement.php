<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'content',
        'start_date',
        'duration_days',
        'status',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'date',
        'duration_days' => 'integer',
    ];

    /**
     * Relationship dengan User (Admin yang membuat announcement)
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope untuk mendapatkan announcement yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope untuk mendapatkan announcement yang sedang berlangsung
     */
    public function scopeCurrent($query)
    {
        $today = Carbon::today();

        return $query->where('start_date', '<=', $today)
            ->whereRaw('DATE_ADD(start_date, INTERVAL duration_days DAY) >= ?', [$today]);
    }

    /**
     * Scope untuk mendapatkan announcement yang aktif dan sedang berlangsung
     */
    public function scopeActiveAndCurrent($query)
    {
        return $query->active()->current();
    }

    /**
     * Accessor untuk mendapatkan tanggal berakhir announcement
     */
    public function getEndDateAttribute()
    {
        return $this->start_date->addDays($this->duration_days);
    }

    /**
     * Accessor untuk mengecek apakah announcement masih berlangsung
     */
    public function getIsCurrentAttribute()
    {
        $today = Carbon::today();
        return $this->start_date <= $today && $this->end_date >= $today;
    }

    /**
     * Accessor untuk mengecek apakah announcement sudah expired
     */
    public function getIsExpiredAttribute()
    {
        return $this->end_date < Carbon::today();
    }
}
