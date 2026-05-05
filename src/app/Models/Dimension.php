<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dimension extends Model {
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description',
        'color', 'order', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
        ];

    public function subDimensions() {
        return $this->hasMany(SubDimension::class);
    }

    public function indicators() {
        return $this->hasMany(Indicator::class);
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }
}
