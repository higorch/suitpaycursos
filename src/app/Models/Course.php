<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUlids;

    protected $fillable = [
        'teacher_id',
        'name',
        'description',
        'presentation_video_url',
        'status',
        'delivery_mode',
        'max_enrollments',
        'enrollment_deadline',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('order_by_id', function (Builder $builder) {
            $builder->orderBy('id');
        });
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }

    public function thumbnail()
    {
        return $this->morphOne(Attachment::class, 'attachable')->where('taxonomy', 'course-thumb')->where('status', 'activated');
    }
}
