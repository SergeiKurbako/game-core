<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\IActionsPool;

/**
 * Класс выполняет действие открытия игры
 */
class ActionsPool implements IActionsPool
{
    /**
     * Добавить действие для командира
     *
     * @param string $name
     * @param IAction $action
     */
    public function addAction(string $name, IAction $action): void
    {
        $this->$name = $action;
    }
}
