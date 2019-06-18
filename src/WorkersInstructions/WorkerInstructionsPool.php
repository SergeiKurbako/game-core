<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IWorkerInstructionsPool;
use Avior\GameCore\Base\IWorkerInstruction;

/**
 *
 */
class WorkerInstructionsPool implements IWorkerInstructionsPool
{
    public function addWorkerInstruction(string $name, IWorkerInstruction $worker): void
    {
        $this->$name = $worker;
    }
}
