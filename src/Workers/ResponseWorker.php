<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Data\DataPool;

/**
 * Класс занимается генерацией ответа для фронта
 */
class ResponseWorker extends Worker
{
    /**
     * Генерация ответа для фронта
     *
     * @param IDataPool $dataPool
     *
     * @return string json-данные
     */
    public function makeResponse(IDataPool $dataPool): string
    {
        $responseData = new DataPool;

        // создание объекта для ответа с данными stateData
        $stateData = clone $dataPool->stateData;
        unset($stateData->isWinOnJackpot);
        unset($stateData->isWinOnDouble);
        unset($stateData->isDropJackpot);
        $responseData->addData('stateData', $stateData);

        // создание объекта для ответа с данными balanceData
        $balanceData = clone $dataPool->balanceData;
        unset($balanceData->payoffByJackpot);
        unset($balanceData->payoffByDouble);
        $responseData->addData('balanceData', $balanceData);

        // создание объекта для ответа с данными statisticsData
        //$responseData->addData('statisticsData', $dataPool->statisticsData);

        // создание объекта для ответа с данными sessionData
        $responseData->addData('sessionData', $dataPool->sessionData);

        // создание объекта для ответа с данными логики
        $logicData = clone $dataPool->logicData;
        unset($logicData->maxLineBet);
        unset($logicData->maxLinesInGame);
        unset($logicData->minLineBet);
        unset($logicData->minLinesInGame);
        unset($logicData->linesRules);
        unset($logicData->featureGameRules);
        unset($logicData->bonusRules);
        unset($logicData->combinationsRules);
        unset($logicData->jackpotRules);
        unset($logicData->percentagesRules);
        unset($logicData->payoffsForDouble);
        unset($logicData->payoffsForJackpot);
        //unset($logicData->countOfMovesInFeatureGame);
        unset($logicData->startCountOfFreeSpinsInFeatureGame);
        unset($logicData->startMultiplierInMainGame);
        unset($logicData->startMultiplierInFeatureGame);
        $responseData->addData('logicData', $logicData);

        return \json_encode($responseData);
    }
}
