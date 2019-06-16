<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class EndActionSpinEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endActionSpin';
}
