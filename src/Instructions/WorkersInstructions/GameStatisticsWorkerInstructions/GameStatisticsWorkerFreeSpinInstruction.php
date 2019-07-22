<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class GameStatisticsWorkerFreeSpinInstruction implements IInstruction
{
    /**
     * Вычисление общего выигрыша
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinnings(
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
     * Вычисление общего выигрыша в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinningsOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winningsOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnFeatureGame(
                $dataPool->gameStatisticsData->winningsOnFeatureGame,
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
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
     * Вычисление общего кол-ва кручений в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getSpinCountInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->spinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountInFeatureGame(
                $dataPool->gameStatisticsData->spinCountInFeatureGame
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
     * Вычисление общего кол-ва выигрышных кручений в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinSpinCountOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->winSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnFeatureGame(
                $dataPool->gameStatisticsData->winSpinCountInFeatureGame,
                $dataPool->stateData->isWin
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
     * Вычисление общего кол-ва проигрышных кручений в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getLoseSpinCountOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->loseSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnFeatureGame(
                $dataPool->gameStatisticsData->loseSpinCountInFeatureGame,
                $dataPool->stateData->isWin
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
     * Общий процент выигрышных спинов в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentWinSpinsInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentWinSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInFeatureGame(
                $dataPool->gameStatisticsData->spinCountInFeatureGame,
                $dataPool->gameStatisticsData->winSpinCountInFeatureGame
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
     * Общий процент проигрышных спинов в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPercentLoseSpinsInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->percentLoseSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInFeatureGame(
                $dataPool->gameStatisticsData->spinCountInFeatureGame,
                $dataPool->gameStatisticsData->loseSpinCountInFeatureGame
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
        $dataPool->gameStatisticsData->winPercentOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnFeatureGame(
                $dataPool->gameStatisticsData->winningsOnFeatureGame,
                $dataPool->gameStatisticsData->loss,
                $dataPool->gameStatisticsData->winnings,
                $dataPool->gameStatisticsData->winningsOnMainGame
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
     * Статистика выигршных комбинаций в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function get(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticOfWinCombinationsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInFeatureGame(
                $dataPool->gameStatisticsData->statisticOfWinCombinationsInFeatureGame,
                $dataPool->logicData->winningLines
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
     * Статистика кол-ва выпадений символов в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getStatisticsOfDroppedSymbolsInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->statisticsOfDroppedSymbolsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInFeatureGame(
                $dataPool->gameStatisticsData->statisticsOfDroppedSymbolsInFeatureGame,
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
     * Статистика кол-ва бонусных символов выпадающих за ход в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getDroppedBonusSymbolsInOneSpinInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpinInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->gameStatisticsData->droppedBonusSymbolsInOneSpinInFeatureGame,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Кол-во алмазов выпавшее за текущую featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getDroppendDiamandsInCurrentFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->gameStatisticsData->droppendDiamandsInCurrentFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppendDiamandsInCurrentFeatureGame(
                $dataPool->gameStatisticsData->droppendDiamandsInCurrentFeatureGame,
                $dataPool->logicData->table
            );

        return $dataPool;
    }

    /**
     * Обновление статистики по максимальному и минимальному кол-ву алмазов
     * выпавшем в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function updateParamsIfEndFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->stateData->isEndFeatureGame) {
            // минимально кол-во алмазов выпавшее за период featureGame
            $dataPool->gameStatisticsData->minDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMinCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->gameStatisticsData->minDroppendDiamandsInFeatureGame,
                    $dataPool->gameStatisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // максимальное кол-во алмазов выпавшее за период featureGame
            $dataPool->gameStatisticsData->maxDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMaxCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->gameStatisticsData->maxDroppendDiamandsInFeatureGame,
                    $dataPool->gameStatisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // обнуление кол-ва алмазов в текущей featureGame
            $dataPool->gameStatisticsData->droppendDiamandsInCurrentFeatureGame = 0;
        }

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
