<?php

namespace Avior\GameCore\Observers\GameProcessObservers;

use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Observers\Observer;

class StartFeatureGameObserver extends Observer
{
    public function update(IEvent $event): IDataPool
    {
        if ($event->name === 'startFeatureGame') {

        }

        return $event->dataPool;
    }
}
