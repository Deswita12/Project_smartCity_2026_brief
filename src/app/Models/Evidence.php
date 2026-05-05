<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    protected $table = 'evidences';

    protected $fillable = [
        'submission_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
    ];
    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}