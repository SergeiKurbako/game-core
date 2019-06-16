<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\GameCore\Tools\BalanceTools\PayoffCalculatorTool;

class PayoffCalculatorToolTest extends TestCase
{
    /**
     * Проверка работы получения выигрыша за линии
     * Условия: выпадает проигрышь по всем линиям
     * Результат: выигрышные = 0
     */
    public function testGetPayoffByLines1()
    {
        $check = false;

        $payoffCalculatorTool = new PayoffCalculatorTool;

        $payoffsForLines = [];

        $result = $payoffCalculatorTool->getPayoffByLines(
            $payoffsForLines
        );

        if ($result === 0) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы получения выигрыша за линии
     * Условия: выпадает выигрышь по линии 1
     * Результат: выигрышные = 10
     */
    public function testGetPayoffByLines2()
    {
        $check = false;

        $payoffCalculatorTool = new PayoffCalculatorTool;

        $payoffsForLines = [['lineNumber' => 0, 'winValue' => 10]];

        $result = $payoffCalculatorTool->getPayoffByLines(
            $payoffsForLines
        );

        if ($result === 10) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getPayoffByBonus
     * Условия: бонусные символы не выпадают
     * Результат: выгрышь на бонусах 0
     */
    public function testGetPayoffByBonus1()
    {
        $check = false;

        $payoffCalculatorTool = new PayoffCalculatorTool;

        $payoffsForBonus = [];

        $result = $payoffCalculatorTool->getPayoffByBonus(
            $payoffsForBonus
        );

        if ($result === 0) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы getPayoffByBonus
     * Условия: выпадают 3 бонусные символа
     * Результат: выгрышь на бонусах 30
     */
    public function testGetPayoffByBonus2()
    {
        $check = false;

        $payoffCalculatorTool = new PayoffCalculatorTool;

        $payoffsForBonus = [['symbol' => 10, 'count' => 3, 'winning' => 30]];

        $result = $payoffCalculatorTool->getPayoffByBonus(
            $payoffsForBonus
        );

        if ($result === 30) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
