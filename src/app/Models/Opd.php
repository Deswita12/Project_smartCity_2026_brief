<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Submission;


class Opd extends Model
{
    use HasFactory;

    protected $fillable = [
        // 'dimension_id',
        // 'sub_dimension_id',
        'name',
        'code',
        'user.name',
        'user.email',
        'user.password',
        // 'description',
        // 'iso_standard',
        // 'weight',
        'status',
        'year',
        // 'joined_at',
        'task_owner_id',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    public function user()
    {
        return $this->hasOne(User::class);
    }
}