<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IInstruction;

/**
 * Интерфейс класса который будет хранить набор инструкций
 */
interface IInstructionsPool
{
    public function addInstruction(
        string $type,
        string $name,
        IInstruction $instruction
    ): void;
}
