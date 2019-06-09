<?php

namespace App\Classes\GameCore\RequestDataSets;

use App\Classes\GameCore\RequestDataSets\RequestDataSet;

/**
 * Класс для хранения данных пользовательской сессии
 */
class SpinRequestData extends RequestDataSet
{
    /** @var int кол-во выбранных линий */
    public $linesInGame;

    /** @var int выбранная ставка на линию */
    public $lineBet;
}
