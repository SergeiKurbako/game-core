<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IDataPool;

interface IObserver
{
    public function update(IEvent $event): IDataPool;
}
