<?php

namespace App\Classes\GameCore\Observers\GameProcessObservers;

use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Observers\Observer;

class EndFeatureGameObserver extends Observer
{
    public function update(IEvent $event): IDataPool
    {
        if ($event->name === 'endFeatureGame') {

        }

        return $event->dataPool;
    }
}
