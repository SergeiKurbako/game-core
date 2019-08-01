<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 */
class UserStatisticsWorkerFreeSpinInstruction implements IInstruction
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
        $dataPool->userStatisticsData->winnings = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinnings(
                $dataPool->userStatisticsData->winnings,
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
        $dataPool->userStatisticsData->winningsOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnFeatureGame(
                $dataPool->userStatisticsData->winningsOnFeatureGame,
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
        $dataPool->userStatisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->userStatisticsData->spinCount
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
        $dataPool->userStatisticsData->spinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountInFeatureGame(
                $dataPool->userStatisticsData->spinCountInFeatureGame
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
        $dataPool->userStatisticsData->winSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnFeatureGame(
                $dataPool->userStatisticsData->winSpinCountInFeatureGame,
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
        $dataPool->userStatisticsData->loseSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoseSpinCount(
                $dataPool->userStatisticsData->loseSpinCount,
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
        $dataPool->userStatisticsData->loseSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnFeatureGame(
                $dataPool->userStatisticsData->loseSpinCountInFeatureGame,
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
        $dataPool->userStatisticsData->percentWinSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpins(
                $dataPool->userStatisticsData->spinCount,
                $dataPool->userStatisticsData->winSpinCount
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
        $dataPool->userStatisticsData->percentWinSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInFeatureGame(
                $dataPool->userStatisticsData->spinCountInFeatureGame,
                $dataPool->userStatisticsData->winSpinCountInFeatureGame
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
        $dataPool->userStatisticsData->percentLoseSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInFeatureGame(
                $dataPool->userStatisticsData->spinCountInFeatureGame,
                $dataPool->userStatisticsData->loseSpinCountInFeatureGame
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
        $dataPool->userStatisticsData->statisticOfWinCombinationsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInFeatureGame(
                $dataPool->userStatisticsData->statisticOfWinCombinationsInFeatureGame,
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
        $dataPool->userStatisticsData->statisticsOfDroppedSymbols = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbols(
                $dataPool->userStatisticsData->statisticsOfDroppedSymbols,
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
        $dataPool->userStatisticsData->statisticsOfDroppedSymbolsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInFeatureGame(
                $dataPool->userStatisticsData->statisticsOfDroppedSymbolsInFeatureGame,
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
        $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpinInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->userStatisticsData->droppedBonusSymbolsInOneSpinInFeatureGame,
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
        $dataPool->userStatisticsData->droppendDiamandsInCurrentFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppendDiamandsInCurrentFeatureGame(
                $dataPool->userStatisticsData->droppendDiamandsInCurrentFeatureGame,
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
            $dataPool->userStatisticsData->minDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMinCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->userStatisticsData->minDroppendDiamandsInFeatureGame,
                    $dataPool->userStatisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // максимальное кол-во алмазов выпавшее за период featureGame
            $dataPool->userStatisticsData->maxDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMaxCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->userStatisticsData->maxDroppendDiamandsInFeatureGame,
                    $dataPool->userStatisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // обнуление кол-ва алмазов в текущей featureGame
            $dataPool->userStatisticsData->droppendDiamandsInCurrentFeatureGame = 0;
        }

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
