<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class StartActionSpinEvent extends BaseEvent
{
    /** @var string */
    public $name = 'startActionSpin';
}
