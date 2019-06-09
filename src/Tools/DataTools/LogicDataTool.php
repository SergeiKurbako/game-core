<?php

namespace App\Classes\GameCore\Tools\DataTools;

use App\Classes\GameCore\Base\ITool;
use App\Classes\GameCore\Base\IData;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IRequestDataSet;
use App\Models\V2GameRule;

/**
 * помошник для работы с запросом с фронта
 */
class LogicDataTool implements ITool
{
    /**
     * Получение игровых правил
     *
     * @param int $gameId
     * @param string $name
     *
     * @return array
     */
    public function loadGameRules(int $gameId, string $name): array
    {
        $gameRules = [];

        $gameRulesDB = V2GameRule::where('game_id', $gameId)->where('name', $name)->first();

        $gameRules = \json_decode($gameRulesDB->rules);

        return $gameRules;
    }

    /**
     * Загрузка в объект с данными о логике данных переданных в запросе
     *
     * @param IData $logicData
     * @param IData $requestData
     *
     * @return IData
     */
    public function loadDataFromRequest(
        IData $logicData, IData $requestData
    ): IData
    {
        $logicData->lineBet = $requestData->lineBet;
        $logicData->linesInGame = $requestData->linesInGame;

        return $logicData;
    }
}
