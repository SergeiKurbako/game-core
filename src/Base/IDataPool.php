<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IData;

/**
 * Интерфейс класса который будет хранить набор данных с которыми работают воркеры
 */
interface IDataPool
{
    public function addData(string $name, IData $data): void;
}
