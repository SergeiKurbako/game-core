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
        $responseData = new \stdClass;

        // создание объекта для ответа с данными stateData
        $stateData = clone $dataPool->stateData;
        unset($stateData->isWinOnJackpot);
        unset($stateData->isWinOnDouble);
        unset($stateData->isDropJackpot);
        //$responseData->addData('stateData', $stateData);
        $responseData->stateData = $stateData;

        // создание объекта для ответа с данными balanceData
        $balanceData = clone $dataPool->balanceData;
        unset($balanceData->payoffByJackpot);
        unset($balanceData->payoffByDouble);
        //$responseData->addData('balanceData', $balanceData);
        $responseData->balanceData = $balanceData;

        // создание объекта для ответа с данными statisticsData
        //$responseData->addData('statisticsData', $dataPool->statisticsData);

        // создание объекта для ответа с данными sessionData
        //$responseData->addData('sessionData', $dataPool->sessionData);
        $responseData->sessionData = $dataPool->sessionData;

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
        $responseData->logicData = $logicData;

        // создание объекта для ответа с данными longData
        if ($dataPool->stateData->isEndFeatureGame) {
            $longData = new \stdClass;

            $longData->stateData = new \stdClass;
            $longData->stateData = $dataPool->longData->data->stateData;
            unset($longData->stateData->isWinOnJackpot);
            unset($longData->stateData->isWinOnDouble);
            unset($longData->stateData->isDropJackpot);

            $longData->balanceData = new \stdClass;
            $longData->balanceData = $dataPool->longData->data->balanceData;
            unset($longData->balanceData->payoffByJackpot);
            unset($longData->balanceData->payoffByDouble);

            $longData->logicData = new \stdClass;
            $longData->logicData = $dataPool->longData->data->logicData;
            unset($longData->logicData->maxLineBet);
            unset($longData->logicData->maxLinesInGame);
            unset($longData->logicData->minLineBet);
            unset($longData->logicData->minLinesInGame);
            unset($longData->logicData->linesRules);
            unset($longData->logicData->featureGameRules);
            unset($longData->logicData->bonusRules);
            unset($longData->logicData->combinationsRules);
            unset($longData->logicData->jackpotRules);
            unset($longData->logicData->percentagesRules);
            unset($longData->logicData->payoffsForDouble);
            unset($longData->logicData->payoffsForJackpot);
            unset($longData->logicData->startCountOfFreeSpinsInFeatureGame);
            unset($longData->logicData->startMultiplierInMainGame);
            unset($longData->logicData->startMultiplierInFeatureGame);

            $responseData->longData = $longData;
        }

        return \json_encode($responseData);
    }
}
