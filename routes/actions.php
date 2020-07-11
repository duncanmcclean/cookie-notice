<?php

use DoubleThreeDigital\CookieNotice\Http\Controllers\CookieNoticeActionController;

Route::post('/update', [CookieNoticeActionController::class, 'update'])->name('cookie-notice.update');