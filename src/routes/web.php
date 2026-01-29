<?php

use Illuminate\Support\Facades\Route;

// auth
Route::group(['prefix' => 'auth', 'as' => 'auth.', 'middleware' => ['auth.guests']], function () {
    Route::livewire('/login', App\Livewire\Auth\Login::class)->name('login');
});

// panel
Route::group(['prefix' => 'panel', 'as' => 'panel.', 'middleware' => ['user.auth', 'panel.restrict']], function () {

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::livewire('/', App\Livewire\Panel\Profile\Index::class)->name('index');
    });

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::livewire('/', App\Livewire\Panel\Dashboard\Index::class)->name('index');
    });

    Route::group(['middleware' => ['admin.restrict']], function () {
        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::livewire('/', App\Livewire\Panel\User\Index::class)->name('index');
            Route::livewire('/save', App\Livewire\Panel\User\Save::class)->name('save');
            Route::livewire('/{ulid}/save', App\Livewire\Panel\User\Save::class)->name('edit');
        });
    });

    Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
        Route::livewire('/', App\Livewire\Panel\Student\Index::class)->name('index');
        Route::livewire('/save', App\Livewire\Panel\Student\Save::class)->name('save');
        Route::livewire('/{ulid}/save', App\Livewire\Panel\Student\Save::class)->name('edit');
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::livewire('/', App\Livewire\Panel\Course\Index::class)->name('index');
        Route::livewire('/save', App\Livewire\Panel\Course\Save::class)->name('save');
        Route::livewire('/{id}/save', App\Livewire\Panel\Course\Save::class)->name('edit');
    });
});

// student
Route::group(['prefix' => 'student', 'as' => 'student.', 'middleware' => ['user.auth']], function () {

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::livewire('/', App\Livewire\Student\Profile\Index::class)->name('index');
    });

    Route::group(['prefix' => 'catalogs', 'as' => 'catalogs.'], function () {
        Route::livewire('/', App\Livewire\Student\Catalog\Index::class)->name('index');
        Route::livewire('creator/{at}/course/{slug}/', App\Livewire\Student\Catalog\Single::class)->name('single');
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::livewire('/', App\Livewire\Student\Course\Index::class)->name('index');
    });
});

// public
Route::get('/', function () {
    return redirect()->route('auth.login');
});
