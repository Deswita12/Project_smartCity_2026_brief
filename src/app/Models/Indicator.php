<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Model;

// class Indicator extends Model {
//     use HasFactory;

//     protected $fillable = [
//         'dimension_id', 'sub_dimension_id', 'name',
//         'code', 'description', 'iso_standard',
//         'weight', 'status', 'year', 'task_owner_id'
//     ];

//     public function dimension() { return $this->belongsTo(Dimension::class); }
//     public function subDimension() { return $this->belongsTo(SubDimension::class); }
//     public function taskOwner() { return $this->belongsTo(Opd::class, 'task_owner_id'); }
//     public function submissions() { return $this->hasMany(Submission::class); }
//     public function questions() { return $this->hasMany(Question::class); }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'dimension_id',
        'sub_dimension_id',
        'task_owner_id',
        'name',
        'code',
        'description',
        'iso_standard',
        'weight',
        'status',
        'year',
        
    ];

    protected $casts = [
        'weight' => 'float',
    ];

    // status: aktif | nonaktif

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function subDimension()
    {
        return $this->belongsTo(SubDimension::class);
    }

    public function taskOwner()
    {
        return $this->belongsTo(Opd::class, 'task_owner_id');
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}