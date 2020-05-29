<?php

use DoubleThreeDigital\CookieNotice\Http\Controllers\CookieNoticeActionController;

Route::post('/store', [CookieNoticeActionController::class, 'store'])->name('cookie-notice.store');
Route::post('/update', [CookieNoticeActionController::class, 'update'])->name('cookie-notice.update');