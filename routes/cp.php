<?php

use DuncanMcClean\CookieNotice\Http\Controllers\CP\ScriptsController;
use Illuminate\Support\Facades\Route;

Route::prefix('cookie-notice')->name('cookie-notice.')->group(function () {
    Route::get('scripts', [ScriptsController::class, 'edit'])->name('scripts.edit');
    Route::patch('scripts', [ScriptsController::class, 'update'])->name('scripts.update');
});
