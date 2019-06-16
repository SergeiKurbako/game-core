<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StatisticsWorkerTest extends TestCase
{
    /**
     * Проверка работы StatisticsWorker
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testGetResultOfSpinForStatisticsWorker1()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'mainGame';
        $dataPool->logicData->action = 'spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        $dataPool->logicData->featureGameRules = json_decode('[10, 3]');

        $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы StatisticsWorker
     * Условия: выигрышь по линии
     * Результат:
     */
    public function testGetResultOfSpinForStatisticsWorker2()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        // данные из запроса
        $dataPool->requestData->userId = 1;
        $dataPool->requestData->gameId = 1;

        // загрузка стоковых данных
        $dataPool = $this->loadStartData($dataPool, $workersPool, $toolsPool);

        // изменение данных под условия теста
        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'mainGame';
        $dataPool->logicData->action = 'spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [2,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $dataPool->logicData->payoffsForLines = [["lineNumber" => 0, "winValue" => 10]];
        $dataPool->logicData->winningCells = [[1 => 1, 4 => 1]];
        $dataPool->logicData->featureGameRules = json_decode('[10, 3]');
        $dataPool->balanceData->totalPayoff = 10;


        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы StatisticsWorker
     * Условия: выпадение фриспинов
     * Результат:
     */
    public function testGetResultOfSpinForStatisticsWorker3()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        // данные из запроса
        $dataPool->requestData->userId = 1;
        $dataPool->requestData->gameId = 1;

        // загрузка стоковых данных
        $dataPool = $this->loadStartData($dataPool, $workersPool, $toolsPool);

        // изменение данных под условия теста
        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'mainGame';
        $dataPool->logicData->action = 'spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [10,6,3,5,1,10,7,8,9,4,10,3,4,5,6];
        $dataPool->logicData->featureGameRules = json_decode('[10, 3]');
        $dataPool->logicData->winningLines = [];
        $dataPool->logicData->payoffsForLines = [];
        $dataPool->logicData->winningCells = [];
        $dataPool->logicData->payoffsForBonus = [['symbol' => 10, 'count' => 3, 'winning' => 225]];
        $dataPool->balanceData->totalPayoff = 225;

        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 225) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 225) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы StatisticsWorker
     * Условия: проигрышь во время фриспинов
     * Результат:
     */
    public function testGetResultOfSpinForStatisticsWorker4()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        // данные из запроса
        $dataPool->requestData->userId = 1;
        $dataPool->requestData->gameId = 1;

        // загрузка стоковых данных
        $dataPool = $this->loadStartData($dataPool, $workersPool, $toolsPool);

        // изменение данных под условия теста
        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'featureGame';
        $dataPool->logicData->action = 'free_spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [1,6,3,5,1,2,7,8,9,4,6,3,4,5,6];
        $dataPool->logicData->featureGameRules = json_decode('[10, 3]');
        $dataPool->logicData->winningLines = [];
        $dataPool->logicData->payoffsForLines = [];
        $dataPool->logicData->winningCells = [];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->balanceData->totalPayoff = 0;

        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы StatisticsWorker
     * Условия: выигрышь во время фриспинов
     * Результат:
     */
    public function testGetResultOfSpinForStatisticsWorker5()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        // данные из запроса
        $dataPool->requestData->userId = 1;
        $dataPool->requestData->gameId = 1;

        // загрузка стоковых данных
        $dataPool = $this->loadStartData($dataPool, $workersPool, $toolsPool);

        // изменение данных под условия теста
        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'featureGame';
        $dataPool->logicData->action = 'free_spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [8,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $dataPool->logicData->payoffsForLines = [["lineNumber" => 0, "winValue" => 10]];
        $dataPool->logicData->winningCells = [[1 => 1, 4 => 1]];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->balanceData->totalPayoff = 10;

        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 20) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 20) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 2) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 2) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы StatisticsWorker
     * Условия: совершение последнего фриспина
     * Результат:
     */
    public function testGetResultOfSpinForStatisticsWorker6()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        // данные из запроса
        $dataPool->requestData->userId = 1;
        $dataPool->requestData->gameId = 1;

        // загрузка стоковых данных
        $dataPool = $this->loadStartData($dataPool, $workersPool, $toolsPool);

        // изменение данных под условия теста
        $dataPool->balanceData->balance = 100;
        $dataPool->stateData->screen = 'featureGame';
        $dataPool->logicData->action = 'free_spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [8,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $dataPool->logicData->payoffsForLines = [["lineNumber" => 0, "winValue" => 10]];
        $dataPool->logicData->winningCells = [[1 => 1, 4 => 1]];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->stateData->moveNumberInFeatureGame = 9;
        $dataPool->balanceData->totalPayoff = 10;

        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 0) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        // совершение хода после фриспинов
        $dataPool->balanceData->balance = 110;
        $dataPool->stateData->screen = 'mainGame';
        $dataPool->logicData->action = 'spin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [8,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $dataPool->logicData->payoffsForLines = [["lineNumber" => 0, "winValue" => 10]];
        $dataPool->logicData->winningCells = [[1 => 1, 4 => 1]];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->stateData->moveNumberInFeatureGame = 9;
        $dataPool->balanceData->totalPayoff = 10;
        // выполнение операции
        $dataPool = $workersPool->statisticsWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->statisticsData->totalWinnings !== 20) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnMainGame !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalWinningsOnFeatureGame !== 10) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLoss !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalLossOnMainGame !== 15) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCount !== 2) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnMainGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalSpinCountOnFeatureGame !== 1) {
            $check = false;
        }
        if ($dataPool->statisticsData->totalJackpots !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

}
