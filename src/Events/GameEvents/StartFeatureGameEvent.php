<?php

namespace App\Classes\GameCore\Events\GameEvents;

use App\Classes\GameCore\Events\BaseEvent;

class StartFeatureGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'startFeatureGame';
}
