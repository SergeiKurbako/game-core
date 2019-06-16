<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BalanceWorkerTest extends TestCase
{
    /**
     * Проверка работа balanceWorker
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testGetResultOfSpinForBalanceWorker1()
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

        $dataPool = $workersPool->balanceWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->balanceData->balance !== 85) {
            $check = false;
        }
        if ($dataPool->balanceData->totalPayoff !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByJackpot !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByDouble !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы balanceWorker
     * Условия: выигрышь по линии
     * Результат: выигрышь 10
     */
    public function testGetResultOfSpinForBalanceWorker2()
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

        // выполнение операции
        $dataPool = $workersPool->balanceWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->balanceData->balance !== 95) {
            $check = false;
        }
        if ($dataPool->balanceData->totalPayoff !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByLines !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByJackpot !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByDouble !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы balanceWorker
     * Условия: проигрышь во время фриспинов
     * Результат: все остается неизменным
     */
    public function testGetResultOfSpinForBalanceWorker3()
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
        $dataPool->logicData->table = [8,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [];
        $dataPool->logicData->payoffsForLines = [];
        $dataPool->logicData->winningCells = [];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->stateData->screen = 'featureGame';

        // выполнение операции
        $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->balanceData->balance !== 100) {
            $check = false;
        }
        if ($dataPool->balanceData->totalPayoff !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByJackpot !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByDouble !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы balanceWorker
     * Условия: выигрышь во время фриспинов
     * Результат: баланс только увеличивается; увеличивается выигрышь во время фриспинов
     */
    public function testGetResultOfSpinForBalanceWorker4()
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
        $dataPool->logicData->table = [8,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $dataPool->logicData->winningLines = [["lineNumber" => 0, "symbol" => 1, "winCellCount" => 2]];
        $dataPool->logicData->payoffsForLines = [["lineNumber" => 0, "winValue" => 10]];
        $dataPool->logicData->winningCells = [[1 => 1, 4 => 1]];
        $dataPool->logicData->payoffsForBonus = [];
        $dataPool->stateData->screen = 'featureGame';

        // выполнение операции
        $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->balanceData->balance !== 110) {
            $check = false;
        }
        if ($dataPool->balanceData->totalPayoff !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByLines !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByJackpot !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByDouble !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->totalWinningsInFeatureGame !== 10) {
            $check = false;
        }

        // выполнение операции
        $dataPool = $workersPool->balanceWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->balanceData->balance !== 120) {
            $check = false;
        }
        if ($dataPool->balanceData->totalPayoff !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByLines !== 10) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByJackpot !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->payoffByDouble !== 0) {
            $check = false;
        }
        if ($dataPool->balanceData->totalWinningsInFeatureGame !== 20) {
            $check = false;
        }

        $this->assertTrue($check);
    }
}
