<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class GameStatisticsWorkerLoadDataInstruction implements IInstruction
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
            $dataPool->gameStatisticsData = $toolsPool
            ->dataTools
            ->statisticsDataTool
            ->getGameStatistics(
                $dataPool->gameStatisticsData,
                $dataPool->sessionData->gameId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }
}
