<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Tools\RecoveryDataTool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Events\ActionEvents\StartActionCloseGameEvent;
use Avior\GameCore\Events\ActionEvents\EndActionCloseGameEvent;

/**
 * Класс выполняет действие закрытия игры на сервере
 */
class ActionCloseGame extends Action
{
    public function __invoke(
        array $requestArray,
        IWorkersPool $workersPool,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IRequestDataSets $requestDataSets
    ): string
    {
        // загрузка данных из запроса
        $dataPool = $workersPool->requestWorker->loadRequestData($requestArray, $dataPool, $toolsPool, $requestDataSets);

        // оповещение об начале выполнения действия
        $dataPool = $this->notify(new StartActionCloseGameEvent($dataPool, $toolsPool));

        // закрытие сессии
        $dataPool = $workersPool->sessionWorker->closeSession($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        // оповещение об окончании выполнения действия
        $dataPool = $this->notify(new EndActionCloseGameEvent($dataPool, $toolsPool));

        return $response;
    }
}
