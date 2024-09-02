<?php

use App\Controllers\IndexController;
use Illuminate\Support\Facades\Route;


Route::get('/api', [IndexController::class, 'index']);


// health check url
Route::any('/ping', function () {
    return \App\Utils\RespUtil::sucJson();
});
