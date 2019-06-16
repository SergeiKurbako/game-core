<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\V2GameRule;
use App\Classes\GameCore\Tools\LogicTools\TableTool;

class TableToolTest extends TestCase
{
    /**
     * Проверка работы функции генерирующей стол
     * Условия: вызывается генерация стола
     * Результат: должен сгенерироваться массив с 15 значениями
     */
    public function testGetRandomTable1()
    {
        $check = false;

        $tableTool = new TableTool;

        $currentPercentages = json_decode(V2GameRule::where('game_id', 1)->where('name', 'percentages')->first()->rules);
        $currentPercentages = $currentPercentages[0]->mainGame;

        $result = $tableTool->getRandomTable($currentPercentages);

        if (count($result) === 15) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы функции генерирующей стол
     * Условия: вызывается генерация стола для featureGame
     * Результат: должен сгенерироваться массив с 15 значениями
     */
    public function testGetRandomTable2()
    {
        $check = false;

        $tableTool = new TableTool;

        $currentPercentages = json_decode(V2GameRule::where('game_id', 1)->where('name', 'percentages')->first()->rules);
        $currentPercentages = $currentPercentages[0]->featureGame;

        $result = $tableTool->getRandomTable($currentPercentages);

        if (count($result) === 15) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
