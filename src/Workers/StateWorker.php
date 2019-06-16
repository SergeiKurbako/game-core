<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Workers\Worker;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Events\GameEvents\StartFeatureGameEvent;
use App\Classes\GameCore\Events\GameEvents\EndFeatureGameEvent;

class StateWorker extends Worker
{
    /**
     * Загрузка данных исходного состояния
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function loadStateData(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        $dataPool->stateData->screen = 'mainGame';

        return $dataPool;
    }

    /**
     * Получение состояния после совершения хода в основной игре
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getResultOfSpin(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // выигрышь на чем либо
        $dataPool->stateData->isWin = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWin(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->payoffsForDouble,
                $dataPool->logicData->payoffsForJackpot
            );

        // выигрышь в основной игре
        $dataPool->stateData->isWinOnMain = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnMain(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->payoffsForJackpot
            );

        // выигрышь в featureGame
        $dataPool->stateData->isWinOnFeatureGame = false;

        // выигрыш на бонусных символах
        $dataPool->stateData->isWinOnBonus = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnBonus(
                $dataPool->logicData->payoffsForBonus
            );

        // выпадение featureGame
        $dataPool->stateData->isDropFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropFeatureGame(
                $dataPool->stateData->screen,
                $dataPool->logicData->table,
                $dataPool->logicData->featureGameRules
            );

        // изменение экрана
        if ($dataPool->stateData->isDropFeatureGame) {
            $dataPool->stateData->screen = 'featureGame';
        }

        // выпадение джекпота
        $dataPool->stateData->isDropJackpot = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropJackpot(
                $dataPool->logicData->jackpotRules
            );

        // окончание фриспинов
        $dataPool->stateData->isEndFeatureGame = false;

        // отправка уведомлений о событиях
        $dataPool = $this->sendNotifies($dataPool, $toolsPool);

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
    public function getResultOfFreeSpin(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // перенаправление запроса в случае если это не фриспин
        if ($dataPool->stateData->screen === 'mainGame') {
            dd(__METHOD__, 'нет фриспинов');
            //return $this->getResultOfSpin($dataPool, $toolsPool);
        }

        // выигрышь на чем либо
        $dataPool->stateData->isWin = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWin(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->payoffsForDouble,
                $dataPool->logicData->payoffsForJackpot
            );

        // выигрышь в основной игре
        $dataPool->stateData->isWinOnMain = false;

        // выигрышь в featureGame
        $dataPool->stateData->isWinOnFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnFeatureGame(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        // выигрышь на бонусных символах
        $dataPool->stateData->isWinOnBonus = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnBonus(
                $dataPool->logicData->payoffsForBonus
            );

        // выпадение featureGame
        $dataPool->stateData->isDropFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropFeatureGame(
                $dataPool->stateData->screen,
                $dataPool->logicData->table,
                $dataPool->logicData->featureGameRules
            );

        // изменение текущего номера хода в featureGame
        $dataPool->stateData->moveNumberInFeatureGame += 1;

        // окончание featureGame (последний бесплатный спин)
        $dataPool->stateData->isEndFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->checkEndFeatureGame(
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        // отправка уведомлений о событиях
        $dataPool = $this->sendNotifies($dataPool, $toolsPool);

        return $dataPool;
    }

    public function sendNotifies(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // оповещение о выпадении featureGame
        if ($dataPool->stateData->isDropFeatureGame) {
            $dataPool = $this->notify(new StartFeatureGameEvent($dataPool, $toolsPool));
        }

        // оповещение об окончании featureGame
        if ($dataPool->stateData->isEndFeatureGame) {
            $dataPool = $this->notify(new EndFeatureGameEvent($dataPool, $toolsPool));
        }

        return $dataPool;
    }
}
