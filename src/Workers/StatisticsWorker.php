<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Workers\Worker;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2Statistic;

/**
 * Класс работаюзий со статистикой пользователя
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
            ->calculateSpinCountInMainGame(
                $dataPool->statisticsData->spinCountInMainGame,
                $dataPool->stateData->screen,
                $dataPool->stateData->isDropFeatureGame
            );

        // вычисление общего кол-ва выигрышных кручений
        $dataPool->statisticsData->winSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCount(
                $dataPool->statisticsData->winSpinCount,
                $dataPool->stateData->isWin
            );

        // вычисление общего кол-ва выигрышных кручений в основной игре
        $dataPool->statisticsData->winSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnMainGame(
                $dataPool->statisticsData->winSpinCountInMainGame,
                $dataPool->stateData->isWinOnMain,
                $dataPool->stateData->screen,
                $dataPool->stateData->isDropFeatureGame
            );

        // вычисление общего кол-ва проигрышных кручений
        $dataPool->statisticsData->loseSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoseSpinCount(
                $dataPool->statisticsData->loseSpinCount,
                $dataPool->stateData->isWin
            );

        // вычисление общего кол-ва проигрышных кручений в основной игре
        $dataPool->statisticsData->loseSpinCountInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnMainGame(
                $dataPool->statisticsData->loseSpinCountInMainGame,
                $dataPool->stateData->isWinOnMain,
                $dataPool->stateData->screen
            );

        // вычисление кол-ва выпавших featureGame
        $dataPool->statisticsData->featureGamesDropped = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateFeatureGamesDropped(
                $dataPool->statisticsData->featureGamesDropped,
                $dataPool->stateData->isDropFeatureGame
            );

        // общий процент выигрышных спинов
        $dataPool->statisticsData->percentWinSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpins(
                $dataPool->statisticsData->spinCount,
                $dataPool->statisticsData->winSpinCount
            );

        // общий процент выигрышных спинов в основной игре
        $dataPool->statisticsData->percentWinSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInMainGame(
                $dataPool->statisticsData->spinCountInMainGame,
                $dataPool->statisticsData->winSpinCountInMainGame
            );

        // общий процент проигрышных спинов
        $dataPool->statisticsData->percentLoseSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpins(
                $dataPool->statisticsData->spinCount,
                $dataPool->statisticsData->loseSpinCount
            );

        // общий процент проигрышных спинов в основной игре
        $dataPool->statisticsData->percentLoseSpinsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInMainGame(
                $dataPool->statisticsData->spinCountInMainGame,
                $dataPool->statisticsData->loseSpinCountInMainGame
            );

        // процент выиграных денег относительно потраченных
        $dataPool->statisticsData->winPercent = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercent(
                $dataPool->statisticsData->winnings,
                $dataPool->statisticsData->loss
            );

        // процент выиграных денег относительно потраченных в mainGame
        $dataPool->statisticsData->winPercentOnMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnMainGame(
                $dataPool->statisticsData->winningsOnMainGame,
                $dataPool->statisticsData->loss
            );

        // статистика выигршных комбинаций
        $dataPool->statisticsData->statisticOfWinCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinations(
                $dataPool->statisticsData->statisticOfWinCombinations,
                $dataPool->logicData->winningLines
            );

        // статистика выигршных комбинаций в основной игре
        $dataPool->statisticsData->statisticOfWinCombinationsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInMainGame(
                $dataPool->statisticsData->statisticOfWinCombinationsInMainGame,
                $dataPool->logicData->winningLines,
                $dataPool->stateData->screen
            );

        // статистика кол-ва выпадений символов
        $dataPool->statisticsData->statisticsOfDroppedSymbols = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbols(
                $dataPool->statisticsData->statisticsOfDroppedSymbols,
                $dataPool->logicData->table
            );

        // статистика кол-ва выпадений символов в основной игре
        $dataPool->statisticsData->statisticsOfDroppedSymbolsInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInMainGame(
                $dataPool->statisticsData->statisticsOfDroppedSymbolsInMainGame,
                $dataPool->logicData->table
            );

        // статистика выигршных комбинаций из-за которых началась featureGame
        $dataPool->statisticsData->statisticOfWinBonusCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinBonusCombinations(
                $dataPool->statisticsData->statisticOfWinBonusCombinations,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->table
            );



        // статистика кол-ва бонусных символов выпадающих за ход
        $dataPool->statisticsData->droppedBonusSymbolsInOneSpin = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->statisticsData->droppedBonusSymbolsInOneSpin,
                $dataPool->logicData->table
            );

        // статистика кол-ва бонусных символов выпадающих за ход в основной игре
        $dataPool->statisticsData->droppedBonusSymbolsInOneSpinInMainGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->statisticsData->droppedBonusSymbolsInOneSpinInMainGame,
                $dataPool->logicData->table
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

        // вычисление общего выигрыша в featureGame
        $dataPool->statisticsData->winningsOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinningsOnFeatureGame(
                $dataPool->statisticsData->winningsOnFeatureGame,
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        // вычисление общего кол-ва кручений
        $dataPool->statisticsData->spinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalSpinCount(
                $dataPool->statisticsData->spinCount
            );

        // вычисление общего кол-ва кручений в featureGame
        $dataPool->statisticsData->spinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateSpinCountInFeatureGame(
                $dataPool->statisticsData->spinCountInFeatureGame
            );

        // вычисление общего кол-ва выигрышных кручений
        $dataPool->statisticsData->winSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCount(
                $dataPool->statisticsData->winSpinCount,
                $dataPool->stateData->isWin
            );

        // вычисление общего кол-ва выигрышных кручений в featureGame
        $dataPool->statisticsData->winSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalWinSpinCountOnFeatureGame(
                $dataPool->statisticsData->winSpinCountInFeatureGame,
                $dataPool->stateData->isWin
            );

        // вычисление общего кол-ва проигрышных кручений
        $dataPool->statisticsData->loseSpinCount = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateTotalLoseSpinCount(
                $dataPool->statisticsData->loseSpinCount,
                $dataPool->stateData->isWin
            );

        // вычисление общего кол-ва проигрышных кручений в featureGame
        $dataPool->statisticsData->loseSpinCountInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateLoseSpinCountOnFeatureGame(
                $dataPool->statisticsData->loseSpinCountInFeatureGame,
                $dataPool->stateData->isWin
            );

        // общий процент выигрышных спинов
        $dataPool->statisticsData->percentWinSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpins(
                $dataPool->statisticsData->spinCount,
                $dataPool->statisticsData->winSpinCount
            );

        // общий процент выигрышных спинов в featureGame
        $dataPool->statisticsData->percentWinSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentWinSpinsInFeatureGame(
                $dataPool->statisticsData->spinCountInFeatureGame,
                $dataPool->statisticsData->winSpinCountInFeatureGame
            );

        // общий процент проигрышных спинов
        $dataPool->statisticsData->percentLoseSpins = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpins(
                $dataPool->statisticsData->spinCount,
                $dataPool->statisticsData->loseSpinCount
            );

        // общий процент проигрышных спинов в featureGame
        $dataPool->statisticsData->percentLoseSpinsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculatePercentLoseSpinsInFeatureGame(
                $dataPool->statisticsData->spinCountInFeatureGame,
                $dataPool->statisticsData->loseSpinCountInFeatureGame
            );

        // процент выиграных денег относительно потраченных
        $dataPool->statisticsData->winPercent = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercent(
                $dataPool->statisticsData->winnings,
                $dataPool->statisticsData->loss
            );

        // процент выиграных денег относительно потраченных в featureGame
        $dataPool->statisticsData->winPercentOnFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateWinPercentOnFeatureGame(
                $dataPool->statisticsData->winningsOnFeatureGame,
                $dataPool->statisticsData->loss,
                $dataPool->statisticsData->winnings,
                $dataPool->statisticsData->winningsOnMainGame
            );

        // статистика выигршных комбинаций
        $dataPool->statisticsData->statisticOfWinCombinations = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinations(
                $dataPool->statisticsData->statisticOfWinCombinations,
                $dataPool->logicData->winningLines
            );

        // статистика выигршных комбинаций в featureGame
        $dataPool->statisticsData->statisticOfWinCombinationsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticOfWinCombinationsInFeatureGame(
                $dataPool->statisticsData->statisticOfWinCombinationsInFeatureGame,
                $dataPool->logicData->winningLines
            );

        // статистика кол-ва выпадений символов
        $dataPool->statisticsData->statisticsOfDroppedSymbols = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbols(
                $dataPool->statisticsData->statisticsOfDroppedSymbols,
                $dataPool->logicData->table
            );

        // статистика кол-ва выпадений символов в featureGame
        $dataPool->statisticsData->statisticsOfDroppedSymbolsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateStatisticsOfDroppedSymbolsInFeatureGame(
                $dataPool->statisticsData->statisticsOfDroppedSymbolsInFeatureGame,
                $dataPool->logicData->table
            );

        // статистика кол-ва бонусных символов выпадающих за ход
        $dataPool->statisticsData->droppedBonusSymbolsInOneSpin = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->statisticsData->droppedBonusSymbolsInOneSpin,
                $dataPool->logicData->table
            );

        // статистика кол-ва бонусных символов выпадающих за ход в featureGame
        $dataPool->statisticsData->droppedBonusSymbolsInOneSpinInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppedBonusSymbolsInOneSpin(
                $dataPool->statisticsData->droppedBonusSymbolsInOneSpinInFeatureGame,
                $dataPool->logicData->table
            );

        // кол-во алмазов выпавшее за текущую featureGame
        $dataPool->statisticsData->droppendDiamandsInCurrentFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
            ->calculateDroppendDiamandsInCurrentFeatureGame(
                $dataPool->statisticsData->droppendDiamandsInCurrentFeatureGame,
                $dataPool->logicData->table
            );


        if ($dataPool->stateData->isEndFeatureGame) {
            // минимально кол-во алмазов выпавшее за период featureGame
            $dataPool->statisticsData->minDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMinCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->statisticsData->minDroppendDiamandsInFeatureGame,
                    $dataPool->statisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // максимальное кол-во алмазов выпавшее за период featureGame
            $dataPool->statisticsData->maxDroppendDiamandsInFeatureGame = $toolsPool->statisticsTools->statisticsCalculatorTool
                ->calculateMaxCountDroppenDiamandsInFreeSpinGame(
                    $dataPool->statisticsData->maxDroppendDiamandsInFeatureGame,
                    $dataPool->statisticsData->droppendDiamandsInCurrentFeatureGame
                );

            // обнуление кол-ва алмазов в текущей featureGame
            $dataPool->statisticsData->droppendDiamandsInCurrentFeatureGame = 0;
        }

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
