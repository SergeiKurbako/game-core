<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IRequestDataSet;

/**
 * Интерфейс класса который будет хранить набор воркеров
 */
interface IRequestDataSets
{
    public function addRequestData(string $name, IRequestDataSet $requestDataSet): void;
}
