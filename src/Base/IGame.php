<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IInvoker;
use Avior\GameCore\Base\IInstructionsPool;

interface IGame
{
    public function setDataPool(IDataPool $dataPool): void;

    public function setWorkersPool(IWorkersPool $workersPool): void;

    public function setToolsPool(IToolsPool $toolsPool): void;

    public function setInstructionsPool(IInstructionsPool $instructionsPool): void;

    public function executeAction(array $requestArray): string;
}
