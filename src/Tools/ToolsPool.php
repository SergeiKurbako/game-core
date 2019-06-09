<?php

namespace App\Classes\GameCore\Tools;

use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\ITool;

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
