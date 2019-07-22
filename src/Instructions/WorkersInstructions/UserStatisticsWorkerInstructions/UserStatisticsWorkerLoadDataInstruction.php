<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class UserStatisticsWorkerLoadDataInstruction implements IInstruction
{
    /**
     * загрузка данных статистики
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadStatisticsData(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // загруказ данных статистики
        if ($dataPool->systemData->isSimulation === false) {
            $dataPool->userStatisticsData = $toolsPool
            ->dataTools
            ->statisticsDataTool
            ->getUserStatistics(
                $dataPool->userStatisticsData,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->gameId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }
}
