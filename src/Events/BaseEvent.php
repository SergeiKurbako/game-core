<?php

namespace App\Classes\GameCore\Events;

use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;

abstract class BaseEvent implements IEvent
{
    /** @var string */
    public $name = 'noNameEvent';

    /** @var IDataPool */
    public $dataPool;

    /** @var IToolsPool */
    public $toolsPool;

    public function __construct(IDataPool $dataPool, IToolsPool $toolsPool)
    {
        $this->dataPool = $dataPool;
        $this->toolsPool = $toolsPool;
    }
}
