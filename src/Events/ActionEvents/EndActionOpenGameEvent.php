<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class EndActionOpenGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endActionOpenGame';
}
