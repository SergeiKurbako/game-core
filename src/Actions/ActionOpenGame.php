<?php

namespace App\Classes\GameCore\Actions;

use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Tools\RecoveryDataTool;
use App\Classes\GameCore\Events\ActionEvents\StartActionOpenGameEvent;
use App\Classes\GameCore\Events\ActionEvents\EndActionOpenGameEvent;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionOpenGame extends Action
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
        $dataPool = $this->notify(new StartActionOpenGameEvent($dataPool, $toolsPool));

        // проверка возможности выполнения запроса
        $workersPool->verifierWorker->verificationStartGameRequest($dataPool, $toolsPool);
        // загрузка сессии
        $dataPool = $workersPool->sessionWorker->loadSessionData($dataPool, $toolsPool);
        // загрузка баланса
        $dataPool = $workersPool->balanceWorker->loadBalanceData($dataPool, $toolsPool);
        // загрузка логики
        $dataPool = $workersPool->logicWorker->loadLogicData($dataPool, $toolsPool);
        // загрузка состояния
        $dataPool = $workersPool->stateWorker->loadStateData($dataPool, $toolsPool);
        // загрузка статистики
        $dataPool = $workersPool->statisticsWorker->loadStatisticsData($dataPool, $toolsPool);

        // востановление состояния
        $dataPool = $workersPool->recoveryWorker->recoveryData($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        // оповещение об окончании выполнения действия
        $dataPool = $this->notify(new EndActionOpenGameEvent($dataPool, $toolsPool));

        // Сохранение данных для последующего востановления
        $workersPool->recoveryWorker->saveRecoveryData($dataPool, $toolsPool);


        return $response;
    }
}
