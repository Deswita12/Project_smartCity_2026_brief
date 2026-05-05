<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'question_id',
        'respondent_id',
        'answer',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}