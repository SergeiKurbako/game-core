<?php

namespace Avior\GameCore\Observers\GameProcessObservers;

use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Observers\Observer;

class EndFeatureGameObserver extends Observer
{
    public function update(IEvent $event): IDataPool
    {
        if ($event->name === 'endFeatureGame') {

        }

        return $event->dataPool;
    }
}
