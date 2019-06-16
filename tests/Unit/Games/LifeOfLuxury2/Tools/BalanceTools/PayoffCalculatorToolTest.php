<?php


namespace Tests\Unit\Games\LifeOfLuxury2\Tools\BalanceTools;


use Tests\TestCase;
use App\Classes\GameCore\Tools\BalanceTools\PayoffCalculatorTool;

class PayoffCalculatorToolTest extends TestCase
{
    /**
     * тест проверяющий, что выигрышь по линии правильно сумируется
     * Условия: выпадает выигрышь по линии 1 и 2
     * Результат: выигрышь правильно сумируется
     */
    public function testPayoffCalculatorTool1()
    {
        $check = true;

        $payoffCalculatorTool = new PayoffCalculatorTool;

        $payoffsForLines = [['lineNumber' => 0, 'winValue' => 10], ['lineNumber' => 1, 'winValue' => 10]];

        $payoffByLines = $payoffCalculatorTool->getPayoffByLines(
            $payoffsForLines
        );

        if ($payoffByLines !== 20) {
            $check = false;
        }

        $this->assertTrue($check);
    }
}
