<?php

namespace Avior\GameCore\Observers;

use Avior\GameCore\Base\IObserver;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IDataPool;

abstract class Observer implements IObserver
{
    protected $eventName = '';

    public function update(IEvent $event): IDataPool
    {

    }
}
