<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Workers\Worker;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Models\V2Statistic;

/**
 * Класс работаюзий со статистикой
 */
class StatisticsWorker extends Worker
{
    public function loadStatisticsData(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // загруказ данных статистики
        $dataPool->statisticsData = $toolsPool
            ->dataTools
            ->statisticsDataTool
            ->getUserStatistics(
                $dataPool->statisticsData,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->gameId,
                $dataPool->sessionData->mode
            );

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
    public function getResultOfSpin(IDataPool $dataPool, IToolsPool $toolsPool, bool $simulation = false): IDataPool
    {
        // вычисление общего выигрыша
        $dataPool->statisticsData->totalWinnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->totalWinnings,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего выигрыша в основной игре
        $dataPool->statisticsData->totalWinningsOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnMainGame(
                $dataPool->statisticsData->totalWinningsOnMainGame,
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        // вычисление общего проигрыша
        $dataPool->statisticsData->totalLoss = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoss(
                $dataPool->statisticsData->totalLoss,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        // вычисление общего проигрыша в основной игре
        $dataPool->statisticsData->totalLossOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLossOnMainGame(
                $dataPool->statisticsData->totalLossOnMainGame,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        // вычисление общего кол-ва кручений
        $dataPool->statisticsData->totalSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->statisticsData->totalSpinCount
            );

        // вычисление общего кол-ва кручений в основной игре
        $dataPool->statisticsData->totalSpinCountOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountOnMainGame(
                $dataPool->statisticsData->totalSpinCountOnMainGame,
                $dataPool->stateData->screen
            );

        // вычисление общего кол-ва джекпотов
        $dataPool->statisticsData->totalJackpots = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateJackpots(
                $dataPool->statisticsData->totalJackpots
            );

        // сохранение данных статистики
        if ($simulation === false) {
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
    public function getResultOfFreeSpin(IDataPool $dataPool, IToolsPool $toolsPool, bool $simulation = false): IDataPool
    {
        // вычисление общего выигрыша
        $dataPool->statisticsData->totalWinnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->totalWinnings,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего выигрыша за все featureGame
        $dataPool->statisticsData->totalWinningsOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->statisticsData->totalWinningsOnFeatureGame,
                $dataPool->balanceData->totalPayoff
            );

        // вычисление общего кол-ва кручений
        $dataPool->statisticsData->totalSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->statisticsData->totalSpinCount
            );

        // вычисление общего кол-ва кручений в featureGame
        $dataPool->statisticsData->totalSpinCountOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountOnFeatureGame(
                $dataPool->statisticsData->totalSpinCountOnFeatureGame,
                $dataPool->stateData->screen
            );

        // сохранение данных статистики
        if ($simulation === false) {
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
