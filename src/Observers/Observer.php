<?php

namespace App\Classes\GameCore\Observers;

use App\Classes\GameCore\Base\IObserver;
use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IDataPool;

abstract class Observer implements IObserver
{
    protected $eventName = '';

    public function update(IEvent $event): IDataPool
    {

    }
}
