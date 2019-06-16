<?php

namespace App\Classes\GameCore\Events\ActionEvents;

use App\Classes\GameCore\Events\BaseEvent;

class StartActionSpinEvent extends BaseEvent
{
    /** @var string */
    public $name = 'startActionSpin';
}
