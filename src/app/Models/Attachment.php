<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Attachment extends Model
{
    use HasUlids;

    protected $fillable = [
        'path',
        'extension',
        'size',
        'taxonomy',
        'status',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::addGlobalScope('order_by_id', function (Builder $builder) {
            $builder->orderBy('id');
        });
    }
}
