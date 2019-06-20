<?php

namespace Avior\GameCore\Instructions;

use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Base\IInstruction;

/**
 *
 */
class InstructionsPool implements IInstructionsPool
{
    /**
     * Набор инструкций для воркера
     *
     * @param string       $type        тип инструкции (например: logicWorkerInstructions)
     * @param string       $name        имя инструкции. Должно совпадать с значением
     * параметра action из запроса
     * @param IInstruction $instruction объект содержащий набор методов, которые
     * должны последовательно выполнится воркером
     *
     * return void
     */
    public function addInstruction(
        string $type,
        string $name,
        IInstruction $instruction
    ): void {
        if (!isset($this->$type)) {
            $this->$type = new \stdClass;
        }

        $this->$type->$name = $instruction;
    }
}
