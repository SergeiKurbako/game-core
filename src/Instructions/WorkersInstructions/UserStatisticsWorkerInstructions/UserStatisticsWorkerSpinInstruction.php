<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class UserStatisticsWorkerSpinInstruction implements IInstruction
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
        $dataPool->userStatisticsData->totalBet += $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame;

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
        $dataPool->userStatisticsData->winnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->userStatisticsData->winnings,
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
        $dataPool->userStatisticsData->winningsOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnMainGame(
                $dataPool->userStatisticsData->winningsOnMainGame,
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
        $dataPool->userStatisticsData->loss = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoss(
                $dataPool->userStatisticsData->loss,
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
        $dataPool->userStatisticsData->lossOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLossOnMainGame(
                $dataPool->userStatisticsData->lossOnMainGame,
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
        $dataPool->userStatisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->userStatisticsData->spinCount
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
        $dataPool->userStatisticsData->spinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountInMainGame(
                $dataPool->userStatisticsData->spinCountInMainGame,
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
        $dataPool->userStatisticsData->winSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCount(
                $dataPool->userStatisticsData->winSpinCount,
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
        $dataPool->userStatisticsData->winSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnMainGame(
                $dataPool->userStatisticsData->winSpinCountInMainGame,
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
        $dataPool->userStatisticsData->loseSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoseSpinCount(
                $dataPool->userStatisticsData->loseSpinCount,
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
        $dataPool->userStatisticsData->loseSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnMainGame(
                $dataPool->userStatisticsData->loseSpinCountInMainGame,
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
        $dataPool->userStatisticsData->featureGamesDropped = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateFeatureGamesDropped(
                $dataPool->userStatisticsData->featureGamesDropped,
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
        $dataPool->userStatisticsData->percentWinSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpins(
                $dataPool->userStatisticsData->spinCount,
                $dataPool->userStatisticsData->winSpinCount
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
        $dataPool->userStatisticsData->percentWinSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInMainGame(
                $dataPool->userStatisticsData->spinCountInMainGame,
                $dataPool->userStatisticsData->winSpinCountInMainGame
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
        $dataPool->userStatisticsData->percentLoseSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpins(
                $dataPool->userStatisticsData->spinCount,
                $dataPool->userStatisticsData->loseSpinCount
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
        $dataPool->userStatisticsData->percentLoseSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInMainGame(
                $dataPool->userStatisticsData->spinCountInMainGame,
                $dataPool->userStatisticsData->loseSpinCountInMainGame
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
        $dataPool->userStatisticsData->winPercent = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercent(
                $dataPool->userStatisticsData->winnings,
                $dataPool->userStatisticsData->loss
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
        $dataPool->userStatisticsData->winPercentOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnMainGame(
                $dataPool->userStatisticsData->winningsOnMainGame,
                $dataPool->userStatisticsData->loss
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
        $dataPool->userStatisticsData->statisticOfWinCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinations(
                $dataPool->userStatisticsData->statisticOfWinCombinations,
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
        $dataPool->userStatisticsData->statisticOfWinCombinationsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInMainGame(
                $dataPool->userStatisticsData->statisticOfWinCombinationsInMainGame,
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
        $dataPool->userStatisticsData->statisticsOfDroppedSymbols = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbols(
                $dataPool->userStatisticsData->statisticsOfDroppedSymbols,
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
        $dataPool->userStatisticsData->statisticsOfDroppedSymbolsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInMainGame(
                $dataPool->userStatisticsData->statisticsOfDroppedSymbolsInMainGame,
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
        $dataPool->userStatisticsData->statisticOfWinBonusCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinBonusCombinations(
                $dataPool->userStatisticsData->statisticOfWinBonusCombinations,
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
        $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpin = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpin,
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
        $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpinInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpinInMainGame(
                $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpinInMainGame,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Процент выиграных денег относительно потраченных в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinPercentOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->userStatisticsData->winPercentOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnFeatureGame(
                $dataPool->userStatisticsData->winningsOnFeatureGame,
                $dataPool->userStatisticsData->loss,
                $dataPool->userStatisticsData->winnings,
                $dataPool->userStatisticsData->winningsOnMainGame
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
                ->saveUserStatistics(
                    $dataPool->userStatisticsData,
                    $dataPool->sessionData->userId,
                    $dataPool->sessionData->gameId,
                    $dataPool->sessionData->mode
                );
        }

        return $dataPool;
    }
}
