<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\LogicWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняется в воркером
 */
class LogicWorkerSpinInstruction implements IInstruction
{
    /**
     * изменение данных в logicData в соответствии с запросом
     *
     * @param  IDataPool  $dataPool
     * @param  IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function loadDataFromRequest(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData = $toolsPool->dataTools->logicDataTool->loadDataFromRequest(
            $dataPool->logicData,
            $dataPool->requestData
        );

        $dataPool->logicData->lineBet = $dataPool->requestData->lineBet;

        return $dataPool;
    }

    /**
     * получение рандомного занчения стола
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getCurrentPercentages(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        if ($dataPool->systemData->tablePreset === []) {
            // получение процентов выпадения символов
            $currentPercentages = $toolsPool->logicTools->tableTool
            ->getCurrentPercentages(
                $dataPool->logicData->percentagesRules,
                $dataPool->stateData->screen,
                $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame
            );

            $dataPool->logicData->table = $toolsPool->logicTools->tableTool->getRandomTable(
                $currentPercentages
            );
        } else {
            $dataPool->logicData->table = $dataPool->systemData->tablePreset;
        }

        return $dataPool;
    }

    /**
     * получение выигрышных линий
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinningLines(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->winningLines = $toolsPool->logicTools->winLinesTool->getWinningLines(
            $dataPool->logicData->table,
            $dataPool->logicData->linesRules,
            $dataPool->logicData->linesInGame
        );

        return $dataPool;
    }

    /**
     * получение выигрыша по линиям
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getPayoffsForLines(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->payoffsForLines = $toolsPool->logicTools->winLinesTool->getPayoffsForLines(
            $dataPool->requestData->lineBet,
            $dataPool->logicData->table,
            $dataPool->logicData->winningLines,
            $dataPool->logicData->combinationsRules,
            $dataPool->logicData->linesRules
        );

        return $dataPool;
    }

    /**
     * получение выигрышных ячеек
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getWinningCells(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->winningCells = $toolsPool->logicTools->winLinesTool->getWinningCells(
            $dataPool->logicData->table,
            $dataPool->logicData->winningLines,
            $dataPool->logicData->linesRules
        );

        return $dataPool;
    }

    /**
     * получения выигрыша по бонусным символам
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getBonusWinningsForMainGame(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->payoffsForBonus = $toolsPool->logicTools->bonusCalculatorTool->getBonusWinningsForMainGame(
            $dataPool->logicData->table,
            $dataPool->logicData->bonusRules,
            $dataPool->logicData->linesInGame,
            $dataPool->logicData->lineBet
        );

        return $dataPool;
    }
}