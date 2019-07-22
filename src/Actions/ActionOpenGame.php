<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Tools\RecoveryDataTool;
use Avior\GameCore\Events\ActionEvents\StartActionOpenGameEvent;
use Avior\GameCore\Events\ActionEvents\EndActionOpenGameEvent;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionOpenGame extends Action
{
    /**
     * Выполнение действия запуска игры
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
        $dataPool = $this->notify(new StartActionOpenGameEvent($dataPool, $toolsPool));

        // проверка возможности выполнения запроса
        $workersPool->verifierWorker->verificationStartGameRequest($dataPool, $toolsPool);
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
