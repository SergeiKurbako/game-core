<?php

namespace Avior\GameCore\Events;

use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

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
