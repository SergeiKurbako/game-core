<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Workers\Worker;

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
    ): IDataPool {
        // загрузка данных получаемых в запросе
        $requestDataSetName = $requestArray['action'];
        $dataPool->requestData = $toolsPool->dataTools->requestDataTool->loadData(
            $dataPool->requestData,
            $requestDataSets->$requestDataSetName,
            $requestArray
        );

        return $dataPool;
    }

    /**
     * Метод отправляющий уведомления о событиях
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    protected function sendNotifies(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        return $dataPool;
    }
}
