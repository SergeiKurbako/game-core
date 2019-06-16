<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Workers\Worker;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IDataPool;

class BalanceWorker extends Worker
{
    /**
     * Загрузка исходных данных связанных с балансом
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function loadBalanceData(
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        bool $simulation = false
    ): IDataPool
    {
        if ($simulation === false) { // не делается для симуляции или теста
            $dataPool->balanceData->balance = $toolsPool->dataTools->balanceDataTool->getUserBalance(
                $dataPool->requestData->userId,
                $dataPool->requestData->mode
            );
        }

        return $dataPool;
    }

    /**
     * Получение результатов хода в основной игре
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getResultOfSpin(IDataPool $dataPool, IToolsPool $toolsPool, bool $simulation = false): IDataPool
    {
        // проврка может ли пользователь сделать кручение
        $isPossibilitySpin = $toolsPool->balanceTools->possibilityСhecker
            ->checkIsPossibilitySpin(
                $dataPool->balanceData->balance,
                $dataPool->logicData->lineBet,
                $dataPool->logicData->linesInGame,
                $dataPool->stateData->screen
            );

        if ($isPossibilitySpin === false) {
            die(json_encode(["status" => "false", "message" => "low balance"]));
        }

        // подсчет кол-ва выигрыша по линиям
        $payoffByLines = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByLines(
            $dataPool->logicData->payoffsForLines
        );

        // подсчет выигрыша за счет бонусных символов
        $payoffByBonus = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByBonus(
            $dataPool->logicData->payoffsForBonus
        );

        // общий выигрышь
        $totalPayoff = $payoffByLines + $payoffByBonus;

        // общая ставка
        $bet = $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame;

        // запись данных в dataPool
        $dataPool->balanceData->balance = $dataPool->balanceData->balance + $totalPayoff - $bet;
        $dataPool->balanceData->totalPayoff = $totalPayoff;
        $dataPool->balanceData->payoffByLines = $payoffByLines;
        $dataPool->balanceData->payoffByBonus = $payoffByBonus;
        $dataPool->balanceData->totalWinningsInFeatureGame = 0;

        // изменение баланса в БД
        if ($simulation === false) {
            $toolsPool->dataTools->balanceDataTool->updateUserBalance(
                $dataPool->balanceData->balance,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }

    /**
     * Получение результатов хода в featureGame
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getResultOfFreeSpin(IDataPool $dataPool, IToolsPool $toolsPool, bool $simulation = false): IDataPool
    {
        // проврка может ли пользователь сделать кручение
        if ($dataPool->stateData->screen === 'featureGame') {
            $isPossibilitySpin = true;
        } else {
            dd(__METHOD__, 'нет оставшихся фриспинов');
        }

        if ($isPossibilitySpin === false) {
            dd(__METHOD__, 'не достаточный баланс');
        }

        // подсчет кол-ва выигрыша по линиям
        $payoffByLines = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByLines($dataPool->logicData->payoffsForLines);

        // подсчет выигрыша за счет бонусных символов
        $payoffByBonus = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByBonus(
            $dataPool->logicData->payoffsForBonus
        );

        // общий выигрышь
        $totalPayoff = $payoffByLines + $payoffByBonus;

        // запись данных в dataPool
        $dataPool->balanceData->balance = $dataPool->balanceData->balance + $totalPayoff;
        $dataPool->balanceData->totalPayoff = $totalPayoff;
        $dataPool->balanceData->payoffByLines = $payoffByLines;
        $dataPool->balanceData->payoffByBonus = $payoffByBonus;
        $dataPool->balanceData->totalWinningsInFeatureGame += $totalPayoff;

        // изменение баланса в БД
        if ($simulation === false) {
            $toolsPool->dataTools->balanceDataTool->updateUserBalance(
                $dataPool->balanceData->balance,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }
}
