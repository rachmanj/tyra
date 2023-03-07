<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function specifications()
    {
        return $this->belongsToMany(Specification::class, 'suppliers_specifications');
    }

    public function accountOfficer()
    {
        return $this->belongsTo(User::class, 'account_officer');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
