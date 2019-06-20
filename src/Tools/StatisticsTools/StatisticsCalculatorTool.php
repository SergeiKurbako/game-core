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
        string $screen
    ): int {
        if ($screen === 'mainGame') {
            $spinCountInMainGame += 1;
        }

        return $spinCountInMainGame;
    }

    public function calculateSpinCountOnFeatureGame(int $oldValue, string $screen): int
    {
        if ($screen === 'featureGame') {
            return $oldValue + 1;
        } else {
            return $oldValue;
        }
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
        string $screen
    ): int {
        if ($screen === 'mainGame') {
            if ($isWin) {
                $winSpinCountInMainGame += 1;
            }
        }

        return $winSpinCountInMainGame;
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
        $percentWinSpins = $spinCount / 100 * $winSpinCount;

        return (float) $percentWinSpins;
    }

    public function calculatePercentWinSpinsInMainGame(
        int $spinCount,
        int $winSpinCountInMainGame
    ): float {
        $percentWinSpinsInMainGame = $spinCount / 100 * $winSpinCountInMainGame;

        return (float) $percentWinSpinsInMainGame;
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
        $winPercentOnMainGame = 100 / $loss * $winningsOnMainGame;

        return (float) $winPercentOnMainGame;
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



}
