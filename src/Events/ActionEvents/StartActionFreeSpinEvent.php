<?php

namespace App\Classes\GameCore\Events\ActionEvents;

use App\Classes\GameCore\Events\BaseEvent;

class StartActionFreeSpinEvent extends BaseEvent
{
    /** @var string */
    public $name = 'startActionFreeSpin';
}
