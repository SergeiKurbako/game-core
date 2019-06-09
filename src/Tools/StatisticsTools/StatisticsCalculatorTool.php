<?php

namespace App\Classes\GameCore\Tools\StatisticsTools;

use App\Classes\GameCore\Base\ITool;
use App\Classes\GameCore\Base\IDataPool;

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
    public function calculateTotalLoss(int $oldValue, int $lineBet, int $linesInGame): int
    {
        $newValue = $oldValue + $lineBet * $linesInGame;

        return $newValue;
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
    public function calculateSpinCountOnMainGame(int $oldValue, string $screen): int
    {
        if ($screen === 'mainGame') {
            return $oldValue + 1;
        } else {
            return $oldValue;
        }
    }

    public function calculateJackpots(int $oldValue): int
    {
        return $oldValue;
    }

    public function calculateSpinCountOnFeatureGame(int $oldValue, string $screen): int
    {
        if ($screen === 'featureGame') {
            return $oldValue + 1;
        } else {
            return $oldValue;
        }
    }

    public function calculateFeatureGamesDropped(int $oldValue): int
    {
        return $oldValue;
    }
}
