<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IWorker;

/**
 * Интерфейс класса который будет хранить набор воркеров
 */
interface IWorkersPool
{
    public function addWorker(string $name, IWorker $worker): void;
}
