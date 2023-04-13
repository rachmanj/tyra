<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tyre extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function size()
    {
        return $this->belongsTo(TyreSize::class, 'size_id')->withDefault([
            'size' => 'n/a'
        ]);
    }

    public function brand()
    {
        return $this->belongsTo(TyreBrand::class, 'brand_id')->withDefault([
            'brand' => 'n/a'
        ]);
    }

    public function pattern()
    {
        return $this->belongsTo(Pattern::class, 'pattern_id')->withDefault([
            'pattern' => 'n/a'
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id')->withDefault([
            'supplier' => 'n/a'
        ]);
    }
}
