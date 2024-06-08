<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Mail;
/* use App\Mail\TestMail;

Route::get('/test-email', function () {
    $details = [
        'title' => 'Test Email from Laravel',
        'body' => 'This is a test email sent from a Laravel application.'
    ];

    Mail::to('hello@example.com')->send(new \App\Mail\TestMail($details));

    return 'Email sent!';
}); */
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
