<?php

namespace App\Classes\GameCore\Workers;

use App\Classes\GameCore\Base\IWorker;
use App\Classes\GameCore\Base\ISubject;
use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IObserver;
use App\Classes\GameCore\Base\IDataPool;

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
