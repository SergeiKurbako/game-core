<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IInstruction;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

interface IWorker
{
    /**
     * Последовательное выполнение всех методов, которые есть у объекта инструкции
     *
     * @param  IDataPool    $dataPool    [description]
     * @param  IToolsPool   $toolsPool   [description]
     * @param  IInstruction $instruction [description]
     *
     * @return IDataPool                 [description]
     */
    public function executeInstruction(
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IInstruction $instruction
    ): IDataPool;
}
