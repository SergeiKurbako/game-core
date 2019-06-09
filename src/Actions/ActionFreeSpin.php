<?php

namespace App\Classes\GameCore\Actions;

use App\Classes\GameCore\Base\IAction;
use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionFreeSpin implements IAction
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

        // востановление состояния
        $dataPool = $workersPool->recoveryWorker->recoveryData($dataPool, $toolsPool);

        // проверка возможности выполнения запроса
        $workersPool->verifierWorker->verificationFreeSpinRequest($dataPool, $toolsPool);
        // вычисление результатов хода
        $dataPool = $workersPool->logicWorker->getResultOfFreeSpin($dataPool, $toolsPool);
        // обновление данных связанных с деньгами
        $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool);
        // получение итогового стостояния
        $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool);
        // обновление статистики
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        // Сохранение данных для последующего востановления
        $workersPool->recoveryWorker->saveRecoveryData($dataPool, $toolsPool);

        return $response;
    }
}
