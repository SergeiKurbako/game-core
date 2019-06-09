<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IRequestDataSet;

/**
 * Интерфейс класса который будет хранить набор воркеров
 */
interface IRequestDataSets
{
    public function addRequestData(string $name, IRequestDataSet $requestDataSet): void;
}
