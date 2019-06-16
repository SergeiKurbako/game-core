<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StateWorkerTest extends TestCase
{
    /**
     * Проверка работы StateWorker
     * Условия: проигрышь
     * Результат: проигрышь
     */
    public function testGetResultOfSpinForStateWorker1()
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

        $dataPool = $workersPool->stateWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работа StateWorker
     * Условия: выигрышь по линии
     * Результат:
     */
    public function testGetResultOfSpinForStateWorker2()
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


        // выполнение операции
        $dataPool = $workersPool->stateWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работа StateWorker
     * Условия: выпадение фриспинов
     * Результат: выпадают фриспины
     */
    public function testGetResultOfSpinForStateWorker3()
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
        $dataPool->logicData->payoffsForBonus = [['symbol' => 10, 'count' => 3, 'winning' => 15]];

        // выполнение операции
        $dataPool = $workersPool->stateWorker->getResultOfSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работа StateWorker
     * Условия: проигрышь во время фриспинов
     * Результат:
     */
    public function testGetResultOfSpinForStateWorker4()
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
        $dataPool->logicData->action = 'freeSpin';
        $dataPool->logicData->linesInGame = 15;
        $dataPool->logicData->lineBet = 1;
        $dataPool->logicData->table = [1,6,3,5,1,2,7,8,9,4,6,3,4,5,6];
        $dataPool->logicData->featureGameRules = json_decode('[10, 3]');
        $dataPool->logicData->winningLines = [];
        $dataPool->logicData->payoffsForLines = [];
        $dataPool->logicData->winningCells = [];
        $dataPool->logicData->payoffsForBonus = [];

        // выполнение операции
        $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работа StateWorker
     * Условия: выигрышь во время фриспинов
     * Результат:
     */
    public function testGetResultOfSpinForStateWorker5()
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
        $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 1) {
            $check = false;
        }

        // выполнение операции
        $dataPool = $workersPool->stateWorker->getResultOfFreeSpin($dataPool, $toolsPool, true);

        if ($dataPool->stateData->isWin !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($dataPool->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($dataPool->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($dataPool->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($dataPool->stateData->isDropJackpot !== false) {
            $check = false;
        }
        if ($dataPool->stateData->moveNumberInFeatureGame !== 2) {
            $check = false;
        }

        $this->assertTrue($check);
    }

}
