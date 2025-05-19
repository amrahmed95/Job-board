<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;
use App\Http\Controllers\EmployerController;

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
