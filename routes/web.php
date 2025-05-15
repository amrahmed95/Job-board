<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobController;


Route::get("/", function () {
    return redirect()->route("jobs.index");
})->name("home");


Route::resource("jobs", JobController::class)->only([
    'index',
    'show'
]);
