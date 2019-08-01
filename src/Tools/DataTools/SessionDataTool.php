<?php

namespace Avior\GameCore\Tools\DataTools;

use Avior\GameCore\Base\ITool;
use App\Models\V2Session;
use Webpatser\Uuid\Uuid;
use Avior\GameCore\Base\IDataPool;

/**
 * Помошник по работе с данными сессии
 */
class SessionDataTool implements ITool
{
    /**
     * Проверка наличия работающей сессии у пользователя
     *
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return bool
     */
    public function checkWorkingSession(int $userId, int $gameId, string $mode): bool
    {
        $session = V2Session::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->where('status', 'work')
            ->first();

        $check = true;
        if ($session === null) {
            $check = false;
        }

        return $check;
    }

    /**
     * Создание новой сессии
     *
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return V2Session
     */
    public function makeNewSession(int $userId, int $gameId, string $mode): V2Session
    {
        $newSession = new V2Session;
        $newSession->user_id = $userId;
        $newSession->game_id = $gameId;
        $newSession->mode = $mode;
        $newSession->session_uuid = Uuid::generate()->string;
        $newSession->status = 'work';
        $newSession->save();

        return $newSession;
    }

    /**
     * Получение данных работающей сессии для данной игры
     *
     * @param int $userId
     * @param int $gameId
     * @param string $mode
     *
     * @return V2Session
     */
    public function getWorkingSession(int $userId, int $gameId, string $mode): V2Session
    {
        $session = V2Session::where('user_id', $userId)
            ->where('game_id', $gameId)
            ->where('mode', $mode)
            ->first();

        return $session;
    }

    /**
     * Закрытие пользовательской сессии
     *
     * @param string $sessionUuid
     * @param string $mode
     *
     * @return bool
     */
    public function closeSession(
        string $sessionUuid, string $mode
    ): bool
    {
        $session = V2Session::where('session_uuid', $sessionUuid)
            ->where('mode', $mode)
            ->where('status', 'work')
            ->get()->first();
        $session->status = 'done';
        $session->save();

        return true;
    }
}
