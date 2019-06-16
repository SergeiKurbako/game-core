<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IData;

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
