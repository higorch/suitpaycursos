<?php

namespace App\Models;

use App\Models\Scopes\CourseScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasUlids;

    protected $fillable = [
        'creator_id',
        'name',
        'description',
        'slug',
        'presentation_video_url',
        'status',
        'delivery_mode',
        'max_enrollments',
        'enrollment_deadline',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new CourseScope);

        static::addGlobalScope('order_by_id', function (Builder $builder) {
            $builder->orderBy('id');
        });
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
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
