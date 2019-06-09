<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Workers\Worker;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IDataPool;

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

        if ($dataPool->stateData->isDropFeatureGame) {
            // Обнуление кол-ва возможных бесплатных спинов
            $dataPool->stateData->totalSpinCountOnFeatureGame = $toolsPool->stateTools->stateCalculatorTool
                ->resetTotalSpinCountOnFeatureGame(
                    $dataPool->logicData->startCountOfFreeSpinsInFeatureGame
                );

            // изменение текущего номера хода в featureGame
            $dataPool->stateData->moveNumberInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
                ->resetMoveNumberInFeatureGame();

            // изменение множителя
            $dataPool->logicData->multiplier = $toolsPool->stateTools->stateCalculatorTool
                ->resetMultiplier();

            // изменение текущего экрана
            $dataPool->stateData->screen = $toolsPool->stateTools->stateCalculatorTool
                ->updateScreenIfDropFeatureGame();
        }

        // выпадение джекпота
        $dataPool->stateData->isDropJackpot = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropJackpot(
                $dataPool->logicData->jackpotRules
            );

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
            return $this->getResultOfSpin($dataPool, $toolsPool);
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

        // изменение текущего номера хода в featureGame
        $dataPool->stateData->moveNumberInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateMoveNumberInFeatureGame(
                $dataPool->stateData->moveNumberInFeatureGame
            );

        // изменение кол-ва возможных ходов с учетом выпадения новой featureGame
        $dataPool->logicData->countOfMovesInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->updateCountOfMovesInFeatureGame(
                $dataPool->logicData->countOfMovesInFeatureGame,
                $dataPool->stateData->isDropFeatureGame
            );

        // изменение множителя в случает выпадения алмаза
        $dataPool->logicData->multiplier = $toolsPool->stateTools->stateCalculatorTool
            ->calculateMultiplier(
                $dataPool->logicData->multiplier,
                $dataPool->logicData->table
            );

        // обнуление множителя в случае окончания featureGame
        $dataPool->logicData->multiplier = $toolsPool->stateTools->stateCalculatorTool
            ->nullableMultiplierIfEndFeatureGame(
                $dataPool->logicData->multiplier,
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        // обнуление экрана если бесплатные спины закончились
        $dataPool->stateData->screen = $toolsPool->stateTools->stateCalculatorTool
            ->nullableScreenIfEndFeatureGame(
                $dataPool->stateData->screen,
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        // обнуление кол-ва возможных бесплатных спинов если они закончились
        $dataPool->logicData->countOfMovesInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->nullableCountOfMovesIfEndFeatureGame(
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        // обнуление кол-ва сделанных бесплатных спинов если они закончились
        $dataPool->stateData->moveNumberInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->nullableMoveNumbersIfEndFeatureGame(
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        // обнуление общего выигрыша в фриспинах если они закончились
        $dataPool->balanceData->totalWinningsInFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->nullableTotalWinningsFGIfEndFeatureGame(
                $dataPool->balanceData->totalWinningsInFeatureGame,
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        return $dataPool;
    }
}
