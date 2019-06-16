<?php

namespace Tests\Games\LifeOfLuxury2\Tools\StateTools;

use App\Models\V2GameRule;
use Tests\TestCase;
use App\Classes\Games\LifeOfLuxury2\Tools\StateTools\StateCalculatorTool;

class StateCalculatorToolTest extends TestCase
{
    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в mainGame и выпадает 2 монеты
     * Результат: featureGame не выпадает
     */
    public function testСalculateIsDropFeatureGame1()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'mainGame';
        $table = [10,2,3,4,10,6,7,8,9,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== false) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в mainGame и выпадает 3 монеты
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGame2()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'mainGame';
        $table = [10,2,3,4,10,6,7,8,10,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в mainGame и выпадает 2 монеты и алмаз
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGame3()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'mainGame';
        $table = [10,2,3,4,10,6,7,8,0,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в mainGame и выпадает 2 монеты и 3 алмаза
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGame4()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'mainGame';
        $table = [10,2,3,4,0,6,7,8,0,1,2,0,4,5,10];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в featureGame и выпадает 2 монеты
     * Результат: featureGame не выпадает
     */
    public function testСalculateIsDropFeatureGameForFG1()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'featureGame';
        $table = [10,2,3,4,10,6,7,8,9,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== false) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в featureGame и выпадает 3 монеты
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGameForFG2()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'featureGame';
        $table = [10,2,3,4,10,6,7,8,10,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в featureGame и выпадает 2 монеты и алмаз
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGameForFG3()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'featureGame';
        $table = [10,2,3,4,10,6,7,8,0,1,2,3,4,5,6];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения fatureGame в основной игре
     * Условия: делается ход в featureGame и выпадает 2 монеты и 3 алмаза
     * Результат: featureGame выпадает
     */
    public function testСalculateIsDropFeatureGameForFG4()
    {
        $check = true;

        $stateCalculatorTool = new StateCalculatorTool();

        $screen = 'featureGame';
        $table = [10,2,3,4,0,6,7,8,0,1,2,0,4,5,10];
        $featureGameRoules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'featureGame')->first()->rules);

        $isDropFeatureGame = $stateCalculatorTool->calculateIsDropFeatureGame(
            $screen,
            $table,
            $featureGameRoules
        );

        if ($isDropFeatureGame !== true) {
            $check = false;
        }

        $this->assertTrue($check);
    }

}
