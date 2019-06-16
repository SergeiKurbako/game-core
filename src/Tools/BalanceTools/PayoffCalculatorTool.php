<?php

namespace Avior\GameCore\Tools\BalanceTools;

use Avior\GameCore\Base\ITool;

/**
 * Подсчет выигрыша
 */
class PayoffCalculatorTool implements ITool
{
    /**
     * Получение суммы выигрыша за линии
     *
     * @param array $payoffsForLines [['lineNumber' => , 'winValue' => ], ...]
     *
     * @return int
     */
    public function getPayoffByLines(array $payoffsForLines): int
    {
        $winOnLines = 0;
        foreach ($payoffsForLines as $payoffsForLine) {
            $winOnLines += $payoffsForLine['winValue'];
        }

        return $winOnLines;
    }

    /**
     * Сумирование выигрыша на бонусных символах
     *
     * @param array $payoffsForBonus [['symbol' => , 'count' => , 'winning' => ], ...]
     *
     * @return int
     */
    public function getPayoffByBonus(array $payoffsForBonus): int
    {
        $payoffByBonus = 0;
        foreach ($payoffsForBonus as $payoffForBonus) {
            $payoffByBonus += $payoffForBonus['winning'];
        }

        return $payoffByBonus;
    }

    /**
     * Сумирование всех выигрышей
     *
     * @param array $allWinnings
     *
     * @return int
     */
    public function getTotalWinnings(
        int $payoffByLines,
        int $payoffByBonus
    ): int
    {

    }
}
