<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseAnswer extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'answer',
        'course_question_id',
        'is_correct',
    ];

    public function courseQuestion(): BelongsTo
    {
        return $this->belongsTo(CourseQuestion::class);
    }

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }
}
