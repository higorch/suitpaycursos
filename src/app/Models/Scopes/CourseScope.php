<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CourseScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();
            $role = $user->role;

            // Acesso geral role admin
            if (in_array($role, ['admin'])) return;

            $builder->whereHas('creator', function ($query) use ($user) {
                $query->where('id', $user->id);
            });
        }
    }
}
