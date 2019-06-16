<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс описывает все данные которые могут быть получены из запроса
 */
class RequestData implements IData
{
    /** @var int id пользователя в БД */
    public $userId = 0;

    /** @var int id игры в БД */
    public $gameId = 0;

    /** @var string мод игры */
    public $mode = '';

    /** @var string id пользовательской сессии в БД */
    public $sessionUuid = '';

    /** @var string действие которое хочет выполнить фронт */
    public $action = '';

    /** @var int кол-во выбранных линий */
    public $linesInGame = 0;

    /** @var int выбранная ставка на линию */
    public $lineBet = 0;
}
