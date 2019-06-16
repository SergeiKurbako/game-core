<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class StartActionCloseGameEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'startActionCloseGame';
}
