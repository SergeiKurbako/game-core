<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IWorker;

/**
 * Интерфейс класса который будет хранить набор воркеров
 */
interface IWorkersPool
{
    public function addWorker(string $name, IWorker $worker): void;
}
