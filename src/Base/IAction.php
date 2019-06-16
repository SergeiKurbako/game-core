<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IObserver;

/**
 * Интерфейс для класса выполняющего определенное действие
 */
interface IAction
{
    public function __invoke(
        array $requestArray,
        IWorkersPool $workersPool,
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IRequestDataSets $requestDataSets
    ): string;

    public function attach(IObserver $observer);

    public function detach(IObserver $observer);

    public function notify(IEvent $event);
}
