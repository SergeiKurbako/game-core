<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionSimulation extends Action
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
        $dataPool = $workersPool->requestWorker->loadRequestData($requestArray, $dataPool, $toolsPool);
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

        $dataPool->balanceData->balance = 100000000000;

        $start = microtime(true);

        // выполнение запросов в цикле
        for ($i = 0; $i < $requestArray['count_of_moves']; $i++) {
            if ($dataPool->stateData->screen === 'mainGame') {
                // вычисление результатов хода
                $dataPool = $workersPool->logicWorker->getResultOfSpin($dataPool, $toolsPool);
                // обновление данных связанных с деньгами
                $dataPool = $workersPool->balanceWorker->getResultOfSpin($dataPool, $toolsPool, true);
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->getResultOfSpin($dataPool, $toolsPool);
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool, true);
            } elseif ($dataPool->stateData->screen === 'featureGame') {
                // вычисление результатов хода
                $dataPool = $workersPool->logicWorker->getResultOfFreeSpin($dataPool, $toolsPool);
                // обновление данных связанных с деньгами
                $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool);
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);
            }
        }

        $time = microtime(true) - $start;
        dd($time);


        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
