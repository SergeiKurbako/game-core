<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Classes\GameCore\Tools\StatisticsTools\StatisticsCalculatorTool;

class StatisticsCalculatorToolTest extends TestCase
{
    /**
     * Подсчет общего выигрыша
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testCalculateTotalWinnings1()
    {
        $check = false;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalWinnings(0, 1);

        if ($result === 1) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет общего выигрыша
     * Условия: выигрышь
     * Результат: выигрышь
     */
    public function testCalculateTotalWinnings2()
    {
        $check = false;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalWinnings(1, 1);

        if ($result === 2) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет выигрыша в основной игре
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testСalculateTotalWinningsOnMainGame1()
    {
        $check = false;

        $payoffsForLines = [];
        $payoffsForBonus = [];

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalWinningsOnMainGame(0, $payoffsForLines, $payoffsForBonus);

        if ($result === 0) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет выигрыша в основной игре
     * Условия: выигрышь
     * Результат: выигрышь
     */
    public function testСalculateTotalWinningsOnMainGame2()
    {
        $check = false;

        $payoffsForLines = [['lineNumber' => 0, 'winValue' => 1]];
        $payoffsForBonus = [];

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalWinningsOnMainGame(0, $payoffsForLines, $payoffsForBonus);

        if ($result === 1) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет выигрыша в featureGame
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testСalculateIsWinOnFeatureGame1()
    {
        $check = false;

        $payoffsForLines = [];
        $payoffsForBonus = [];

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateIsWinOnFeatureGame(0, $payoffsForLines, $payoffsForBonus);

        if ($result === 0) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет выигрыша в featureGame
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testСalculateIsWinOnFeatureGame2()
    {
        $check = false;

        $payoffsForLines = [['lineNumber' => 0, 'winValue' => 1]];
        $payoffsForBonus = [];

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateIsWinOnFeatureGame(0, $payoffsForLines, $payoffsForBonus);

        if ($result === 1) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет подсчет общего проигрыша
     * Условия: $betLine = 1; $linesInGame = 15;
     * Результат: $result = 15
     */
    public function testСalculateTotalLoss1()
    {
        $check = false;

        $betLine = 1;
        $linesInGame = 15;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalLoss(0, $betLine, $linesInGame);

        if ($result === 15) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет подсчет общего проигрыша
     * Условия: $betLine = 1; $linesInGame = 30;
     * Результат: $result = 30
     */
    public function testСalculateTotalLoss2()
    {
        $check = false;

        $betLine = 1;
        $linesInGame = 15;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalLoss(15, $betLine, $linesInGame);

        if ($result === 30) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет подсчет проигрыша в основной игре
     * Условия: $betLine = 1; $linesInGame = 15;
     * Результат: $result = 15
     */
    public function testСalculateTotalLossOnMainGame1()
    {
        $check = false;

        $betLine = 1;
        $linesInGame = 15;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalLossOnMainGame(0, $betLine, $linesInGame);

        if ($result === 15) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет проигрыша в основной игре
     * Условия: $betLine = 1; $linesInGame = 30;
     * Результат: $result = 30
     */
    public function testСalculateTotalLossOnMainGame2()
    {
        $check = false;

        $betLine = 1;
        $linesInGame = 15;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalLossOnMainGame(15, $betLine, $linesInGame);

        if ($result === 30) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет общее кол-во кручений
     * Условия: $oldValue = 13;
     * Результат: $result = 14
     */
    public function testСalculateTotalSpinCount1()
    {
        $check = false;

        $oldValue = 13;

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateTotalSpinCount($oldValue);

        if ($result === 14) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет кол-во кручений в основной игре
     * Условия: $oldValue = 13;
     * Результат: $result = 14
     */
    public function testСalculateSpinCountOnMainGame1()
    {
        $check = false;

        $oldValue = 13;
        $screen = 'mainGame';

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateSpinCountOnMainGame(
            $oldValue,
            $screen
        );

        if ($result === 14) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Подсчет кол-во кручений в featureGame
     * Условия: $oldValue = 13;
     * Результат: $result = 14
     */
    public function testСalculateSpinCountOnFeatureGame1()
    {
        $check = false;

        $oldValue = 13;
        $screen = 'featureGame';

        $statisticsCalculatorTool = new StatisticsCalculatorTool();
        $result = $statisticsCalculatorTool->calculateSpinCountOnFeatureGame(
            $oldValue,
            $screen
        );

        if ($result === 14) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
