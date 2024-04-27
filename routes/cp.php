<?php

use DuncanMcClean\CookieNotice\Http\Controllers\ScriptsController;
use Illuminate\Support\Facades\Route;

Route::prefix('cookie-notice')->name('cookie-notice.')->group(function () {
    Route::get('scripts', [ScriptsController::class, 'edit'])->name('scripts.edit');
    Route::post('scripts', [ScriptsController::class, 'update'])->name('scripts.update');
});
