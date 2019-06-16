<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\ITool;

/**
 * Инструменты выполняют элементарные задачи,
 * которые могут встречаться во всех выиграных
 * (библиотека функций разделенная на малые сферы ответсвенности)
 */
interface IToolsPool
{
    public function addTool(string $type, string $name, ITool $tool): void;
}
