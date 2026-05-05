<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDimension extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'name',
        'description',
        'order',
        // 'code',
    ];

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }
}