<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Получение результатов хода в featureGame
 */
class BalanceWorkerFreeSpinInstruction implements IInstruction
{
    /**
     * Проверка может ли пользователь сделать кручение
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function checkIsPossibilitySpin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $isPossibilitySpin = true;

        if ($dataPool->stateData->screen === 'featureGame') {
            $isPossibilitySpin = true;
        } else {
            //dd(__METHOD__, 'нет оставшихся фриспинов');
        }

        if ($isPossibilitySpin === false) {
            //dd(__METHOD__, 'не достаточный баланс');
        }

        return $dataPool;
    }

    /**
     * Подсчет кол-ва выигрыша по линиям
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPayoffByLines(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->payoffByLines = $toolsPool->balanceTools->payoffCalculatorTool->getPayoffByLines($dataPool->logicData->payoffsForLines);

        return $dataPool;
    }

    /**
     * Подсчет выигрыша за счет бонусных символов
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
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
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalPayoff(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->totalPayoff = $dataPool->balanceData->payoffByLines + $dataPool->balanceData->payoffByBonus;

        return $dataPool;
    }

    /**
     * Получение баланса
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getBalance(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->balance = $dataPool->balanceData->balance + $dataPool->balanceData->totalPayoff;

        return $dataPool;
    }

    /**
     * Получение общего выигрыша за текущую featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getTotalWinningsInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->balanceData->totalWinningsInFeatureGame += $dataPool->balanceData->totalPayoff;

        return $dataPool;
    }

    /**
     * изменение баланса в БД
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
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
