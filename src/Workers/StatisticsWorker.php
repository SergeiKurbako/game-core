<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Workers\Worker;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2Statistic;

/**
 * Класс работаюзий со статистикой
 */
class StatisticsWorker extends Worker
{
    public function loadStatisticsData(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // загруказ данных статистики
        if ($dataPool->systemData->isSimulation === false) {
            $dataPool->statisticsData = $toolsPool
            ->dataTools
            ->statisticsDataTool
            ->getUserStatistics(
                $dataPool->statisticsData,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->gameId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }

    /**
     * Заненесение результов кручения в основной игре в статистику
     * Выполнение задач предполагает screen = main
     *
     * @param IDataPool $dataPool
     *
     * @return void
     */
    public function getResultOfSpin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // вычисление общего выигрыша
        $dataPool->statisticsData->winnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->winnings,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего выигрыша в основной игре
        $dataPool->statisticsData->winningsOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnMainGame(
                $dataPool->statisticsData->winningsOnMainGame,
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        // вычисление общего проигрыша
        $dataPool->statisticsData->loss = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoss(
                $dataPool->statisticsData->loss,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        // вычисление общего проигрыша в основной игре
        $dataPool->statisticsData->lossOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLossOnMainGame(
                $dataPool->statisticsData->lossOnMainGame,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        // вычисление общего кол-ва кручений
        $dataPool->statisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->statisticsData->spinCount
            );

        // вычисление общего кол-ва кручений в основной игре
        $dataPool->statisticsData->spinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountOnMainGame(
                $dataPool->statisticsData->spinCountInMainGame,
                $dataPool->stateData->screen
            );

        // сохранение данных статистики
        if ($dataPool->systemData->isSimulation === false) {
            $statistics = $toolsPool
                ->dataTools
                ->statisticsDataTool
                ->saveStatistics(
                    $dataPool->statisticsData,
                    $dataPool->sessionData->userId,
                    $dataPool->sessionData->gameId,
                    $dataPool->sessionData->mode
                );
        }

        return $dataPool;
    }

    /**
     * Получение результатов хода в featureGame
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getResultOfFreeSpin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // вычисление общего выигрыша
        $dataPool->statisticsData->winnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->winnings,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего выигрыша за все featureGame
        $dataPool->statisticsData->winningsOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->winningsOnFeatureGame,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего кол-ва кручений
        $dataPool->statisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->statisticsData->spinCount
            );

        // вычисление общего кол-ва кручений в featureGame
        $dataPool->statisticsData->spinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountOnFeatureGame(
                $dataPool->statisticsData->spinCountInFeatureGame,
                $dataPool->stateData->screen
            );

        // сохранение данных статистики
        if ($dataPool->systemData->isSimulation === false) {
            $statistics = $toolsPool
                ->dataTools
                ->statisticsDataTool
                ->saveStatistics(
                    $dataPool->statisticsData,
                    $dataPool->sessionData->userId,
                    $dataPool->sessionData->gameId,
                    $dataPool->sessionData->mode
                );
        }

        return $dataPool;
    }
}
