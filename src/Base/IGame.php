<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IInvoker;

interface IGame
{
    public function setDataPool(IDataPool $dataPool): void;

    public function setWorkersPool(IWorkersPool $workersPool): void;

    public function setToolsPool(IToolsPool $toolsPool): void;

    public function executeAction(array $requestArray): string;
}
