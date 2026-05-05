<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Survey;
use App\Models\Response;
use App\Models\Dimension;
use App\Models\Indicator;


class Question extends Model
{
    protected $fillable = [
        'survey_id',
        'question_text',
        'dimension_id',
        'indicator_id',
        'order',
        'type',
    ];

    
    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function dimension()
    {
        return $this->belongsTo(Dimension::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
    

    public function responses()
    {
        return $this->hasMany(Response::class);
    }
}