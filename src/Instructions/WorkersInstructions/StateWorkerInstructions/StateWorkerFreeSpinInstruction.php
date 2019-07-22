<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\StateWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Получение результатов хода в featureGame
 */
class StateWorkerFreeSpinInstruction implements IInstruction
{
    /**
     * Перенаправление запроса в случае если это не фриспин
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function updateScreen(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->stateData->screen === 'mainGame') {
            dd(__METHOD__, 'нет фриспинов');
            //return $this->getResultOfSpin($dataPool, $toolsPool);
        }

        return $dataPool;
    }

    /**
     * Изменение предыдущего экрана
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function updatePrevScreen(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->prevScreen = 'featureGame';

        return $dataPool;
    }

    /**
     * Выигрышь на чем либо
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getIsWin(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWin = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWin(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->payoffsForDouble,
                $dataPool->logicData->payoffsForJackpot
            );

        return $dataPool;
    }

    /**
     * Выигрышь в основной игре
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function updateIsWinOnMain(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWinOnMain = false;

        return $dataPool;
    }

    /**
     * Выигрышь в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getIsWinOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWinOnFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnFeatureGame(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus
            );

        return $dataPool;
    }

    /**
     * Выигрышь на бонусных символах
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getIsWinOnBonus(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWinOnBonus = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnBonus(
                $dataPool->logicData->payoffsForBonus
            );

        return $dataPool;
    }

    /**
     * Выпадение featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getIsDropFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isDropFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropFeatureGame(
                $dataPool->stateData->screen,
                $dataPool->logicData->table,
                $dataPool->logicData->featureGameRules
            );

        return $dataPool;
    }

    /**
     * Изменение текущего номера хода в featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function updateMoveNumberInFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->moveNumberInFeatureGame += 1;

        return $dataPool;
    }

    /**
     * Окончание featureGame (последний бесплатный спин)
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getIsEndFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isEndFeatureGame = $toolsPool->stateTools->stateCalculatorTool
            ->checkEndFeatureGame(
                $dataPool->stateData->moveNumberInFeatureGame,
                $dataPool->logicData->countOfMovesInFeatureGame
            );

        return $dataPool;
    }
}
