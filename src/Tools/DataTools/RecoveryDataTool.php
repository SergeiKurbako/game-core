<?php

namespace App\Classes\GameCore\Tools\DataTools;

use App\Classes\GameCore\Base\ITool;
use App\Classes\GameCore\Base\IData;
use App\Classes\GameCore\Base\IDataPool;
use App\Models\V2RecoveryData;
use App\Models\V2Session;

/**
 * помошник для работы с запросом с фронта
 */
class RecoveryDataTool implements ITool
{
    /**
     * Сохранение данных игры для последующего востановления
     *
     * @param IDataPool $dataPool
     *
     * @return void
     */
    public function saveRecoveryData(IDataPool $dataPool): void
    {
        $recoveryData = new V2RecoveryData;
        $recoveryData->session_id = V2Session::where('session_uuid', $dataPool->sessionData->sessionUuid)->get()->first()->id;
        $recoveryData->recovery_data = json_encode($dataPool);
        $recoveryData->save();
    }

    /**
     * Получение объекта с данными с предыдущего хода
     *
     * @param string $sessionUuid
     *
     * @return object
     */
    public function getPrevDataPool(string $sessionUuid): object
    {
        $sessionId = V2Session::where('session_uuid', $sessionUuid)->first()->id;
        $recoveryData = V2RecoveryData::where('session_id', $sessionId)->get()->last();

        if ($recoveryData === null) {
            $recoveryData = new V2RecoveryData;
        }

        if ($recoveryData->recovery_data !== null) {
            $prevDataPool = \json_decode($recoveryData->recovery_data);
        } else {
            $prevDataPool = \json_decode('{"sessionData": null}');
        }

        return $prevDataPool;
    }

    /**
     * Преобразование данных полученных из json к определенному типом данных
     *
     * @param IData $sessionData
     *
     * @return IData
     */
    public function recoveryData(IData $data, object $recoveryData): IData
    {
        foreach ($data as $key => $value) {
            $data->$key = $recoveryData->$key;
        }

        return $data;
    }

}
