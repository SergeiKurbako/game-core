<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Events\ActionEvents\StartActionSpinEvent;
use Avior\GameCore\Events\ActionEvents\EndActionSpinEvent;

/**
 * Класс выполняет действие запуска игры на сервере
 */
class ActionSpin extends Action
{
    /**
     * Выполение действия кручения слота в основной игре
     *
     * @param  array             $requestArray     [description]
     * @param  IWorkersPool      $workersPool      [description]
     * @param  IDataPool         $dataPool         [description]
     * @param  IToolsPool        $toolsPool        [description]
     * @param  IInstructionsPool $instructionsPool [description]
     * @param  IRequestDataSets  $requestDataSets  [description]
     *
     * @return [type]                              [description]
     */
    public function __invoke(
        array $requestArray,
        IWorkersPool $workersPool,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IInstructionsPool $instructionsPool,
        IRequestDataSets $requestDataSets
    ): string
    {
        // загрузка данных из запроса
        $dataPool = $workersPool->requestWorker->loadRequestData($requestArray, $dataPool, $toolsPool, $requestDataSets);
        // оповещение об начале выполнения действия
        $dataPool = $this->notify(new StartActionSpinEvent($dataPool, $toolsPool));
        // загрузка баланса
        $dataPool = $workersPool->balanceWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->balanceWorkerInstructions->loadData
        );
        // восстановление состояния
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
        $workersPool->verifierWorker->verificationSpinRequest($dataPool, $toolsPool);
        // вычисление результатов хода
        $dataPool = $workersPool->logicWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->logicWorkerInstructions->spin
        );
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
        // обновление пользовательской статистики
        $dataPool = $workersPool->statisticsWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->userStatisticsWorkerInstructions->spin
        );
        $dataPool = $workersPool->statisticsWorker->executeInstruction(
            $dataPool,
            $toolsPool,
            $instructionsPool->gameStatisticsWorkerInstructions->spin
        );

        // оповещение об окончании выполнения действия
        $dataPool = $this->notify(new EndActionSpinEvent($dataPool, $toolsPool));

        // Сохранение данных для последующего восстановления
        $workersPool->recoveryWorker->saveRecoveryData($dataPool, $toolsPool);

        // подготовка данных для фронта
        $response = $workersPool->responseWorker->makeResponse($dataPool, $toolsPool);

        return $response;
    }
}
