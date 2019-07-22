<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Events\ActionEvents\StartActionFreeSpinEvent;
use Avior\GameCore\Events\ActionEvents\EndActionFreeSpinEvent;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionFreeSpin extends Action
{
    /**
     * Выполение действия кручения слота в featureGame игре
     *
     * @param  array             $requestArray     [description]
     * @param  IWorkersPool      $workersPool      [description]
     * @param  IDataPool         $dataPool         [description]
     * @param  IToolsPool        $toolsPool        [description]
     * @param  IInstructionsPool $instructionsPool [description]
     * @param  IRequestDataSets  $requestDataSets  [description]
     *
     * @return string                              json
     */
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

        // оповещение об начале выполнения действия
        $dataPool = $this->notify(new StartActionFreeSpinEvent($dataPool, $toolsPool));

        // загрузка баланса
        $dataPool = $workersPool->balanceWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->balanceWorkerInstructions->loadData
        );
        // востановление состояния
        $dataPool = $workersPool->recoveryWorker->recoveryData($dataPool, $toolsPool);
        // загрузка статистики пользователя по данной игре
        $dataPool = $workersPool->statisticsWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->userStatisticsWorkerInstructions->loadData
        );
        // загрузка статистики по данной игре
        $dataPool = $workersPool->statisticsWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->gameStatisticsWorkerInstructions->loadData
        );

        // проверка возможности выполнения запроса
        $workersPool->verifierWorker->verificationFreeSpinRequest($dataPool, $toolsPool);
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
        $dataPool = $workersPool->statisticsWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->gameStatisticsWorkerInstructions->freeSpin
        );

        // оповещение об окончании выполнения действия
        $dataPool = $this->notify(new EndActionFreeSpinEvent($dataPool, $toolsPool));

        // Сохранение данных для последующего востановления
        $workersPool->recoveryWorker->saveRecoveryData($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
