<?php

namespace DuncanMcClean\CookieNotice\Http\Controllers\CP;

use DuncanMcClean\CookieNotice\Scripts\Blueprint;
use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Http\Request;

class ScriptsController
{
    public function edit()
    {
        $values = Scripts::get();
        $blueprint = Blueprint::blueprint();

        $fields = $blueprint->fields()->addValues($values)->preProcess();

        return view('cookie-notice::cp.scripts', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        // TODO: validate request (ensure we have valid pixel ids, gtm thingys, etc)
//        $validated = $request->validate([]);

        $blueprint = Blueprint::blueprint();
        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        $values = $fields->process()->values();

        Scripts::save($values->all());

        return response()->json(['message' => 'Scripts saved']);
    }

    // todo: add tests
    // todo: revision field
}
