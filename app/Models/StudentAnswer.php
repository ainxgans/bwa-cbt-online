<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAnswer extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'course_question_id',
        'answer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function courseQuestion(): BelongsTo
    {
        return $this->belongsTo(CourseQuestion::class);
    }
}
