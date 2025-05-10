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

        $values = $values->map(function ($scripts, $group) {
            // We only want to manipulate the consent group fields.
            if (! collect(config('cookie-notice.consent_groups'))->keyBy('handle')->has($group)) {
                return $scripts;
            }

            return collect($scripts)->map(function (array $script) {
                // When you deselect all consent types, we need to save an empty array to
                // prevent it falling back to the field's default value (all consent types).
                if ($script['script_type'] === 'google-tag-manager') {
                    if (! isset($script['gtm_consent_types'])) {
                        $script['gtm_consent_types'] = [];
                    }

                    return $script;
                }

                // Currently, Statamic's PublishForm component submits *all* values, rather than just the *visible* values.
                // This should hopefully be fixed in Statamic 6, but until then, we need to filter out the gtm_consent_types key manually.
                unset($script['gtm_consent_types']);

                return $script;
            })->all();
        });

        Scripts::save($values->all());

        return response()->json(['message' => 'Scripts saved']);
    }
}
