<?php

namespace Avior\GameCore\RequestDataSets;

use Avior\GameCore\Base\IRequestDataSet;

/**
 * Класс описывающий все данные, которые могут получаться из запросов
 */
class RequestDataSet implements IRequestDataSet
{
    /** @var int id пользователя */
    public $userId;

    /** @var int id игры */
    public $gameId;

    /** @var string id пользовательской сессии */
    public $sessionUuid;

    /** @var string режим игры */
    public $mode;

    /** @var string выполняемое действие */
    public $action;
}
