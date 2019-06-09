<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Workers\Worker;

class RequestWorker extends Worker
{
    /**
     * Загрузка данных
     * Set данных, который будет загружаться определяется параметром параметром
     * action.
     *
     * @param array $requestArray
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     * @param IRequestDataSets $requestDataSets
     *
     * @return IDataPool
     */
    public function loadRequestData(
        array $requestArray,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IRequestDataSets $requestDataSets
    ): IDataPool
    {
        // загрузка данных получаемых в запросе
        $requestDataSetName = $requestArray['action'];
        $dataPool->requestData = $toolsPool->dataTools->requestDataTool->loadData(
            $dataPool->requestData,
            $requestDataSets->$requestDataSetName,
            $requestArray
        );

        return $dataPool;
    }
}
