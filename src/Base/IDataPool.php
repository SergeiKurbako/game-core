<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IData;

/**
 * Интерфейс класса который будет хранить набор данных с которыми работают воркеры
 */
interface IDataPool
{
    public function addData(string $name, IData $data): void;
}
