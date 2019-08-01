<?php

namespace Avior\GameCore\Tools\StatisticsTools;

use Avior\GameCore\Base\ITool;
use Avior\GameCore\Base\IDataPool;

/**
 * Ведение статистики
 *
 * В метода выполняющих подсчет статистики не делается проверка состояния.
 * Эта обязанность перекладывается на отдельного рабочего, который занимается
 * проверкой возможности выполнения десвия. Рабочий статистики определяет в зависимости
 * от действия какие методы для ведения статистики будут использованы
 */
class StatisticsCalculatorTool implements ITool
{
    /**
     * Вычисление нового значения общего выигрыша
     *
     * @param int $oldTotalWinnings
     * @param int $totalWinnings
     *
     * @return int
     */
    public function calculateTotalWinnings(int $oldValue, int $totalWinnings): int
    {
        $newValue = $oldValue + $totalWinnings;

        return $newValue;
    }

    /**
     * Вычисление нового значения общего выигрыша в основной игре
     *
     * @param int $oldValue
     * @param array $payoffsForLines
     * @param array $payoffsForBonus
     *
     * @return int
     */
    public function calculateTotalWinningsOnMainGame(
        int $oldValue,
        array $payoffsForLines,
        array $payoffsForBonus
    ): int
    {
        // получение общей ставки
        $totalWinningsOnLines = 0;
        foreach ($payoffsForLines as $key => $payoffsForLine) {
            $totalWinningsOnLines += $payoffsForLine['winValue'];
        }

        // получение выигрыша на бонусных символах
        $totalWinningsOnBonus = 0;
        foreach ($payoffsForBonus as $key => $value) {
            $totalWinningsOnBonus += $value['winning'];
        }

        $newValue = $oldValue + $totalWinningsOnLines + $totalWinningsOnBonus;

        return $newValue;
    }

    /**
     * Вычисление нового значения общего выигрыша в featureGame
     *
     * @param int $oldValue
     * @param array $payoffsForLines
     * @param array $payoffsForBonus
     *
     * @return int
     */
    public function calculateTotalWinningsOnFeatureGame(
        int $oldValue,
        array $payoffsForLines,
        array $payoffsForBonus
    ): int
    {
        // получение общей ставки
        $totalWinningsOnLines = 0;
        foreach ($payoffsForLines as $key => $payoffsForLine) {
            $totalWinningsOnLines += $payoffsForLine['winValue'];
        }

        // получение выигрыша на бонусных символах
        $totalWinningsOnBonus = 0;
        foreach ($payoffsForBonus as $key => $value) {
            $totalWinningsOnBonus += $value['winning'];
        }

        $newValue = $oldValue + $totalWinningsOnLines + $totalWinningsOnBonus;

        return $newValue;
    }

    /**
     * Вычисление нового значения общего выигрыша в featureGame
     *
     * @param int $oldValue
     * @param array $payoffsForLines
     * @param array $payoffsForBonus
     *
     * @return int
     */
    public function calculateIsWinOnFeatureGame(int $oldValue, array $payoffsForLines, array $payoffsForBonus): int
    {
        // получение выигрыша по линиям
        $totalWinningsOnLines = 0;
        foreach ($payoffsForLines as $key => $payoffsForLine) {
            $totalWinningsOnLines += $payoffsForLine['winValue'];
        }

        // получение выигрыша на бонусных символах
        $totalWinningsOnBonus = 0;
        foreach ($payoffsForBonus as $key => $value) {
            $totalWinningsOnBonus += $value['winning'];
        }

        $newValue = $oldValue + $totalWinningsOnLines + $totalWinningsOnBonus;

        return $newValue;
    }

    /**
     * Вычисление общего проигрыша
     *
     * @param int $oldValue
     * @param int $lineBet
     * @param int $linesInGame
     *
     * @return int
     */
    public function calculateTotalLoss(
        int $loss,
        int $lineBet,
        int $linesInGame
    ): int {
        $loss = $loss + $lineBet * $linesInGame;

        return $loss;
    }

    /**
     * Вычисление общего проигрыша в основной игре
     *
     * @param int $oldValue
     * @param int $lineBet
     * @param int $linesInGame
     *
     * @return int
     */
    public function calculateTotalLossOnMainGame(int $oldValue, int $lineBet, int $linesInGame): int
    {
        $newValue = $oldValue + $lineBet * $linesInGame;

        return $newValue;
    }

    /**
     * Вычисление общего проигрыша в featureGame
     *
     * @param int $oldValue
     * @param int $lineBet
     * @param int $linesInGame
     *
     * @return int
     */
    public function calculateTotalLossOnFeatureGame(int $oldValue, int $lineBet, int $linesInGame): int
    {
        $newValue = $oldValue + $lineBet * $linesInGame;

        return $newValue;
    }

    /**
     * Вычисление общего кол-ва кручений
     *
     * @param int $oldValue
     *
     * @return int
     */
    public function calculateTotalSpinCount(int $oldValue): int
    {
        return $oldValue + 1;
    }

    /**
     * Вычисление общего кол-ва кручений в основной игре
     *
     * @param int $oldValue
     *
     * @return int
     */
    public function calculateSpinCountInMainGame(
        int $spinCountInMainGame,
        string $screen,
        bool $isDropFeatureGame
    ): int {
        if ($screen === 'mainGame' || $isDropFeatureGame) {
            $spinCountInMainGame += 1;
        }

        return $spinCountInMainGame;
    }

    public function calculateSpinCountInFeatureGame(
        int $oldValue
    ): int {
        return $oldValue + 1;
    }

    public function calculateTotalWinSpinCount(int $winSpinCount, bool $isWin): int
    {
        if ($isWin) {
            $winSpinCount += 1;
        }

        return $winSpinCount;
    }

    public function calculateTotalWinSpinCountOnMainGame(
        int $winSpinCountInMainGame,
        bool $isWin,
        string $screen,
        bool $isDropFeatureGame
    ): int {
        if ($screen === 'mainGame' || $isDropFeatureGame) {
            if ($isWin) {
                $winSpinCountInMainGame += 1;
            }
        }

        return $winSpinCountInMainGame;
    }

    public function calculateTotalWinSpinCountOnFeatureGame(
        int $winSpinCountInFeatureGame,
        bool $isWin
    ): int {
        if ($isWin) {
            $winSpinCountInFeatureGame += 1;
        }

        return $winSpinCountInFeatureGame;
    }

    public function calculateTotalLoseSpinCount(
        int $loseSpinCount,
        bool $isWin
    ): int {
        if (!$isWin) {
            $loseSpinCount += 1;
        }

        return $loseSpinCount;
    }

    public function calculateLoseSpinCountOnMainGame(
        int $loseSpinCountInMainGame,
        bool $isWin,
        string $screen
    ): int {
        if ($screen === 'mainGame') {
            if (!$isWin) {
                $loseSpinCountInMainGame += 1;
            }
        }

        return $loseSpinCountInMainGame;
    }

    public function calculateLoseSpinCountOnFeatureGame(
        int $loseSpinCountInFeatureGame,
        bool $isWin
    ): int {
        if (!$isWin) {
            $loseSpinCountInFeatureGame += 1;
        }

        return $loseSpinCountInFeatureGame;
    }

    public function calculateFeatureGamesDropped(
        int $featureGamesDropped,
        bool $isDropFeatureGame
    ): int {
        if ($isDropFeatureGame) {
            $featureGamesDropped += 1;
        }

        return $featureGamesDropped;
    }

    public function calculatePercentWinSpins(
        int $spinCount,
        int $winSpinCount
    ): float {
        $percent = 100 / $spinCount * $winSpinCount;

        return (float) $percent;
    }

    public function calculatePercentWinSpinsInMainGame(
        int $spinCount,
        int $winSpinCountInMainGame
    ): float {
        $percent = 100 / $spinCount * $winSpinCountInMainGame;

        return (float) $percent;
    }

    public function calculatePercentWinSpinsInFeatureGame(
        int $spinCountInFeatureGame,
        int $winSpinCountInFeatureGame
    ): float {
        $percent = 100 / $spinCountInFeatureGame * $winSpinCountInFeatureGame;

        return (float) $percent;
    }

    public function calculatePercentLoseSpins(
        int $spinCount,
        int $loseSpinCount
    ): float {
        $percentLoseSpins = 100 / $spinCount * $loseSpinCount;

        return (float) $percentLoseSpins;
    }

    public function calculatePercentLoseSpinsInMainGame(
        int $spinCount,
        int $loseSpinCountInMainGame
    ): float {
        $percentLoseSpinsInMainGame = 100 / $spinCount * $loseSpinCountInMainGame;

        return (float) $percentLoseSpinsInMainGame;
    }

    public function calculatePercentLoseSpinsInFeatureGame(
        int $spinCountInFeatureGame,
        int $loseSpinCountInFeatureGame
    ): float {
        $percentLoseSpinsInFeatureGame = 100 / $spinCountInFeatureGame * $loseSpinCountInFeatureGame;

        return (float) $percentLoseSpinsInFeatureGame;
    }

    public function calculateWinPercent(
        int $winnings,
        int $loss
    ): float {
        $winPercent = 100 / $loss * $winnings;

        return (float) $winPercent;
    }

    public function calculateWinPercentOnMainGame(
        int $winningsOnMainGame,
        int $loss
    ): float {
        $percent = 100 / $loss * $winningsOnMainGame;

        return (float) $percent;
    }

    public function calculateWinPercentOnFeatureGame(
        int $winningsOnFeatureGame,
        int $loss,
        int $winnings,
        int $winningsOnMainGame
    ): float {
        //$percent = 100 / $loss * ($winnings - $winningsOnMainGame);
        $percent = 100 / $loss * ($winnings - $winningsOnMainGame);

        return (float) $percent;
    }

    public function calculateStatisticOfWinCombinations(
        array $statisticOfWinCombinations, // [номер_символа => [кол-во_символов_в_комбинации => кол-во_выигрышей, ...], ... ]
        array $winningLines // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
    ): array {
        foreach ($winningLines as $winningLine) {
            $statisticOfWinCombinations[$winningLine['symbol']][$winningLine['winCellCount']] += 1;
        }

        return $statisticOfWinCombinations;
    }

    public function calculateStatisticOfWinCombinationsInMainGame(
        array $statisticOfWinCombinationsInMainGame, // [номер_символа => [кол-во_символов_в_комбинации => кол-во_выигрышей, ...], ... ]
        array $winningLines, // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
        string $screen
    ): array {
        if ($screen === 'mainGame') {
            foreach ($winningLines as $winningLine) {
                $statisticOfWinCombinationsInMainGame[$winningLine['symbol']][$winningLine['winCellCount']] += 1;
            }
        }

        return $statisticOfWinCombinationsInMainGame;
    }

    public function calculateStatisticOfWinCombinationsInFeatureGame(
        array $statisticOfWinCombinationsInFeatureGame, // [номер_символа => [кол-во_символов_в_комбинации => кол-во_выигрышей, ...], ... ]
        array $winningLines // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
    ): array {
        foreach ($winningLines as $winningLine) {
            $statisticOfWinCombinationsInFeatureGame[$winningLine['symbol']][$winningLine['winCellCount']] += 1;
        }

        return $statisticOfWinCombinationsInFeatureGame;
    }

    public function calculateStatisticsOfDroppedSymbols(
        array $statisticsOfDroppedSymbols, // [номер_символа => кол-во_выпадений]
        array $table // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
    ): array {
        foreach ($table as $symbol) {
            $statisticsOfDroppedSymbols[$symbol] += 1;
        }

        return $statisticsOfDroppedSymbols;
    }

    public function calculateStatisticsOfDroppedSymbolsInMainGame(
        array $statisticsOfDroppedSymbolsInMainGame, // [номер_символа => кол-во_выпадений]
        array $table // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
    ): array {
        foreach ($table as $symbol) {
            $statisticsOfDroppedSymbolsInMainGame[$symbol] += 1;
        }

        return $statisticsOfDroppedSymbolsInMainGame;
    }

    public function calculateStatisticsOfDroppedSymbolsInFeatureGame(
        array $statisticsOfDroppedSymbolsInFeatureGame, // [номер_символа => кол-во_выпадений]
        array $table // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...]
    ): array {
        foreach ($table as $symbol) {
            $statisticsOfDroppedSymbolsInFeatureGame[$symbol] += 1;
        }

        return $statisticsOfDroppedSymbolsInFeatureGame;
    }

    public function calculateStatisticOfWinBonusCombinations(
        array $statisticOfWinBonusCombinations, // [кол-во_символов_в_комбинации => [кол-во_джокеров_в_комбинации => кол-во_выпадений]]
        array $payoffsForBonus, // [['symbol' => , 'count' => , 'winning' => ], ...]
        array $table
    ): array {
        $jockerCounter = 0;
        foreach ($table as $symbol) {
            if ($symbol === 0) {
                $jockerCounter += 1;
            }
        }

        foreach ($payoffsForBonus as $payoffForBonus) {
            $statisticOfWinBonusCombinations[$payoffForBonus['count']][$jockerCounter] += 1;
        }

        return $statisticOfWinBonusCombinations;
    }

    public function calculateDroppedBonusSymbolsInOneSpin(
        array $droppedBonusSymbolsInOneSpin,
        array $table
    ): array {
        $count = 0;
        foreach ($table as $key => $value) {
            if ($value === 10) {
                $count += 1;
            }
        }

        $droppedBonusSymbolsInOneSpin[$count] = $droppedBonusSymbolsInOneSpin[$count] + 1;

        return $droppedBonusSymbolsInOneSpin;
    }

    public function calculateDroppedBonusSymbolsInOneSpinInMainGame(
        array $droppedBonusSymbolsInOneSpin,
        array $table
    ): array {
        $count = 0;
        foreach ($table as $key => $value) {
            if ($value === 10) {
                $count += 1;
            }
        }

        $droppedBonusSymbolsInOneSpin[$count] = $droppedBonusSymbolsInOneSpin[$count] + 1;

        return $droppedBonusSymbolsInOneSpin;
    }

    public function calculateDroppendDiamandsInCurrentFeatureGame(
        int $droppendDiamandsInCurrentFeatureGame,
        array $table
    ): int {
        foreach ($table as $key => $value) {
            if ($value === 0) {
                $droppendDiamandsInCurrentFeatureGame += 1;
            }
        }

        return $droppendDiamandsInCurrentFeatureGame;
    }

    public function calculateMinCountDroppenDiamandsInFreeSpinGame(
        int $minDroppendDiamandsInFeatureGame,
        int $droppendDiamandsInCurrentFeatureGame
    ): int {
        if ($minDroppendDiamandsInFeatureGame > $droppendDiamandsInCurrentFeatureGame) {
            $minDroppendDiamandsInFeatureGame = $droppendDiamandsInCurrentFeatureGame;
        }

        return $minDroppendDiamandsInFeatureGame;
    }

    public function calculateMaxCountDroppenDiamandsInFreeSpinGame(
        int $maxDroppendDiamandsInFeatureGame,
        int $droppendDiamandsInCurrentFeatureGame
    ): int {
        if ($maxDroppendDiamandsInFeatureGame < $droppendDiamandsInCurrentFeatureGame) {
            $maxDroppendDiamandsInFeatureGame = $droppendDiamandsInCurrentFeatureGame;
        }

        return $maxDroppendDiamandsInFeatureGame;
    }

}
