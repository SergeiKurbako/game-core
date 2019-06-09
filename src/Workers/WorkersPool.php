<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IWorker;

/**
 *
 */
class WorkersPool implements IWorkersPool
{
    public function addWorker(string $name, IWorker $worker): void
    {
        $this->$name = $worker;
    }
}
