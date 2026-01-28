<?php

use Illuminate\Support\Facades\Route;

// auth
Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::livewire('/login', App\Livewire\Auth\Login::class)->name('login');
});

// panel
Route::group(['prefix' => 'panel', 'as' => 'panel.'], function () {

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::livewire('/', App\Livewire\Panel\Profile\Index::class)->name('index');
    });

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::livewire('/', App\Livewire\Panel\Dashboard\Index::class)->name('index');
    });

    Route::group(['prefix' => 'students', 'as' => 'students.'], function () {
        Route::livewire('/', App\Livewire\Panel\Student\Index::class)->name('index');
        Route::livewire('/save', App\Livewire\Panel\Student\Save::class)->name('save');
        Route::livewire('/{ulid}/save', App\Livewire\Panel\Student\Save::class)->name('edit');
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::livewire('/', App\Livewire\Panel\Course\Index::class)->name('index');
        Route::livewire('/save', App\Livewire\Panel\Course\Save::class)->name('save');
        Route::livewire('/{ulid}/save', App\Livewire\Panel\Course\Save::class)->name('edit');
    });
});

// student
Route::group(['prefix' => 'students', 'as' => 'students.'], function () {

    Route::group(['prefix' => 'profile', 'as' => 'profile.'], function () {
        Route::livewire('/', App\Livewire\Student\Profile\Index::class)->name('index');
    });

    Route::group(['prefix' => 'dashboard', 'as' => 'dashboard.'], function () {
        Route::livewire('/', App\Livewire\Student\Dashboard\Index::class)->name('index');
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.'], function () {
        Route::livewire('/', App\Livewire\Student\Course\Index::class)->name('index');
    });
});

// public
Route::livewire('/', App\Livewire\Public\Home::class)->name('home');
Route::livewire('/course/{slug}', App\Livewire\Public\Course::class)->name('course');
