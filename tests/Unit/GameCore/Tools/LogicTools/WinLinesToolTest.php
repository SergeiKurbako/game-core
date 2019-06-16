<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\V2GameRule;
use App\Classes\GameCore\Tools\LogicTools\WinLinesTool;

class WinLinesToolTest extends TestCase
{
    /**
     * Проверка работы получения выигрышных линий
     * Условия: выпадает проигрышь по всем линиям
     * Результат: выигрышные линии отсутсвуют
     */
    public function testGetWinningLines()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $table = [4,1,2,3,4,5,6,7,8,9,1,2,3,4];
        $lines = json_decode(V2GameRule::where('game_id', 1)->where('name', 'lines')->first()->rules);
        $linesInGame = 15;

        $result = $winLinesTool->getWinningLines(
            $table,
            $lines,
            $linesInGame
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигрышных линий
     * Условия: выпадает выигрышь по линии 1
     * Результат: выигрышь
     */
    public function testGetWinningLines2()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $table = [4,1,2,3,1,5,6,3,8,9,3,2,3,4];
        $lines = json_decode(V2GameRule::where('game_id', 1)->where('name', 'lines')->first()->rules);
        $linesInGame = 15;

        $result = $winLinesTool->getWinningLines(
            $table,
            $lines,
            $linesInGame
        );

        if ($result[0]['winCellCount'] === 2) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигрыша по линиям
     * Условия: выпадает выигрышь по линии 1
     * Результат: выигрышь
     */
    public function testGetPayoffsForLines1()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $lines = [[1,4,7,10,13],[0,3,6,9,12],[2,5,8,11,14],[0,4,8,10,12],[2,4,6,10,14],[1,3,7,11,13],[1,5,7,9,13],[0,3,7,11,14],[2,5,7,9,12],[0,4,6,10,12],[2,4,8,10,14],[1,3,6,9,13],[1,5,8,11,13],[0,4,7,10,12],[2,4,7,10,14],[1,4,6,10,13],[1,4,8,11,14],[0,5,6,11,12],[2,3,8,9,14],[2,3,7,9,14]];
        $table = [7,1,3,4,1,6,7,0,9,8,2,3,4,5,6];
        $winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $winCombinations = json_decode(V2GameRule::where('game_id', 1)->where('name', 'winCombinations')->first()->rules);
        $lineBet = 1;

        $result = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $lines
        );

        if ($result[0]['winValue'] === 10 && $result[0]['lineNumber'] === 0) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигрыша по линиям
     * Условия: выпадает проигрышь
     * Результат: проигрышь
     */
    public function testGetPayoffsForLines2()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $table = [1,2,3,4,5,6,7,8,9,1,2,4,5,6];
        $winningLines = [];
        $winCombinations = json_decode(V2GameRule::where('game_id', 1)->where('name', 'winCombinations')->first()->rules);
        $lines = json_decode(V2GameRule::where('game_id', 1)->where('name', 'lines')->first()->rules);
        $lineBet = 1;

        $result = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $lines
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигравших ячеек и символы в них
     * Условия: выпадает выигрышь
     * Результат: выигрышь
     */
    public function testGetWinningCells()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $table = [4,1,2,3,1,5,6,3,8,9,3,2,3,4];
        $lines = json_decode(V2GameRule::where('game_id', 1)->where('name', 'lines')->first()->rules);
        $winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];

        $result = $winLinesTool->getWinningCells(
            $table,
            $winningLines,
            $lines
        );

        if ($result === [1 => 1, 4 => 1]) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигрышных линий
     * Условия: выпадает выигрышь по линии 1, при этом выбрана только линия 0
     * Результат: выигрышные линии отсутсвуют
     */
    public function testGetWinningLines3()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;

        $table = [1,7,8,1,5,3,4,9,2,4,8,3,6,2,4];
        $lines = json_decode(V2GameRule::where('game_id', 1)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;

        $result = $winLinesTool->getWinningLines(
            $table,
            $lines,
            $linesInGame
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
