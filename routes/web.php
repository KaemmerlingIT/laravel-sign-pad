<?php

use Illuminate\Support\Facades\Route;
use Kaemmerlingit\LaravelSignPad\Controllers\LaravelSignPadController;

Route::post('kaemmerlingit/sign-pad', LaravelSignPadController::class)->name('sign-pad::signature');
