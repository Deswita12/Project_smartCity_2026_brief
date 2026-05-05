<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Question;


class Survey extends Model
{

    protected $fillable = [
    'name',
    'description',
    'year',
    'period_start' => 'date',
    'period_end' => 'date',
    'status',
    'link_token',
];
     protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
    ];
    

    public const STATUS_DRAFT = 'draft';
    public const STATUS_AKTIF = 'active';
    public const STATUS_SELESAI = 'done';

    public static function statuses(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_SELESAI => 'Selesai',
        ];
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
