<?php

namespace App\Classes\GameCore\Events\ActionEvents;

use App\Classes\GameCore\Events\BaseEvent;

class EndActionSpinEvent extends BaseEvent
{
    /** @var string название события */
    public $name = 'endActionSpin';
}
