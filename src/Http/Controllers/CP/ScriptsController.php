<?php

namespace DuncanMcClean\CookieNotice\Http\Controllers\CP;

use DuncanMcClean\CookieNotice\Scripts\Blueprint;
use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Statamic\CP\PublishForm;
use Statamic\Http\Controllers\CP\CpController;

class ScriptsController extends CpController
{
    public function edit()
    {
        abort_if(Auth::user()->cant('manage scripts'), 403);

        return PublishForm::make(Blueprint::blueprint())
            ->title(__('Manage Scripts'))
            ->icon(File::get(__DIR__.'/../../../../resources/svg/cookie.svg'))
            ->values(Scripts::data())
            ->submittingTo(cp_route('cookie-notice.scripts.update'));
    }

    public function update(Request $request)
    {
        abort_if(Auth::user()->cant('manage scripts'), 403);

        $values = PublishForm::make(Blueprint::blueprint())->submit($request->all());

        Scripts::save($values);

        return response()->json(['message' => 'Scripts saved']);
    }
}
