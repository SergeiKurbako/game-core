<?php

namespace Avior\GameCore\Tools\DataTools;

use Avior\GameCore\Base\ITool;
use Avior\GameCore\Base\IData;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2UserStatistic;
use App\Models\V2GameStatistic;

/**
 * помошник для работы с запросом с фронта
 */
class StatisticsDataTool implements ITool
{
    /**
     * Получение из БД статистики пользователя по игре
     *
     * @param  IData  $statisticsData [description]
     * @param  int    $userId         [description]
     * @param  int    $gameId         [description]
     * @param  string $mode           [description]
     *
     * @return IData                  [description]
     */
    public function getUserStatistics(
        IData $statisticsData,
        int $userId,
        int $gameId,
        string $mode
    ): IData {
        // получение статистики из бд
        $statisticsCollection = V2UserStatistic::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        if ($statisticsCollection === null) {
            // создание новой статистики
            $statisticsCollection = new V2UserStatistic;
            $statisticsCollection->user_id = $userId;
            $statisticsCollection->game_id = $gameId;
            $statisticsCollection->mode = $mode;
            $statisticsCollection->statistics = \json_encode($statisticsData);
            $statisticsCollection->save();
        } else {
            // загрузка статистики из бд в объект
            foreach (json_decode($statisticsCollection->statistics) as $key => $value) {
                $statisticsData->$key = $value;
            }
        }

        return $statisticsData;
    }

    /**
     * Получение из БД статистики игры
     *
     * @param  IData  $statisticsData [description]
     * @param  int    $gameId         [description]
     * @param  string $mode           [description]
     *
     * @return IData                  [description]
     */
    public function getGameStatistics(
        IData $statisticsData,
        int $gameId,
        string $mode
    ): IData {
        // получение статистики из бд
        $statisticsCollection = V2GameStatistic::where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        if ($statisticsCollection === null) {
            // создание новой статистики
            $statisticsCollection = new V2GameStatistic;
            $statisticsCollection->game_id = $gameId;
            $statisticsCollection->mode = $mode;
            $statisticsCollection->statistics = \json_encode($statisticsData);
            $statisticsCollection->save();
        } else {
            // загрузка статистики из бд в объект
            foreach (json_decode($statisticsCollection->statistics) as $key => $value) {
                $statisticsData->$key = $value;
            }
        }

        return $statisticsData;
    }

    /**
     * Обновление статистики пользователя по данной игре
     *
     * @param IData $statisticsData
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return void
     */
    public function saveUserStatistics(IData $statisticsData, int $userId, int $gameId, string $mode): void
    {
        // получение статистики для данной игры с данныи режимом из БД
        $statisticsCollection = V2UserStatistic::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        // обновление статистики
        $statisticsCollection->statistics = json_encode($statisticsData);
        $statisticsCollection->save();
    }

    /**
     * Сохранение обновление статистики для данной игры
     *
     * @param IData $statisticsData
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return void
     */
    public function saveGameStatistics(IData $statisticsData, int $gameId, string $mode): void
    {
        // получение статистики для данной игры с данныи режимом из БД
        $statisticsCollection = V2GameStatistic::where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        // обновление статистики
        $statisticsCollection->statistics = json_encode($statisticsData);
        $statisticsCollection->save();
    }
}
