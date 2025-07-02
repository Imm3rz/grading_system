<?php

use Illuminate\Support\Facades\Route;

// Show login page as root
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

// Laravel built-in auth routes (login, register, etc.)
Auth::routes();

// Redirect /home to /students (you can change this)
Route::get('/home', function () {
    return redirect('/students');
});

// ✅ All protected routes inside auth
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');

    // Students (CRUD)
    Route::resource('/students', 'StudentController');

    // Grade management per student
    Route::get('/students/{student}/grades/create', 'StudentController@createGrade')->name('grades.create');
    Route::post('/students/{student}/grades', 'StudentController@storeGrade')->name('grades.store');

    // Grade editing
    Route::get('/students/{student}/grades/{grade}/edit', 'StudentController@editGrade')->name('grades.edit');
    Route::put('/students/{student}/grades/{grade}', 'StudentController@updateGrade')->name('grades.update');
    Route::delete('/students/{student}/grades/{grade}', 'StudentController@deleteGrade')->name('grades.destroy');

    // Optional direct grade editing
    Route::get('/grades/{grade}/edit', 'GradeController@edit')->name('grades.direct.edit');
    Route::put('/grades/{grade}', 'GradeController@update')->name('grades.direct.update');
    Route::delete('/grades/{grade}', 'GradeController@destroy')->name('grades.direct.destroy');
});

// ✅ Public grade checker (no login required)
Route::get('/report-card', 'StudentController@publicGradeLookupForm')->name('grades.public.form');
Route::post('/report-card', 'StudentController@publicGradeLookup')->name('grades.public.lookup');

// ✅ Grade lookup for teachers (must be protected if needed)
Route::middleware(['auth'])->group(function () {
    Route::get('/grades/check', 'StudentController@gradeLookupForm')->name('grades.lookup.form');
    Route::post('/grades/check', 'StudentController@gradeLookup')->name('grades.lookup');
});

// Logout
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');
