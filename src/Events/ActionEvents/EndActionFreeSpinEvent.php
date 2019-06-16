<?php

namespace App\Classes\GameCore\Events\ActionEvents;

use App\Classes\GameCore\Events\BaseEvent;

class EndActionFreeSpinEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endActionFreeSpin';
}
