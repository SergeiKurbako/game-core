<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IWorkerInstruction;

/**
 * Интерфейс класса который будет хранить набор инструкций
 */
interface IWorkerInstructionsPool
{
    public function addWorkerInstruction(
        string $name,
        IWorkerInstruction $workerInstruction
    ): void;
}
