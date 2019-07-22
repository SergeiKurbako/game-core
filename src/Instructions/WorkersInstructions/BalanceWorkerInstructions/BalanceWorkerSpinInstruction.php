<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Получение состояния после совершения хода в основной игре.
 * Класс содержащий набор методов, которые последовательно выполняются в воркером.
 */
class BalanceWorkerSpinInstruction implements IInstruction
{
    /**
     * Проврка может ли пользователь сделать кручение
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function checkIsPossibilitySpin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
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

        return $dataPool;
    }

    /**
     * Подсчет кол-ва выигрыша по линиям
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getPayoffByLines(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->payoffByLines = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByLines(
            $dataPool->logicData->payoffsForLines
        );

        return $dataPool;
    }

    /**
     * Подсчет выигрыша за счет бонусных символов
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getPayoffByBonus(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->payoffByBonus = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByBonus(
            $dataPool->logicData->payoffsForBonus
        );

        return $dataPool;
    }

    /**
     * Общий выигрышь
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getTotalPayoff(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->totalPayoff = $dataPool->balanceData->payoffByLines + $dataPool->balanceData->payoffByBonus;

        return $dataPool;
    }

    /**
     * Запись данных в dataPool
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getBalance(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $bet = $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame;
        $dataPool->balanceData->balance = $dataPool->balanceData->balance + $dataPool->balanceData->totalPayoff - $bet;

        return $dataPool;
    }

    /**
     * Выигрышь в featureGame
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getTotalWinningsInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->totalWinningsInFeatureGame = 0;

        return $dataPool;
    }

    /**
     * Обновление данных в БД
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     */
    public function updateUserBalance(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->systemData->isSimulation === false) {
            $toolsPool->dataTools->balanceDataTool->updateUserBalance(
                $dataPool->balanceData->balance,
                $dataPool->sessionData->userId,
                $dataPool->sessionData->mode
            );
        }

        return $dataPool;
    }
}
