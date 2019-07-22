<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\StateWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Получение состояния после совершения хода в основной игре.
 * Класс содержащий набор методов, которые последовательно выполняются в воркером.
 */
class StateWorkerSpinInstruction implements IInstruction
{
    /**
     * Выигрышь на чем либо
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
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
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getIsWinOnMain(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWinOnMain = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsWinOnMain(
                $dataPool->logicData->payoffsForLines,
                $dataPool->logicData->payoffsForBonus,
                $dataPool->logicData->payoffsForJackpot
            );

        return $dataPool;
    }

    /**
     * Выигрышь в featureGame
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function updateIsWinOnFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isWinOnFeatureGame = false;

        return $dataPool;
    }

    /**
     * Выигрышь на бонусных символах
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
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
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
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
     * Изменение экрана
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function updateScreen(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->stateData->isDropFeatureGame) {
            $dataPool->stateData->screen = 'featureGame';
        }

        return $dataPool;
    }

    /**
     * Изменение предыдущего экрана
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function updatePrevScreen(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->prevScreen = 'mainGame';

        return $dataPool;
    }

    /**
     * Выпадение джекпота
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getIsDropJackpot(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isDropJackpot = $toolsPool->stateTools->stateCalculatorTool
            ->calculateIsDropJackpot(
                $dataPool->logicData->jackpotRules
            );

        return $dataPool;
    }


    /**
     * Окончание фриспинов
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getIsEndFeatureGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->stateData->isEndFeatureGame = false;

        return $dataPool;
    }
}
