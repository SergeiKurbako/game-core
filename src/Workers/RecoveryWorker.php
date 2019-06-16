<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Base\IData;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;

class RecoveryWorker extends Worker
{
    /**
     * Восстановление состояния с предыдущего хода
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function recoveryData(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // получение sessionUuid
        if ($dataPool->requestData->sessionUuid === '') {
            $sessionUuid = $dataPool->sessionData->sessionUuid;
        } else {
            $sessionUuid = $dataPool->requestData->sessionUuid;
        }

        // получение данных с предыдущего хода
        $prevDataPool = $toolsPool->dataTools->recoveryDataTool
            ->getPrevDataPool($sessionUuid);

        // при наличии сохраненных данных с предыдущего хода делается востановление
        if ($prevDataPool->sessionData !== null) {
            // востановление данных
            $dataPool->sessionData = $toolsPool->dataTools->recoveryDataTool
                ->recoveryData($dataPool->sessionData, $prevDataPool->sessionData);

            $dataPool->balanceData = $toolsPool->dataTools->recoveryDataTool
               ->recoveryData($dataPool->balanceData, $prevDataPool->balanceData);

            $dataPool->logicData = $toolsPool->dataTools->recoveryDataTool
                ->recoveryData($dataPool->logicData, $prevDataPool->logicData);

            $dataPool->stateData = $toolsPool->dataTools->recoveryDataTool
                ->recoveryData($dataPool->stateData, $prevDataPool->stateData);

            $dataPool->statisticsData = $toolsPool->dataTools->recoveryDataTool
                ->recoveryData($dataPool->statisticsData, $prevDataPool->statisticsData);
        }

        return $dataPool;
    }

    /**
     * Сохранение данных для последующего востановления
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return void
     */
    public function saveRecoveryData(IDataPool $dataPool, IToolsPool $toolsPool): void
    {
        $toolsPool->dataTools->recoveryDataTool->saveRecoveryData($dataPool);
    }
}
