<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

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
}
