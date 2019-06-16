<?php

namespace Tests\Games\LifeOfLuxury2\GameTests;

use Tests\TestCase;
use App\Classes\GameCore\GameFactory;
use App\Classes\Games\LifeOfLuxury2\Data\LogicData;
use App\Models\V2Session;

class DemoGameTest extends TestCase
{
    /**
     * Проверка работы spin действия с заготовленным значением table
     * Условия: выпадает проигрышь
     * Результат: все результирующие параметры говорят о проигрыше
     */
    public function testActionSpin1()
    {
        $check = false;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "20"
        ];

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];

        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->isWin === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы spin
     * Условия: выпадает проигрышь
     * Результат: все результирующие параметры говорят о проигрыше
     */
    public function testActionSpin2()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "20"
        ];

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];

        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== 9600) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы spin
     * Условия: выпадают выигрыши по линиям
     * Результат: все результирующие параметры говорят о проигрыше
     */
    public function testActionSpin3()
    {
        $check = true;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];


        $table = [6,5,7,5,9,3,5,9,7,9,4,3,10,9,4];

        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== 9995) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 15) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 15) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $table = [2,5,6,5,2,6,4,6,7,2,1,8,10,3,7];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== 9985) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 10) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 10) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $table = [3,4,5,6,5,8,8,5,4,2,5,1,5,1,7];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== 10040) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 75) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 75) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $table = [2,4,7,8,7,0,8,3,0,6,4,5,8,4,3];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== 10080) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы free_spin в demo режиме
     * Условия: выпадают выигрыши по линиям
     * Результат: выполняются фриспины с обычными выигрышами по линиям
     */
    public function testActionFreeSpinInGodFormat4()
    {
        $check = true;

        // удаление сессии
        $session = V2Session::where('game_id', 2)->where('user_id', 1)->where('mode', 'demo')->get()->first();
        if ($session) {
            $session->delete();
        }

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;
        $balance = json_decode($response)->balanceData->balance;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];


        //выпадают фриспины
        $table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,2];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 40 - 20;


        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 40) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 40) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 2) {
            $check = false;
        }

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "free_spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];

        //фриспин
        $table = [2,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 60;

        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 1) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 60) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 2) {
            $check = false;
        }

        // выполение оставшихся аналогичных ходов в цикле
        for ($i=0; $i < 11; $i++) {
            $responseJson = (new GameFactory())
                ->makeGame(2, "demo")
                ->executeAction($requestArray, $table);
        }
        $response = json_decode($responseJson);

        $balance = $balance + 660;

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 720) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы free_spin в demo режиме
     * Условия: выпадают выигрыши по линиям с алмазом на каждом ходу
     * Результат: прокуручиваются 12 фриспинов
     */
    public function testActionFreeSpinInGodFormat5()
    {
        $check = true;

        // удаление сессии
        $session = V2Session::where('game_id', 2)->where('user_id', 1)->where('mode', 'demo')->get()->first();
        if ($session) {
            $session->delete();
        }

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        // открытие игры
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;
        $balance = json_decode($response)->balanceData->balance;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];


        //выпадают фриспины
        $table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,2];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 40 - 20;


        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 40) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 40) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 2) {
            $check = false;
        }

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "free_spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];

        //фриспин
        $table = [2,1,3,5,0,6,7,8,9,4,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 120;

        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 1) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 120) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 120) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 120) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 3) {
            $check = false;
        }

        // выполение оставшихся аналогичных ходов в цикле
        for ($i=0; $i < 11; $i++) {
            $responseJson = (new GameFactory())
                ->makeGame(2, "demo")
                ->executeAction($requestArray, $table);
        }
        $response = json_decode($responseJson);

        $balance = $balance + 5280;

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60*13) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60*13) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 5400) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка работы free_spin в demo режиме
     * Условия: выпадают фриспинов в фриспинах
     * Результат: прокуручиваются 36 фриспинов
     */
    public function testActionFreeSpinInGodFormat6()
    {
        $check = true;

        // удаление сессии
        $session = V2Session::where('game_id', 2)->where('user_id', 1)->where('mode', 'demo')->get()->first();
        if ($session) {
            $session->delete();
        }

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;
        $requestDataSets = $game->requestDataSets;

        // открытие игры
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => "",
            "token" => null
        ];

        $response = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response)->sessionData->sessionUuid;
        $balance = json_decode($response)->balanceData->balance;

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];


        //выпадают фриспины
        $table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,2];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 40 - 20;


        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 40) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 40) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 2) {
            $check = false;
        }

        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "free_spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];

        //фриспин
        $table = [2,1,3,5,1,6,7,8,9,4,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 60;

        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 1) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 60) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 2) {
            $check = false;
        }

        // подготовка к выпадению featureGame в featureGame
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "free_spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];

        //фриспин с выпадением featureGame
        $table = [10,2,3,10,5,6,0,8,9,1,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 160;



        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 2) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 160) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 160) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 220) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 24) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 3) {
            $check = false;
        }

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        // выполение оставшихся ходов, кроме последего
        for ($i=0; $i < 21; $i++) {
            $responseJson = (new GameFactory())
                ->makeGame(2, "demo")
                ->executeAction($requestArray, $table);
        }
        $response = json_decode($responseJson);

        $balance = $balance;

        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 23) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 220) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 24) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 3) {
            $check = false;
        }


        //фриспин с выпадением featureGame
        $table = [10,2,3,10,5,6,0,8,9,1,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 240;

        if ($response->stateData->screen !== 'featureGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 24) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 240) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 240) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 460) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 36) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 4) {
            $check = false;
        }

        $table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6];
        // выполение оставшихся ходов
        for ($i = 24; $i < 36; $i++) {
            $responseJson = (new GameFactory())
                ->makeGame(2, "demo")
                ->executeAction($requestArray, $table);
        }
        $response = json_decode($responseJson);

        $balance = $balance;

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== true) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 0) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 460) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }


        // подготовка к выполнению спин действия
        $requestArray = [
            "game_id" => "2",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "spin",
            "session_uuid" => $sessionUuid,
            "token" => null,
            "linesInGame" => "20",
            "lineBet" => "1"
        ];


        //выпадают выигрышь в основной игре
        $table = [2,1,3,5,0,6,7,8,9,4,2,3,4,5,6];
        $responseJson = (new GameFactory())
            ->makeGame(2, "demo")
            ->executeAction($requestArray, $table);

        $response = json_decode($responseJson);
        $balance = $balance + 60 - 20;

        if ($response->stateData->screen !== 'mainGame') {
            $check = false;
        }
        if ($response->stateData->isWin !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnMain !== true) {
            $check = false;
        }
        if ($response->stateData->isWinOnBonus !== false) {
            $check = false;
        }
        if ($response->stateData->isWinOnFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isDropFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->isEndFeatureGame !== false) {
            $check = false;
        }
        if ($response->stateData->moveNumberInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->balanceData->balance !== $balance) {
            $check = false;
        }
        if ($response->balanceData->totalPayoff !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByLines !== 60) {
            $check = false;
        }
        if ($response->balanceData->payoffByBonus !== 0) {
            $check = false;
        }
        if ($response->balanceData->totalWinningsInFeatureGame !== 0) {
            $check = false;
        }

        if ($response->logicData->countOfMovesInFeatureGame !== 12) {
            $check = false;
        }
        if ($response->logicData->multiplier !== 1) {
            $check = false;
        }

        $this->assertTrue($check);
    }
}
