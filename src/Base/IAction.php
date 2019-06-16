<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IObserver;

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
