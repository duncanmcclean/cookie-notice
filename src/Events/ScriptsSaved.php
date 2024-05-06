<?php

namespace DuncanMcClean\CookieNotice\Events;

use Statamic\Contracts\Git\ProvidesCommitMessage;
use Statamic\Events\Event;

class ScriptsSaved extends Event implements ProvidesCommitMessage
{
    public function commitMessage()
    {
        return __('Cookie Notice scripts updated', [], config('statamic.git.locale'));
    }
}