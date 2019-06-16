<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IAction;

/**
 * Интерфейс для класса выполняющего определенное действие
 */
interface IActionsPool
{
    public function addAction(string $name, IAction $action): void;
}
