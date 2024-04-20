<?php

namespace DuncanMcClean\CookieNotice\Tags;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    protected static $handle = 'cookie_notice';

    public function index()
    {
        if ($this->context->get('live_preview')) {
            return;
        }

        return view($this->params->get('view') ?? 'cookie-notice::notice', $this->viewData());
    }

    public function scripts()
    {
        if ($this->context->get('live_preview')) {
            return;
        }

        return view('cookie-notice::scripts', $this->viewData());
    }

    protected function viewData(): array
    {
        $cookieNoticeVersion = Addon::get('duncanmcclean/cookie-notice')->version();

        return array_merge([
            'config' => [
                'domain' => config('session.domain') ?? request()->getHost(),
                'cookie_name' => config('cookie-notice.cookie_name', 'COOKIE_NOTICE'),
                'cookie_expiry' => config('cookie-notice.cookie_expiry', 14),
                'consent_groups' => config('cookie-notice.consent_groups'),
            ],
            'consent_groups' => config('cookie-notice.consent_groups'),
            // 'inline_css' => Cache::rememberForever("CookieNotice:{$cookieNoticeVersion}:InlineCss", function () {
            //     return File::get($this->getViteAssetPath('resources/css/cookie-notice.css'));
            // }),
            'inline_css' => File::get($this->getViteAssetPath('resources/css/cookie-notice.css')),
        ], $this->getGlobalsData());
    }

    /**
     * Converts the Vite asset URL to a path on the filesystem.
     */
    protected function getViteAssetPath($asset): string
    {
        // TODO: can we get away without publishing the assets?
        $manifest = json_decode(File::get(public_path('vendor/cookie-notice/build/manifest.json')), true);

        if (! isset($manifest[$asset])) {
            throw new \Exception("Cookie Notice: Unable to find {$asset} in Vite Manifest.");
        }

        return public_path('vendor/cookie-notice/build/'.$manifest[$asset]['file']);
    }


    protected function getGlobalsData(): array
    {
        $globalsData = [];

        foreach (GlobalSet::all() as $global) {
            if (! $global->existsIn(Site::current()->handle())) {
                continue;
            }

            $global = $global->in(Site::current()->handle());

            $globalsData[$global->handle()] = $global->toAugmentedArray();
        }

        return $globalsData;
    }
}
