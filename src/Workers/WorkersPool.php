<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IWorker;

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
