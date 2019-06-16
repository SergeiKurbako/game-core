<?php

namespace App\Classes\GameCore\Actions;

use App\Classes\GameCore\Base\IAction;
use App\Classes\GameCore\Base\ISubject;
use App\Classes\GameCore\Base\IEvent;
use App\Classes\GameCore\Base\IObserver;
use App\Classes\GameCore\Base\IDataPool;

/**
 * Класс содержащий базовые методы и свойства для действия
 */
abstract class Action implements IAction, ISubject
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
