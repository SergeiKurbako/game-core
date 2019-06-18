<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IInstructionsPool;

interface IWorker
{
    public function addInstructionsPool(): IInstructionsPool;
}
