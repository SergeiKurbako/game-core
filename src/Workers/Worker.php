<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IWorker;
use Avior\GameCore\Base\ISubject;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IObserver;
use Avior\GameCore\Base\IDataPool;

/**
 * Класс для работы с игровыми данными
 */
abstract class Worker implements IWorker, ISubject
{
    /** @var \SplObjectStorage */
    protected $observers;

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }

    public function attach(IObserver $observer)
    {
        $this->observers->attach($observer);
    }

    public function detach(IObserver $observer)
    {
        $this->observers->detach($observer);
    }

    public function notify(IEvent $event): IDataPool
    {
        foreach ($this->observers as $observer) {
            $event->dataPool = $observer->update($event);
        }

        return $event->dataPool;
    }
}
