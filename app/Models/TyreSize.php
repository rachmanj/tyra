<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TyreSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
    ];

    public function tyres()
    {
        return $this->hasMany(Tyre::class, 'size_id', 'id');
    }
}
