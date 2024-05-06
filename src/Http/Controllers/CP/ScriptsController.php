<?php

namespace DuncanMcClean\CookieNotice\Http\Controllers\CP;

use DuncanMcClean\CookieNotice\Scripts\Blueprint;
use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Statamic\Http\Controllers\CP\CpController;

class ScriptsController extends CpController
{
    public function edit()
    {
        abort_if(Auth::user()->cant('manage scripts'), 403);

        $values = Scripts::data();
        $blueprint = Blueprint::blueprint();

        $fields = $blueprint->fields()->addValues($values)->preProcess();

        return view('cookie-notice::cp.scripts', [
            'blueprint' => $blueprint->toPublishArray(),
            'values' => $fields->values(),
            'meta' => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        abort_if(Auth::user()->cant('manage scripts'), 403);

        $blueprint = Blueprint::blueprint();
        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        $values = $fields->process()->values();

        Scripts::save($values->all());

        return response()->json(['message' => 'Scripts saved']);
    }
}
