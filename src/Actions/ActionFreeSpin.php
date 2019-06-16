<?php

namespace App\Classes\GameCore\Actions;

use App\Classes\GameCore\Base\IAction;
use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Events\ActionEvents\StartActionFreeSpinEvent;
use App\Classes\GameCore\Events\ActionEvents\EndActionFreeSpinEvent;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionFreeSpin extends Action
{
    /**
     * Выполение действия кручения слота в featureGame игре
     *
     * @param  array            $requestArray    [description]
     * @param  IWorkersPool     $workersPool     [description]
     * @param  IDataPool        $dataPool        [description]
     * @param  IToolsPool       $toolsPool       [description]
     * @param  IRequestDataSets $requestDataSets [description]
     * @param  array            $table           массив с значениями ячеек для проведения теста
     *
     * @return string                            json
     */
    public function __invoke(
        array $requestArray,
        IWorkersPool $workersPool,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IRequestDataSets $requestDataSets,
        array $table = []
    ): string
    {
        // загрузка данных из запроса
        $dataPool = $workersPool->requestWorker->loadRequestData($requestArray, $dataPool, $toolsPool, $requestDataSets);

        // оповещение об начале выполнения действия
        $dataPool = $this->notify(new StartActionFreeSpinEvent($dataPool, $toolsPool));

        // загрузка баланса
        $dataPool = $workersPool->balanceWorker->loadBalanceData($dataPool, $toolsPool);

        // востановление состояния
        $dataPool = $workersPool->recoveryWorker->recoveryData($dataPool, $toolsPool);

        // проверка возможности выполнения запроса
        $workersPool->verifierWorker->verificationFreeSpinRequest($dataPool, $toolsPool);
        // вычисление результатов хода
        $dataPool = $workersPool->logicWorker->getResultOfFreeSpin($dataPool, $toolsPool, $table);
        // обновление данных связанных с деньгами
        $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool);
        // получение итогового стостояния
        $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool);
        // обновление статистики
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool);

        // оповещение об окончании выполнения действия
        $dataPool = $this->notify(new EndActionFreeSpinEvent($dataPool, $toolsPool));

        // Сохранение данных для последующего востановления
        $workersPool->recoveryWorker->saveRecoveryData($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
