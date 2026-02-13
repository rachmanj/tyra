<?php

namespace App\Models;

use App\Services\DashboardService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function booted()
    {
        static::saved(fn () => DashboardService::clearDashboardCache());
    }

    public function tyre()
    {
        return $this->belongsTo(Tyre::class);
    }

    public function removalReason()
    {
        return $this->belongsTo(RemovalReason::class)->withDefault([
            'description' => 'N/A'
        ]);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }    
}
