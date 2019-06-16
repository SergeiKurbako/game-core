<?php

namespace Avior\GameCore\Tools\BalanceTools;

use Avior\GameCore\Base\ITool;

/**
 * Проверки возможности выполнения действия.
 * Проверки делаются перед вычислениями результатов хода.
 */
class PossibilityСhecker implements ITool
{
    /**
     * Проверка может ли быть совершен ход
     *
     * @param int $balance
     * @param int $lineBet
     * @param int $linesInGame
     *
     * @return bool
     */
    public function checkIsPossibilitySpin(int $balance, int $lineBet, int $linesInGame, string $screen): bool
    {
        $isPossibilitySpin = false;

        if ($screen === 'mainGame' && $balance >= $lineBet * $linesInGame) {
            $isPossibilitySpin = true;
        }

        if ($screen === 'featureGame') {
            $isPossibilitySpin = true;
        }

        return $isPossibilitySpin;
    }
}
