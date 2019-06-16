<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IDataPool;

/**
 * Интерфейс для создания объекта который может создать события
 * и подписывать на них обсерверы
 */
interface ISubject
{
    public function attach(IObserver $observer);

    public function detach(IObserver $observer);

    public function notify(IEvent $event): IDataPool;
}
