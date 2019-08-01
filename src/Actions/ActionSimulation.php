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
        $dataPool = $workersPool->balanceWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->balanceWorkerInstructions->loadData
        );
        // загрузка логики
        $dataPool = $workersPool->logicWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->logicWorkerInstructions->loadData
        );
        // загрузка состояния
        $dataPool = $workersPool->stateWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->stateWorkerInstructions->loadData
        );
        // загрузка статистики
        // $dataPool = $workersPool->statisticsWorker->executeInstruction(
        //     $dataPool,
        //     $toolsPool,
        //     $instructionsPool->userStatisticsWorkerInstructions->loadData
        // );

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
                $dataPool = $workersPool->balanceWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->balanceWorkerInstructions->spin
                );
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->stateWorkerInstructions->spin
                );
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->userStatisticsWorkerInstructions->spin
                );
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
                    $instructionsPool->logicWorkerInstructions->freeSpin
                );
                // обновление данных связанных с деньгами
                $dataPool = $workersPool->balanceWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->balanceWorkerInstructions->freeSpin
                );
                // получение итогового стостояния
                $dataPool = $workersPool->stateWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->stateWorkerInstructions->freeSpin
                );
                // обновление статистики
                $dataPool = $workersPool->statisticsWorker->executeInstruction(
                    $dataPool,
                    $toolsPool,
                    $instructionsPool->userStatisticsWorkerInstructions->freeSpin
                );
                // оповещение об окончании выполнения действия
                $dataPool = $this->notify(new EndActionFreeSpinEvent($dataPool, $toolsPool));
            }
        }

        // dd(
        //     __METHOD__,
        //     $dataPool->userStatisticsData->loss,
        //     $dataPool->userStatisticsData->winnings,
        //     $dataPool->userStatisticsData->winningsOnMainGame,
        //     $dataPool->userStatisticsData->winningsOnFeatureGame,
        //     $dataPool->userStatisticsData->winPercent,
        //     $dataPool->userStatisticsData->winningsOnMainGame,
        //     $dataPool->userStatisticsData->winPercentOnFeatureGame
        // );

        $dataPool->systemData->executionTime = microtime(true) - $dataPool->systemData->startExecutionTime;

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
