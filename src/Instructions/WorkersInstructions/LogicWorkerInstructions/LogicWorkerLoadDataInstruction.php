<?php

namespace Avior\GameCore\Instructions\WorkersInstructions\LogicWorkerInstructions;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс содержащий набор методов, которые последовательно выполняются в воркером
 * Загрузка данных связанных с логикой игры.
 * Делается только при старте игры. Данные генерируются в независимости от
 * восстановления сессии
 */
class LogicWorkerLoadDataInstruction implements IInstruction
{
    /**
     * Получение данных о линиях
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadLinesRules(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->linesRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'lines');

        return $dataPool;
    }

    /**
     * получени данных о выигрышных комбинациях
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadCombinationsRules(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->combinationsRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'winCombinations');

        return $dataPool;
    }

    /**
     * Получение данных о выигрышных комбинациях на символах не зависящих от линий (бонусные символы)
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadBonusRules(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->bonusRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'bonus');

        return $dataPool;
    }

    /**
     * Получение правил выпадения featureGame
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadFeatureGameRules(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->featureGameRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'featureGame');

        return $dataPool;
    }

    /**
     * Получени процентов выпадения символов
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function loadPercentagesRules(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $dataPool->logicData->percentagesRules = $toolsPool->dataTools->logicDataTool->loadGameRules($dataPool->sessionData->gameId, 'percentages');

        return $dataPool;
    }

    /**
     * генерация стола
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function getRandomTable(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // Получение процентов выпадения символов
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


}
