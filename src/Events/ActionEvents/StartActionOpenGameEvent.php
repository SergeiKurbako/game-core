<?php

namespace App\Classes\GameCore\Events\ActionEvents;

use App\Classes\GameCore\Events\BaseEvent;

class StartActionOpenGameEvent extends BaseEvent
{
    /** @var string */
    public $name = 'startActionOpenGame';
}
