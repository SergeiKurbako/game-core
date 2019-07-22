<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 * Загрузка исходных данных связанных с балансом
 */
class BalanceWorkerLoadDataInstruction implements IInstruction
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
        IToolsPool $toolsPool
    ): IDataPool
    {
        if ($dataPool->systemData->isSimulation === false) { // не делается для симуляции или теста
            $dataPool->balanceData->balance = $toolsPool->dataTools->balanceDataTool->getUserBalance(
                $dataPool->requestData->userId,
                $dataPool->requestData->mode
            );
        }

        return $dataPool;
    }
}
