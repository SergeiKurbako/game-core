<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Workers\Worker;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IDataPool;

class LogicWorker extends Worker
{
    /**
     * Загрузка данных связанных с логикой игры
     * Делается только при старте игры. Данные генерируются в независимости от
     * восстановления сессии
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function loadLogicData(IDataPool $dataPool, IToolsPool $toolsPool): IDataPool
    {
        // получение данных о линиях
        $dataPool->logicData->linesRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'lines');
        // получени данных о выигрышных комбинациях
        $dataPool->logicData->combinationsRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'winCombinations');
        // получени данных о выигрышных комбинациях на символах не зависящих от линий (бонусные символы)
        $dataPool->logicData->bonusRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'bonus');
        // получени правил выпадения featureGame
        $dataPool->logicData->featureGameRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'featureGame');
        // получени процентов выпадения символов
        $dataPool->logicData->percentagesRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'percentages');

        // получение процентов выпадения символов
        $currentPercentages = $toolsPool->logicTools->tableTool->getCurrentPercentages(
            $dataPool->logicData->percentagesRules,
            'mainGame',
            $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame
        );
        // генерация стола
        $dataPool->logicData->table = $toolsPool->logicTools->tableTool->getRandomTable(
            $currentPercentages
        );

        return $dataPool;
    }

    /**
     * Получение результатов выполнения логики для спина в основной игре
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function getResultOfSpin(
        IDataPool $dataPool, IToolsPool $toolsPool
    ): IDataPool
    {
        // изменение данных в logicData в соответствии с запросом
        $dataPool->logicData = $toolsPool->dataTools->logicDataTool->loadDataFromRequest(
            $dataPool->logicData,
            $dataPool->requestData
        );
        $dataPool->logicData->lineBet = $dataPool->requestData->lineBet;

        // получение процентов выпадения символов
        $currentPercentages = $toolsPool->logicTools->tableTool
            ->getCurrentPercentages(
                $dataPool->logicData->percentagesRules,
                $dataPool->stateData->screen,
                $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame
            );

        // получение рандомного занчения стола
        $table = $toolsPool->logicTools->tableTool->getRandomTable(
            $currentPercentages
        );

        //$table = [1,2,3,4,5,0,7,8,9,1,2,3,4,5,6]; // loose
        //$table = [2,1,3,5,1,6,7,8,9,4,2,3,4,5,6]; // win
        //$table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,2]; // featureGame + bonus
        //$table = [2,5,7,8,7,6,1,5,6,1,8,7,6,8,9];

        // получение выигрышных линий
        $winningLines = $toolsPool->logicTools->winLinesTool->getWinningLines(
            $table,
            $dataPool->logicData->linesRules,
            $dataPool->logicData->linesInGame
        );

        // получение выигрыша по линиям
        $payoffsForLines = $toolsPool->logicTools->winLinesTool->getPayoffsForLines(
            $dataPool->requestData->lineBet,
            $table,
            $winningLines,
            $dataPool->logicData->combinationsRules,
            $dataPool->logicData->linesRules
        );

        // получение выигрышных ячеек
        $winningCells = $toolsPool->logicTools->winLinesTool->getWinningCells(
            $table,
            $winningLines,
            $dataPool->logicData->linesRules
        );

        // получения выигрыша по бонусным символам
        $payoffsForBonus = $toolsPool->logicTools->bonusCalculatorTool->getBonusWinningsForMainGame(
            $table,
            $dataPool->logicData->bonusRules,
            $dataPool->logicData->linesInGame,
            $dataPool->logicData->lineBet
        );

        // запись данных в dataPool
        $dataPool->logicData->table = $table;
        $dataPool->logicData->winningLines = $winningLines;
        $dataPool->logicData->winningCells = $winningCells;
        $dataPool->logicData->payoffsForLines = $payoffsForLines;
        $dataPool->logicData->payoffsForBonus = $payoffsForBonus;

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
        // получение процентов выпадения символов
        $currentPercentages = $toolsPool->logicTools->tableTool
            ->getCurrentPercentages(
                $dataPool->logicData->percentagesRules,
                $dataPool->stateData->screen,
                $dataPool->logicData->lineBet * $dataPool->logicData->linesInGame
            );

        // получение рандомного занчения стола
        $table = $toolsPool->logicTools->tableTool->getRandomTable(
            $currentPercentages
        );

        //$table = [1,2,3,4,5,6,7,8,9,1,2,3,4,5,6]; // loose
        //$table = [2,1,3,5,1,6,7,8,9,4,2,3,4,5,6]; // win
        //$table = [2,1,3,5,0,6,7,8,9,4,2,3,4,5,6]; // win
        //$table = [10,2,3,4,5,6,7,8,9,10,2,3,10,5,2]; // featureGame + bonus

        // получение выигрышных линий
        $winningLines = $toolsPool->logicTools->winLinesTool->getWinningLines(
            $table,
            $dataPool->logicData->linesRules,
            $dataPool->logicData->linesInGame
        );

        // получение выигрыша по линиям
        $payoffsForLines = $toolsPool->logicTools->winLinesTool->getPayoffsForLines(
            $dataPool->requestData->lineBet,
            $table,
            $winningLines,
            $dataPool->logicData->combinationsRules,
            $dataPool->logicData->linesRules,
            $dataPool->logicData->multiplier
        );

        // получение выигрышных ячеек
        $winningCells = $toolsPool->logicTools->winLinesTool->getWinningCells(
            $table,
            $winningLines,
            $dataPool->logicData->linesRules
        );

        // получения выигрыша по бонусным символам
        $payoffsForBonus = $toolsPool->logicTools->bonusCalculatorTool->getBonusWinningsForFeatureGame(
            $table,
            $dataPool->logicData->bonusRules,
            $dataPool->logicData->linesInGame,
            $dataPool->logicData->lineBet,
            $dataPool->logicData->multiplier
        );

        // запись данных в dataPool
        $dataPool->logicData->table = $table;
        $dataPool->logicData->winningLines = $winningLines;
        $dataPool->logicData->winningCells = $winningCells;
        $dataPool->logicData->payoffsForLines = $payoffsForLines;
        $dataPool->logicData->payoffsForBonus = $payoffsForBonus;

        return $dataPool;
    }
}
