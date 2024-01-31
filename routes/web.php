<?php

use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    // Job Routes...
    Route::get('/', 'FailedJobsController@index')->name('failed-jobs.index');
    Route::get('/{id}', 'FailedJobsController@show')->name('failed-jobs.show');
});

// Catch-all Route...
Route::get('/{view?}', 'HomeController@index')->where('view', '(.*)')->name('failed-jobs');
