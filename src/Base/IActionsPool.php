<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IAction;

/**
 * Интерфейс для класса выполняющего определенное действие
 */
interface IActionsPool
{
    public function addAction(string $name, IAction $action): void;
}
