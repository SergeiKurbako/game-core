<?php

namespace Avior\GameCore\RequestDataSets;

use Avior\GameCore\RequestDataSets\RequestDataSet;

class FreeSpinRequestData extends RequestDataSet
{
    /** @var int кол-во выбранных линий */
    public $linesInGame;

    /** @var int выбранная ставка на линию */
    public $lineBet;
}
