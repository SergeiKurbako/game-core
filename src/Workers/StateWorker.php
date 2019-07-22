<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Workers\Worker;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Events\GameEvents\StartFeatureGameEvent;
use Avior\GameCore\Events\GameEvents\EndFeatureGameEvent;

class StateWorker extends Worker
{
    protected function sendNotifies(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // оповещение о выпадении featureGame
        if ($dataPool->stateData->isDropFeatureGame) {
            $dataPool = $this->notify(new StartFeatureGameEvent($dataPool, $toolsPool));
        }

        // оповещение об окончании featureGame
        if ($dataPool->stateData->isEndFeatureGame) {
            $dataPool = $this->notify(new EndFeatureGameEvent($dataPool, $toolsPool));
        }

        return $dataPool;
    }
}
