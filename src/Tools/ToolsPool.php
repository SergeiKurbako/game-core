<?php

namespace Avior\GameCore\Tools;

use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\ITool;

/**
 * Набор инструменов для рабочих
 */
class ToolsPool implements IToolsPool
{
    public function addTool(string $type, string $name, ITool $tool): void
    {
        if (!isset($this->$type)) {
            $this->$type = new \stdClass;
        }

        $this->$type->$name = $tool;
    }
}
