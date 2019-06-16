<?php

namespace Tests\Games\LifeOfLuxury2\Tools\StateTools;

use Tests\TestCase;
use App\Classes\GameCore\Workers\VerifierWorker;
use App\Classes\Games\LifeOfLuxury2\Data\LogicData;

class VerifierWorkerTest extends TestCase
{
    /**
     * Проверка выпадения verificationSpinRequest
     * Условия: делается запрос с ставкой < 400
     * Результат: запрос проходит верификацию
     */
    public function testVerificationSpinRequest1()
    {
        $check = false;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        $verifierWorker = new VerifierWorker();
        $logicData = new LogicData;

        $dataPool->logicData->maxLineBet = $logicData->maxLineBet;
        $dataPool->logicData->maxLinesInGame = $logicData->maxLinesInGame;
        $dataPool->requestData->linesInGame = 20;
        $dataPool->requestData->lineBet = 20;

        $result = $verifierWorker->verificationSpinRequest(
            $dataPool,
            $toolsPool
        );

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выпадения verificationFreeSpinRequest
     * Условия: делается запрос с ставкой < 400
     * Результат: запрос проходит верификацию
     */
    public function testVerificationFreeSpinRequest1()
    {
        $check = false;

        $game = $this->getGame();
        $dataPool = $game->dataPool;
        $workersPool = $game->workersPool;
        $toolsPool = $game->toolsPool;

        $verifierWorker = new VerifierWorker();
        $logicData = new LogicData;

        $dataPool->logicData->maxLineBet = $logicData->maxLineBet;
        $dataPool->logicData->maxLinesInGame = $logicData->maxLinesInGame;
        $dataPool->requestData->linesInGame = 20;
        $dataPool->requestData->lineBet = 20;

        $result = $verifierWorker->verificationFreeSpinRequest(
            $dataPool,
            $toolsPool
        );

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
