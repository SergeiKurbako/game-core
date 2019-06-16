<?php

namespace Tests\Games\LifeOfLuxury2\Tools\LogicTools;

use Tests\TestCase;
use App\Models\V2GameRule;
use App\Classes\Games\LifeOfLuxury2\Tools\LogicTools\WinLinesTool;

class WinLinesToolTest extends TestCase
{
    /**
     * Проверка работы getWinningLines
     * Условия: не выпадает выигрышных линий
     * Результат: не выпадает выигрышных линий
     */
    public function testGetWinningLines1()
    {
        $check = false;

        $winLinesTool = new WinLinesTool;
        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;

        $result = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        if ($result === []) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getWinningLines
     * Условия: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     * Результат: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     */
    public function testGetWinningLines2()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;
        $table = [1,2,3,4,2,6,7,2,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;

        $result = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        if ($result[0]['lineNumber'] !== 0) {
            $check = false;
        }
        if ($result[0]['symbol'] !== 2) {
            $check = false;
        }
        if ($result[0]['winCellCount'] !== 4) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getWinningLines
     * Условия: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     * где третий символ джокер
     * Результат: выпадает выигрышная линия 0
     */
    public function testGetWinningLines3()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;
        $table = [1,2,3,4,2,6,7,0,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;

        $result = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        if ($result[0]['lineNumber'] !== 0) {
            $check = false;
        }
        if ($result[0]['symbol'] !== 2) {
            $check = false;
        }
        if ($result[0]['winCellCount'] !== 4) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getWinningLines
     * Условия: выпадает выигрышная линия 15 с тремя символами в комбинации
     * где третий символ джокер
     * Результат: выигрышь 40
     */
    public function testGetWinningLines4()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;
        $table = [7,4,8,5,4,2,0,7,5,6,2,8,7,6,3];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;

        $result = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        if ($result[0]['lineNumber'] !== 15) {
            $check = false;
        }
        if ($result[0]['symbol'] !== 4) {
            $check = false;
        }
        if ($result[0]['winCellCount'] !== 3) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getPayoffsForLines
     * Условия: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     * где третий символ джокер
     * Результат: выпадает выигрышная линия 0
     */
    public function testGetPayoffsForLines1()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [1,2,3,4,2,6,7,0,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;
        $lineBet = 1;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules
        );

        if ($payoffsForLines[0]['winValue'] !== 50) {
            $check = false;
        }
        if ($payoffsForLines[0]['lineNumber'] !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getPayoffsForLines
     * Условия: выпадает выигрышная линия 17 с двумя символами в комбинации
     * где второй символ джокер
     * Результат: выпадает выигрышная линия 17 с выирышем 400
     */
    public function testGetPayoffsForLines5()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [9,6,10,4,3,0,4,8,2,2,9,6,7,9,2];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules
        );

        if ($payoffsForLines[0]['winValue'] !== 200) {
            $check = false;
        }
        if ($payoffsForLines[0]['lineNumber'] !== 17) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getWinningCells
     * Условия: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     * где третий символ "джокер"
     * Результат: выпадает 4 выигрышные ячейки
     */
    public function testGetWinningCells1()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [1,2,3,4,2,6,7,0,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winningCells = $winLinesTool->getWinningCells(
            $table,
            $winningLines,
            $linesRules
        );

        if ($winningCells[1] !== 2) {
            $check = false;
        }
        if ($winningCells[4] !== 2) {
            $check = false;
        }
        if ($winningCells[7] !== 0) {
            $check = false;
        }
        if ($winningCells[10] !== 2) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что выигрышь по линии умножается на ставку
     * Условия: выпадает выигрышная линия 0 с двумя самолетами
     * Результат: выигрышь по линии 0 === 200
     */
    public function testGetPayoffsForLines2()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,4,1,6,7,3,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules
        );

        if ($payoffsForLines[0]['winValue'] !== 200) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии в выигрышной комбинации алмаза делается дополнительное удвоение ставки
     * Условия: выпадает выигрышная линия 0 с самолетом и алмазом
     * Результат: выигрышь по линии 0 === 400
     */
    public function testGetPayoffsForLines3()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,4,0,6,7,3,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules
        );

        if ($payoffsForLines[0]['winValue'] !== 400) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии в выигрышной комбинации двух алмазов
     * удвоение выигрыша по линии делается один раз, а не от каждого алмаза
     * Условия: выпадает выигрышная линия 0 с самолетом и 2-мя алмазами
     * Результат: два выигрышных символа
     */
    public function testGetPayoffsForLines4()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,4,5,0,7,8,0,7,2,3,4,5,6,7];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 1;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules
        );

        if ($payoffsForLines[0]['winValue'] !== 100) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии в выигрышной комбинации алмаза, он учитывается как выигрышный символ
     * Условия: выпадает выигрышная линия 0 с самолетом и алмазом
     * Результат: два выигрышных символа
     */
    public function testGetWinningCells2()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,4,0,6,7,3,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        // получение выигрышных ячеек
        $winningCells = $winLinesTool->getWinningCells(
            $table,
            $winningLines,
            $linesRules
        );

        if ($winningCells[1] !== 1) {
            $check = false;
        }
        if ($winningCells[4] !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getPayoffsForLines для featureGame
     * Условия: выпадает выигрышная линия 0 с четырьмя символами в комбинации
     * где третий символ джокер
     * Результат: выпадает выигрышная линия 0 и выигрышь по ней 100
     */
    public function testGetPayoffsForLinesForFreeSpin1()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [1,2,3,4,2,6,7,0,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 20;
        $lineBet = 1;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $multiplier = 2;
        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules,
            $multiplier
        );

        if ($payoffsForLines[0]['winValue'] !== 100) {
            $check = false;
        }
        if ($payoffsForLines[0]['lineNumber'] !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что выигрышь по линии умножается на ставку
     * Условия: выпадает выигрышная линия 0 с двумя самолетами
     * Результат: выигрышь по линии 0 === 400
     */
    public function testGetPayoffsForLinesForFreeSpin2()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,4,1,6,7,3,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $multiplier = 2;
        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules,
            $multiplier
        );

        if ($payoffsForLines[0]['winValue'] !== 400) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии в выигрышной комбинации алмаза делается дополнительное удвоение ставки
     * Условия: выпадает выигрышная линия 0 с самолетом и алмазом
     * Результат: выигрышь по линии 0 === 800
     */
    public function testGetPayoffsForLinesForFreeSpin3()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,4,0,6,7,3,9,1,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $multiplier = 2;
        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules,
            $multiplier
        );

        if ($payoffsForLines[0]['winValue'] !== 800) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Тест проверяющий, что при наличии в выигрышной комбинации алмаза делается дополнительное удвоение ставки
     * Условия: выпадает выигрышная линия 0 с самолетом и алмазом
     * Результат: выигрышь по линии 0 === 800
     */
    public function testGetPayoffsForLinesForFreeSpin4()
    {
        $check = true;

        $winLinesTool = new WinLinesTool;

        $table = [2,1,3,5,0,6,7,8,9,4,2,3,4,5,6];
        $linesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'lines')->first()->rules);
        $linesInGame = 1;
        $lineBet = 20;

        $winningLines = $winLinesTool->getWinningLines(
            $table,
            $linesRules,
            $linesInGame
        );

        $multiplier = 2;
        $winCombinations = json_decode(V2GameRule::where('game_id', 2)->where('name', 'winCombinations')->first()->rules);
        $payoffsForLines = $winLinesTool->getPayoffsForLines(
            $lineBet,
            $table,
            $winningLines,
            $winCombinations,
            $linesRules,
            $multiplier
        );

        if ($payoffsForLines[0]['winValue'] !== 800) {
            $check = false;
        }

        $this->assertTrue($check);
    }

}
