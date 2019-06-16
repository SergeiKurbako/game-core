<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IDataPool;

interface IObserver
{
    public function update(IEvent $event): IDataPool;
}
