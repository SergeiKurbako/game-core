<?php

namespace Avior\GameCore\Tools\StateTools;

use Avior\GameCore\Base\ITool;

/**
 * Определение состояния
 *
 * Класс содержит методы которые не зависят от текущего состояния, а вычсляют
 * определеное значение для состояния опираясь на переданные параметы
 */
class StateCalculatorTool implements ITool
{
    /**
     * Вычисление были какой либо выигрышь на данном ходу
     *
     * @param array $payoffsForLines
     * @param array $payoffsForBonus
     * @param array $payoffsForDouble
     * @param array $payoffsForJackpot
     *
     * @return bool
     */
    public function calculateIsWin(
        array $payoffsForLines,
        array $payoffsForBonus,
        array $payoffsForDouble,
        array $payoffsForJackpot
    ): bool
    {
        $isWin = false;

        if ($payoffsForLines !== []) {
            $isWin = true;
        }

        if ($payoffsForBonus !== []) {
            $isWin = true;
        }

        if ($payoffsForDouble !== []) {
            $isWin = true;
        }

        if ($payoffsForJackpot !== []) {
            $isWin = true;
        }

        return $isWin;
    }

    /**
     * Вычисление был ли выигышь в основной игре
     *
     * @param array $payoffsForLines
     * @param array $payoffsForBonus
     * @param array $payoffsForJackpot
     *
     * @return bool
     */
    public function calculateIsWinOnMain(
        array $payoffsForLines,
        array $payoffsForBonus,
        array $payoffsForJackpot
    ): bool
    {
        $isWinOnMain = false;

        if ($payoffsForLines !== []) {
            $isWinOnMain = true;
        }

        if ($payoffsForBonus !== []) {
            $isWinOnMain = true;
        }

        if ($payoffsForJackpot !== []) {
            $isWinOnMain = true;
        }

        return $isWinOnMain;
    }

    /**
     * Вычисление был ли выигрышь на бонусных символах
     *
     * @param array $payoffsForBonus
     *
     * @return bool
     */
    public function calculateIsWinOnBonus(array $payoffsForBonus): bool
    {
        $isWinOnBonus = false;

        if ($payoffsForBonus !== []) {
            $isWinOnBonus = true;
        }

        return $isWinOnBonus;
    }

    /**
     * Вычисление был ли выигышь в doubleGame
     *
     * @param array $payoffsForDouble
     *
     * @return bool
     */
    public function calculateIsWinOnDoubleGame(array $payoffsForDouble): bool
    {
        $isWinOnFeatureGame = false;

        if ($payoffsForDouble !== []) {
            $isWinOnFeatureGame = true;
        }

        return $isWinOnFeatureGame;
    }

    /**
     * Вычисление был ли выигышь в featureGame
     *
     * @param array $payoffsForLines
     *
     * @return bool
     */
    public function calculateIsWinOnFeatureGame(
        array $payoffsForLines,
        array $payoffsForBonus
    ): bool
    {
        $isWinOnFeatureGame = false;

        if ($payoffsForLines !== []) {
            $isWinOnFeatureGame = true;
        }

        if ($payoffsForBonus !== []) {
            $isWinOnFeatureGame = true;
        }

        return $isWinOnFeatureGame;
    }

    /**
     * Вычисление выпала ли featureGame
     *
     * @param string $screen
     * @param array $table
     * @param array $featureGameRoules [symbol, requiredAmount]
     *
     * @return bool
     */
    public function calculateIsDropFeatureGame(
        string $screen,
        array $table,
        array $featureGameRoules
    ): bool
    {
        $isDropFeatureGame = false;

        if ($screen === 'mainGame') {
            // подсчет кол-ва выпавших символов
            $count = 0;
            foreach ($table as $key => $value) {
                if ($value === $featureGameRoules[0]) {
                    $count += 1;
                }
            }

            // проверка достаточно ли символов для выпадения featureGame
            if ($count >= $featureGameRoules[1]) {
                $isDropFeatureGame = true;
            }
        }

        return $isDropFeatureGame;
    }

    /**
     * Вычисление выпал ли jackpot
     *
     * @param array $jackpotRoules
     *
     * @return bool
     */
    public function calculateIsDropJackpot(array $jackpotRoules): bool
    {
        $isDropJackpot = false;

        return $isDropJackpot;
    }

    /**
     * Изменение текущего номера хода с учетом выпадения новой featureGame
     *
     * @param int $moveNumberInFeatureGame
     * @param bool $isDropFeatureGame
     *
     * @return int
     */
    public function calculateMoveNumbersIfDroppedFeatureGame(
        int $moveNumberInFeatureGame,
        bool $isDropFeatureGame
    ): int
    {
        return $moveNumberInFeatureGame;
    }

    /**
     * Изменение текущего экрана в случае если бесплатные ходы закончились
     *
     * @param string $screen
     * @param int $moveNumberInFeatureGame
     * @param int $countOfMovesInFeatureGame
     *
     * @return string
     */
    public function nullableScreenIfEndFeatureGame(
        string $screen,
        int $moveNumberInFeatureGame,
        int $countOfMovesInFeatureGame
    ): string
    {
        if ($moveNumberInFeatureGame === $countOfMovesInFeatureGame) {
            $screen = 'mainGame';
        }

        return $screen;
    }

    /**
     * Изменение текущего экрана в случае если бесплатные ходы закончились
     *
     * @param string $screen
     * @param int $moveNumberInFeatureGame
     * @param int $countOfMovesInFeatureGame
     *
     * @return string
     */
    public function nullableMultiplierIfEndFeatureGame(
        int $multiplier,
        int $moveNumberInFeatureGame,
        int $countOfMovesInFeatureGame
    ): string
    {
        if ($moveNumberInFeatureGame === $countOfMovesInFeatureGame) {
            $multiplier = 1;
        }

        return $multiplier;
    }

    /**
     * Обнуление кол-ва сделанных бесплатных спинов если они закончились
     *
     * @param int $moveNumberInFeatureGame
     * @param int $countOfMovesInFeatureGame
     *
     * @return int
     */
    public function nullableMoveNumbersIfEndFeatureGame(
        int $moveNumberInFeatureGame,
        int $countOfMovesInFeatureGame
    ): int
    {
        if ($moveNumberInFeatureGame === $countOfMovesInFeatureGame) {
            $moveNumberInFeatureGame = 0;
        }

        return $moveNumberInFeatureGame;
    }

    /**
     * Обнуление кол-ва возможных бесплатных спинов если они закончились
     *
     * @param int $moveNumberInFeatureGame
     * @param int $countOfMovesInFeatureGame
     *
     * @return int
     */
    public function nullableCountOfMovesIfEndFeatureGame(
        int $moveNumberInFeatureGame,
        int $countOfMovesInFeatureGame
    ): int
    {
        if ($moveNumberInFeatureGame === $countOfMovesInFeatureGame) {
            $countOfMovesInFeatureGame = 10;
        }

        return $countOfMovesInFeatureGame;
    }

    /**
     * Обнуление кол-ва возможных бесплатных спинов
     *
     * @param int $startCountOfFreeSpinsInFeatureGame
     *
     * @return int
     */
    public function resetTotalSpinCountOnFeatureGame(
        int $startCountOfFreeSpinsInFeatureGame
    ): int
    {
        $totalSpinCountOnFeatureGame = $startCountOfFreeSpinsInFeatureGame;

        return $totalSpinCountOnFeatureGame;
    }

    /**
     * Изменение текущего номера хода в featureGame
     *
     * @return int
     */
    public function resetMoveNumberInFeatureGame(): int
    {
        $moveNumberInFeatureGame = 0;

        return $moveNumberInFeatureGame;
    }

    /**
     * Reset множителя
     *
     * @return int
     */
    public function resetMultiplier(): int
    {
        $multiplier = 2;

        return $multiplier;
    }

    /**
     * Проверка выпадения последнего бесплатного спина в featureGame
     *
     * @param int $moveNumberInFeatureGame
     * @param int $countOfMovesInFeatureGame
     *
     * @return bool
     */
    public function checkEndFeatureGame(
        int $moveNumberInFeatureGame,
        int $countOfMovesInFeatureGame
    ): bool
    {
        $isEndFeatureGame = false;

        if ($moveNumberInFeatureGame === $countOfMovesInFeatureGame) {
            $isEndFeatureGame = true;
        }

        return $isEndFeatureGame;
    }

}
