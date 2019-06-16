<?php

namespace Avior\GameCore\Tools\LogicTools;

use Avior\GameCore\Base\ITool;

/**
 * Методы для работы с бонусами
 */
class BonusCalculatorTool implements ITool
{
    /**
     * Получение результатов выигрыша на бонусных символах для основной игры
     *
     * @param array $table
     * @param array $bonusRules
     *
     * @return array [['symbol' => , 'count' => , 'winning' => ], ...]
     */
    public function getBonusWinningsForMainGame(
        array $table,
        array $bonusRules,
        int $linesInGame,
        int $lineBet
    ): array
    {
        $bonusWinnings = [];

        // подсчет наличия каждого символа
        $symblosCounter = [];
        foreach ($table as $value) {
            if (!array_key_exists($value, $symblosCounter)) {
                $symblosCounter[$value] = 1;
            } else {
                $symblosCounter[$value] += 1;
            }
        }

        // определение выигрыша на бонусных символах
        foreach ($bonusRules as $key => $value) {
            // при наличии на поле бонусных символов делается проверка на наличие выигрыша
            if (array_key_exists($key, $symblosCounter)) {

                // при отсутсвии такого кол-ва символов берется максимальное доступное значение
                if (!array_key_exists($symblosCounter[$key], $bonusRules)) {
                    $symblosCounter[$key] = 5;
                }

                $winning = $bonusRules[$key][$symblosCounter[$key]] * $linesInGame * $lineBet;
                $count = $symblosCounter[$key];
                if ($winning > 0) {
                    $bonusWinnings[] = ['symbol' => $key, 'count' => $count, 'winning' => $winning];
                }

            }
        }

        return $bonusWinnings;
    }

    /**
     * Получение результатов выигрыша на бонусных символах для featureGame
     *
     * @param array $table
     * @param array $bonusRules
     * @param int $linesInGame
     * @param int $lineBet
     *
     * @return array [['symbol' => , 'count' => , 'winning' => ], ...]
     */
    public function getBonusWinningsForFeatureGame(
        array $table,
        array $bonusRules,
        int $linesInGame,
        int $lineBet,
        int $multiplier
    ): array
    {
        $bonusWinnings = [];

        return $bonusWinnings;
    }
}
