<?php

namespace Avior\GameCore\Actions;

use Avior\GameCore\Base\IAction;
use Avior\GameCore\Base\ISubject;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IObserver;
use Avior\GameCore\Base\IDataPool;

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
