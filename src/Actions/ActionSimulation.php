<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Events\ActionEvents\StartActionSpinEvent;
use Avior\GameCore\Events\ActionEvents\EndActionSpinEvent;
use Avior\GameCore\Events\ActionEvents\StartActionFreeSpinEvent;
use Avior\GameCore\Events\ActionEvents\EndActionFreeSpinEvent;

/**
 * Класс выполняет действие симуляции игрового процесса и выдает общий результат
 */
class ActionSimulation extends Action
{
    public function __invoke(
        array $requestArray,
        IWorkersPool $workersPool,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IInstructionsPool $instructionsPool,
        IRequestDataSets $requestDataSets
    ): string {
        // загрузка данных из запроса
        $dataPool = $workersPool->requestWorker->loadRequestData($requestArray, $dataPool, $toolsPool, $requestDataSets);
        // загрузка сессии
        $dataPool = $workersPool->sessionWorker->loadSessionData($dataPool, $toolsPool);
        // загрузка баланса
        $dataPool = $workersPool->balanceWorker->loadBalanceData($dataPool, $toolsPool);
        // загрузка логики
        $dataPool = $workersPool->logicWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->logicWorkerInstructions->load_data
        );
        // загрузка состояния
        $dataPool = $workersPool->stateWorker->loadStateData($dataPool, $toolsPool);
        // загрузка статистики
        $dataPool = $workersPool->statisticsWorker->loadStatisticsData($dataPool, $toolsPool);

        $dataPool->balanceData->balance = 1000000000;
        $dataPool->systemData->isSimulation = true;

        // выполнение запросов в цикле
        for ($i = 0; $i < $requestArray['spin_count']; $i++) {
            if ($dataPool->stateData->screen === 'mainGame') {
                // оповещение об начале выполнения действия
                $dataPool = $this->notify(new StartActionSpinEvent($dataPool, $toolsPool));
                // вычисление результатов хода
                $dataPool = $workersPool->logicWorker->executeInstruction($dataPool, $toolsPool, $instructionsPool->logicWorkerInstructions->spin);
                // обновление данных связанных с деньгами
                $dataPool = $workersPool->balanceWorker->getResultOfSpin($dataPool, $toolsPool);
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->getResultOfSpin($dataPool, $toolsPool);
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool);
                // оповещение об окончании выполнения действия
                $dataPool = $this->notify(new EndActionSpinEvent($dataPool, $toolsPool));
            } elseif ($dataPool->stateData->screen === 'featureGame') {
                $requestArray['spin_count'] += 1;
                // оповещение об начале выполнения действия
                $dataPool = $this->notify(new StartActionFreeSpinEvent($dataPool, $toolsPool));
                // вычисление результатов хода
                $dataPool = $workersPool->logicWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->logicWorkerInstructions->free_spin
                );
                // обновление данных связанных с деньгами
                $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool);
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool);
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool);
                // оповещение об окончании выполнения действия
                $dataPool = $this->notify(new EndActionFreeSpinEvent($dataPool, $toolsPool));
            }
        }

        $dataPool->systemData->executionTime = microtime(true) - $dataPool->systemData->startExecutionTime;

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
