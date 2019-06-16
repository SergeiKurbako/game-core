<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\V2GameRule;
use App\Classes\GameCore\Tools\LogicTools\BonusCalculatorTool;

class BonusCalculatorToolTest extends TestCase
{
    /**
     * Проверка работы обработки бонусных символах
     * Условия: бонусные символы не выпадают
     * Результат: проигрышь
     */
    public function testGetBonusWinningsForMainGame1()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 1)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;

        $result = $bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы обработки бонусных символах
     * Условия: выпадают 2 бонусных символа
     * Результат: проигрышь
     */
    public function testGetBonusWinningsForMainGame2()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,4,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 1)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;

        $result = $bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы обработки бонусных символах
     * Условия: выпадают 3 бонусных символа
     * Результат: выигрышь
     */
    public function testGetBonusWinningsForMainGame3()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 1)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;

        $result = $bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet
        );

        if ($result[0] === ['symbol' => 10, 'count' => 3, 'winning' => 30]) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
