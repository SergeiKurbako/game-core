<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\V2GameRule;
use App\Classes\GameCore\Tools\StateTools\StateCalculatorTool;

class StateCalculatorToolTest extends TestCase
{
    /**
     * Проверка работы определения состояния общего выигрыша
     * Условия: выпадает выигрышь по линии
     * Результат: выигрышь
     */
    public function testCalculateIsWin()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWin([['lineNumber' => 1, 'winValue' => 1]], [], [], []);

        if ($result) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния общего выигрыша
     * Условия: не выпадает ни по чем выигрышь
     * Результат: проигрышь
     */
    public function testCalculateIsWin2()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWin([], [], [], []);

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния выигрыша в основной игре
     * Условия: не выпадает ничего
     * Результат: проигрышь
     */
    public function testCalculateIsWinOnMain1()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWinOnMain([], [], []);

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния выигрыша в основной игре
     * Условия: выпадает выигрышь по линиям
     * Результат: выишрышь
     */
    public function testCalculateIsWinOnMain2()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWinOnMain(['lineNumber' => 0, 'winValue' => 10], [], []);

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния выигрыша на бонусных символах
     * Условия: не выпадает ничего
     * Результат: проигрышь
     */
    public function testCalculateIsWinOnBonus1()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWinOnBonus([]);

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния выигрыша на бонусных символах
     * Условия: выпадает выигрышь
     * Результат: выигрышь
     */
    public function testCalculateIsWinOnBonus2()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWinOnBonus([['symbol' => 10, 'count' => 3, 'winning' => 2]]);

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы определения состояния выигрыша в игре на удвоение
     * Условия: выпадает проигрышь
     * Результат: проигрышь
     */
    public function testCalculateIsWinOnDoubleGame1()
    {
        $check = false;

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsWinOnDoubleGame([]);

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения featureGame
     * Условия: не выпадает
     * Результат: не выпадает
     */
    public function testCalculateIsDropFeatureGame1()
    {
        $check = false;

        $screen = 'mainGame';
        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 1)->where('name', 'featureGame')->first()->rules);

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения featureGame
     * Условия: не выпадает
     * Результат: не выпадает
     */
    public function testCalculateIsDropFeatureGame2()
    {
        $check = false;

        $screen = 'mainGame';
        $table = [10,2,3,4,5,6,10,8,9,10,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 1)->where('name', 'featureGame')->first()->rules);

        $stateCalculatorTool = new StateCalculatorTool();
        $result = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }

}
