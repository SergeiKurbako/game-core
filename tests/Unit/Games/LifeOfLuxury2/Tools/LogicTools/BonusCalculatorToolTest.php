<?php

namespace Tests\Games\LifeOfLuxury2\Tools\LogicTools;

use Tests\TestCase;
use App\Models\V2GameRule;
use App\Classes\Games\LifeOfLuxury2\Tools\LogicTools\BonusCalculatorTool;

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
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
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
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
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
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
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

    /**
     * Тест проверяющий, что при наличии на поле алмаза делается дополнительное удвоение выигрыша на бонусах
     * Условия: выпадают 2 бонусных символа и алмаз
     * Результат: выигрышь === 60
     */
    public function testGetBonusWinningsForMainGame4()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,0,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;

        $result = $bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet
        );

        if ($result[0] === ['symbol' => 10, 'count' => 3, 'winning' => 60]) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы обработки бонусных символах
     * Условия: бонусные символы не выпадают
     * Результат: проигрышь
     */
    public function testGetBonusWinningsForFreeGame1()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;
        $multiplier = 2;

        $result = $bonusCalculatorTool->getBonusWinningsForFeatureGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet,
            $multiplier
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
    public function testGetBonusWinningsForFreeGame2()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,4,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;
        $multiplier = 2;

        $result = $bonusCalculatorTool->getBonusWinningsForFeatureGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet,
            $multiplier
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
    public function testGetBonusWinningsForFreeGame3()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;
        $multiplier = 2;

        $result = $bonusCalculatorTool->getBonusWinningsForFeatureGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet,
            $multiplier
        );

        if ($result[0] === ['symbol' => 10, 'count' => 3, 'winning' => 60]) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии на поле алмаза делается дополнительное удвоение выигрыша на бонусах
     * Условия: выпадают 2 бонусных символа и алмаз
     * Результат: выигрышь === 120
     */
    public function testGetBonusWinningsForFreeGame4()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,10,2,3,0,5,6];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 15;
        $lineBet = 1;
        $multiplier = 2;

        $result = $bonusCalculatorTool->getBonusWinningsForFeatureGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet,
            $multiplier
        );

        if ($result[0] === ['symbol' => 10, 'count' => 3, 'winning' => 120]) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии на поле алмаза делается дополнительное удвоение выигрыша на бонусах
     * Условия: выпадают 2 бонусных символа и алмаз
     * Результат: выигрышь === 60
     */
    public function testGetBonusWinningsForMainGame5()
    {
        $check = false;

        $bonusCalculatorTool = new BonusCalculatorTool;

        $table = [10,2,3,4,5,6,7,8,9,0,2,3,10,5,2];
        $bonusRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'bonus')->first()->rules);
        $linesInGame = 20;
        $lineBet = 20;

        $result = $bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $bonusRules,
            $linesInGame,
            $lineBet
        );

        if ($result[0] === ['symbol' => 10, 'count' => 3, 'winning' => 1600]) {
            $check = true;
        }

        $this->assertTrue($check);
    }


}
