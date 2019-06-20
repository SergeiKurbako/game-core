<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс содержит системные данные
 */
class SystemData implements IData
{
    /** @var array заданный набор ячеек */
    public $tablePreset  = [];

    /** @var bool выполнение симеляции */
    public $isSimulation = false;
}
