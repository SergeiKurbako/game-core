<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class StartActionOpenGameEvent extends BaseEvent
{
    /** @var string */
    public $name = 'startActionOpenGame';
}
