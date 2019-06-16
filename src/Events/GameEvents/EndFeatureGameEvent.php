<?php

namespace App\Classes\GameCore\Events\GameEvents;

use App\Classes\GameCore\Events\BaseEvent;

class EndFeatureGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endFeatureGame';
}
