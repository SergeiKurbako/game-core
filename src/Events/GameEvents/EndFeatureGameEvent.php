<?php

namespace Avior\GameCore\Events\GameEvents;

use Avior\GameCore\Events\BaseEvent;

class EndFeatureGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endFeatureGame';
}
