<?php

namespace Avior\GameCore\Events\GameEvents;

use Avior\GameCore\Events\BaseEvent;

class StartFeatureGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'startFeatureGame';
}
