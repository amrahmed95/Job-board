<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobApplicationController;


Route::get("/", function () {
    return redirect()->route("jobs.index");
})->name("home");


Route::resource("jobs", JobController::class)->only([
    'index',
    'show'
]);

Route::resource("employers", EmployerController::class)->only([
    'index',
    'show'
]);

Route::get("login", [AuthController::class, "create"])->name("login");
Route::resource("auth", AuthController::class)->only([
    'create',
    'store',
    'destroy',
]);



Route::delete('logout', [AuthController::class, 'destroy'])->name('logout');
Route::delete('auth', [AuthController::class,'destroy'])->name('auth.destroy');


Route::middleware('auth')->group(function(){
    Route::resource("job.application", JobApplicationController::class)->only([
        'create',
        'show',
        'store',
        'updateStatus' => 'update',
    ]);
});
