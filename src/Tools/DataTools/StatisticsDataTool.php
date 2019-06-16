<?php

namespace Avior\GameCore\Tools\DataTools;

use Avior\GameCore\Base\ITool;
use Avior\GameCore\Base\IData;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2Statistic;

/**
 * помошник для работы с запросом с фронта
 */
class StatisticsDataTool implements ITool
{
    public function getUserStatistics(IData $statisticsData, int $userId, int $gameId, string $mode): IData
    {
        // получение статистики из бд
        $statisticsCollection = V2Statistic::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        if ($statisticsCollection === null) {
            // создание новой статистики
            $statisticsCollection = new V2Statistic;
            $statisticsCollection->user_id = $userId;
            $statisticsCollection->game_id = $gameId;
            $statisticsCollection->mode = $mode;
            $statisticsCollection->statistics = \json_encode($statisticsData);
            $statisticsCollection->save();
        } else {
            // загрузка статистики из бд в объект
            foreach (\json_decode($statisticsCollection->statistics) as $key => $value) {
                $statisticsData->$key = $value;
            }
        }

        return $statisticsData;
    }

    /**
     * Сохранение статистики в БД
     *
     * @param IData $statisticsData
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return void
     */
    public function saveStatistics(IData $statisticsData, int $userId, int $gameId, string $mode): void
    {
        $statisticsCollection = V2Statistic::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();
    }
}
