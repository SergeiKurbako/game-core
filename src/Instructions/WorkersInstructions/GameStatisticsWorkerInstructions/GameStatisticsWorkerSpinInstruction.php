<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class GameStatisticsWorkerSpinInstruction implements IInstruction
{
    /**
     * Вычисление суммы всех сделанных ставок
     *
     * @param  IDataPool  $dataPool
     * @param  IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getTotalBet(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->totalBet += $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame;
        
        return $dataPool;
    }

    /**
     * Вычисление общего выигрыша
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinnings(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->gameStatisticsData->winnings,
                $dataPool->balanceData->totalPayoff
            );

        return $dataPool;
    }

    /**
     * Вычисление общего выигрыша в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinningsOnMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winningsOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnMainGame(
                $dataPool->gameStatisticsData->winningsOnMainGame,
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        return $dataPool;
    }


    /**
     * Вычисление общего проигрыша
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalLoss(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->loss = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoss(
                $dataPool->gameStatisticsData->loss,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        return $dataPool;
    }

    /**
     * Вычисление общего проигрыша в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalLossOnMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->lossOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLossOnMainGame(
                $dataPool->gameStatisticsData->lossOnMainGame,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame
            );

        return $dataPool;
    }


    /**
     * Вычисление общего кол-ва кручений
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalSpinCount(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->gameStatisticsData->spinCount
            );

        return $dataPool;
    }

    /**
     * Вычисление общего кол-ва кручений в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getSpinCountInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->spinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountInMainGame(
                $dataPool->gameStatisticsData->spinCountInMainGame,
                $dataPool->stateData->screen,
                $dataPool->stateData->isDropFeatureGame
            );

        return $dataPool;
    }

    /**
     * Вычисление общего кол-ва выигрышных кручений
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinSpinCount(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCount(
                $dataPool->gameStatisticsData->winSpinCount,
                $dataPool->stateData->isWin
            );

        return $dataPool;
    }

    /**
     * Вычисление общего кол-ва выигрышных кручений в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinSpinCountOnMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnMainGame(
                $dataPool->gameStatisticsData->winSpinCountInMainGame,
                $dataPool->stateData->isWinOnMain,
                $dataPool->stateData->screen,
                $dataPool->stateData->isDropFeatureGame
            );

        return $dataPool;
    }

    /**
     * Вычисление общего кол-ва проигрышных кручений
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalLoseSpinCount(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->loseSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoseSpinCount(
                $dataPool->gameStatisticsData->loseSpinCount,
                $dataPool->stateData->isWin
            );

        return $dataPool;
    }

    /**
     * Вычисление общего кол-ва проигрышных кручений в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getLoseSpinCountOnMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->loseSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnMainGame(
                $dataPool->gameStatisticsData->loseSpinCountInMainGame,
                $dataPool->stateData->isWinOnMain,
                $dataPool->stateData->screen
            );

        return $dataPool;
    }

    /**
     * Вычисление кол-ва выпавших featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getFeatureGamesDropped(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->featureGamesDropped = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateFeatureGamesDropped(
                $dataPool->gameStatisticsData->featureGamesDropped,
                $dataPool->stateData->isDropFeatureGame
            );

        return $dataPool;
    }

    /**
     * Общий процент выигрышных спинов
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentWinSpins(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentWinSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpins(
                $dataPool->gameStatisticsData->spinCount,
                $dataPool->gameStatisticsData->winSpinCount
            );

        return $dataPool;
    }

    /**
     * Общий процент выигрышных спинов в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentWinSpinsInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentWinSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInMainGame(
                $dataPool->gameStatisticsData->spinCountInMainGame,
                $dataPool->gameStatisticsData->winSpinCountInMainGame
            );

        return $dataPool;
    }


    /**
     * Общий процент проигрышных спинов
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentLoseSpins(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentLoseSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpins(
                $dataPool->gameStatisticsData->spinCount,
                $dataPool->gameStatisticsData->loseSpinCount
            );

        return $dataPool;
    }

    /**
     * Общий процент проигрышных спинов в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentLoseSpinsInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentLoseSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInMainGame(
                $dataPool->gameStatisticsData->spinCountInMainGame,
                $dataPool->gameStatisticsData->loseSpinCountInMainGame
            );

        return $dataPool;
    }

    /**
     * Процент выиграных денег относительно потраченных
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinPercent(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winPercent = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercent(
                $dataPool->gameStatisticsData->winnings,
                $dataPool->gameStatisticsData->loss
            );

        return $dataPool;
    }


    /**
     * Процент выиграных денег относительно потраченных в mainGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinPercentOnMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winPercentOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnMainGame(
                $dataPool->gameStatisticsData->winningsOnMainGame,
                $dataPool->gameStatisticsData->loss
            );

        return $dataPool;
    }

    /**
     * Статистика выигршных комбинаций
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticOfWinCombinations(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticOfWinCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinations(
                $dataPool->gameStatisticsData->statisticOfWinCombinations,
                $dataPool->logicData->winningLines
            );

        return $dataPool;
    }

    /**
     * Статистика выигршных комбинаций в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticOfWinCombinationsInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticOfWinCombinationsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInMainGame(
                $dataPool->gameStatisticsData->statisticOfWinCombinationsInMainGame,
                $dataPool->logicData->winningLines,
                $dataPool->stateData->screen
            );

        return $dataPool;
    }

    /**
     * Статистика кол-ва выпадений символов
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticsOfDroppedSymbols(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticsOfDroppedSymbols = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbols(
                $dataPool->gameStatisticsData->statisticsOfDroppedSymbols,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Статистика кол-ва выпадений символов в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticsOfDroppedSymbolsInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticsOfDroppedSymbolsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInMainGame(
                $dataPool->gameStatisticsData->statisticsOfDroppedSymbolsInMainGame,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Статистика выигршных комбинаций из-за которых началась featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticOfWinBonusCombinations(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticOfWinBonusCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinBonusCombinations(
                $dataPool->gameStatisticsData->statisticOfWinBonusCombinations,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Статистика кол-ва бонусных символов выпадающих за ход
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getDroppedBonusSymbolsInOneSpin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpin = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpin,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Статистика кол-ва бонусных символов выпадающих за ход в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getDroppedBonusSymbolsInOneSpinInMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpinInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpinInMainGame(
                $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpinInMainGame,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Сохранение данных статистики
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function saveStatistics(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->systemData->isSimulation === false) {
            $statistics = $toolsPool
                ->dataTools
                ->statisticsDataTool
                ->saveGameStatistics(
                    $dataPool->gameStatisticsData,
                    $dataPool->sessionData->gameId,
                    $dataPool->sessionData->mode
                );
        }

        return $dataPool;
    }
}
