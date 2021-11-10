<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PDFController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VisitController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DepartmentController;
use App\Http\Livewire\Visits;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Login route for guest users
Route::get('/', [Controller::class, 'login'])->name('loginform');

// User needs to be authenticated to enter here.
Route::group(['middleware' => 'auth'], function () {
    
    // Uses Auth Middleware

    Route::get('/visits', [VisitController::class, 'index'])->name('visits')->middleware('auth');

    Route::get('/employees', [Controller::class, 'index'])->name('employees');
    
    Route::get('/visitors', [VisitorController::class, 'index'])->name('visitors');
    
    Route::get('/departments', [DepartmentController::class, 'index'])->name('departments');
    
    Route::get('/generate-pdf/{date_search}', [Visits::class, 'createPDF'])->name('pdf');

    
});

  