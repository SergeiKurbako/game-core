<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс для хранения данных пользовательской сессии
 */
class SessionData implements IData
{
    /** @var int id пользователя в БД */
    public $userId = 0;

    /** @var int id игры в БД */
    public $gameId = 0;

    /** @var string мод игры */
    public $mode = '';

    /** @var string id пользовательской сессии в БД */
    public $sessionUuid = '';
}
