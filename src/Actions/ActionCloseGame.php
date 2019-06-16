<?php

namespace App\Classes\GameCore\Actions;

use App\Classes\GameCore\Base\IAction;
use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Tools\RecoveryDataTool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Events\ActionEvents\StartActionCloseGameEvent;
use App\Classes\GameCore\Events\ActionEvents\EndActionCloseGameEvent;

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
