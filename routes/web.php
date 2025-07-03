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

    // Grades per student
    Route::get('/students/{student}/grades/create', 'StudentController@createGrade')->name('grades.create');
    Route::post('/students/{student}/grades', 'StudentController@storeGrade')->name('grades.store');

    // Edit/update/delete grades using GradeController (Recommended separation)
    Route::get('/grades/{grade}/edit', 'GradeController@edit')->name('grades.edit');
    Route::put('/grades/{grade}', 'GradeController@update')->name('grades.update');
    Route::delete('/grades/{grade}', 'GradeController@destroy')->name('grades.destroy');

    // Grade lookup (internal)
    Route::get('/grades/check', 'StudentController@gradeLookupForm')->name('grades.lookup.form');
    Route::post('/grades/check', 'StudentController@gradeLookup')->name('grades.lookup');

    // Send grades to parent
    Route::post('/students/{id}/send-grades', 'StudentController@sendGradesToParent')->name('students.sendGrades');
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


Route::post('/students/{id}/send-grades', 'StudentController@sendGradesToParent')->name('students.sendGrades');
// Add this in web.php — not inside any controller
Route::get('/test-send', function () {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.resend.com/emails');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer re_MxcSQBZY_Paf22mYCcwkkYM8EBmtuUtHy',
        'Content-Type: application/json'
    ));

    $postData = array(
        "from" => "onboarding@resend.dev",
        "to" => "johnemermartin@gmail.com",
        "subject" => "Test Email",
        "html" => "<p>This is a test email</p>"
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));

    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    return response()->json([
        'response' => $response,
        'status' => $status,
        'error' => $error
    ]);
});
