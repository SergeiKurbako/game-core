<?php

namespace Avior\GameCore\RequestDataSets;

use Avior\GameCore\RequestDataSets\RequestDataSet;

/**
 * Класс для хранения данных пользовательской сессии
 */
class SimulationRequestData extends RequestDataSet
{
    /** @var int кол-во выбранных линий */
    public $linesInGame;

    /** @var int выбранная ставка на линию */
    public $lineBet;
}
