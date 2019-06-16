<?php

namespace App\Classes\GameCore\Observers\GameProcessObservers;

use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Observers\Observer;

class StartFeatureGameObserver extends Observer
{
    public function update(IEvent $event): IDataPool
    {
        if ($event->name === 'startFeatureGame') {
            
        }

        return $event->dataPool;
    }
}
