<?php

namespace App\Classes\GameCore\Data;

use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IData;

/**
 * Пул объектов данных
 */
class DataPool implements IDataPool
{
    public function addData(string $name, IData $data): void
    {
        $this->$name = $data;
    }
}
