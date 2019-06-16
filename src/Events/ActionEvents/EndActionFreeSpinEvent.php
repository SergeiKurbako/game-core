<?php

namespace Avior\GameCore\Events\ActionEvents;

use Avior\GameCore\Events\BaseEvent;

class EndActionFreeSpinEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endActionFreeSpin';
}
