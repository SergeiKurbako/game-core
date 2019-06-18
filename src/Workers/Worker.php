<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IWorker;
use Avior\GameCore\Base\ISubject;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IObserver;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IWorkerInstructionsPool;

/**
 * Класс для работы с игровыми данными
 */
abstract class Worker implements IWorker, ISubject
{
    /** @var \SplObjectStorage */
    protected $observers;

    /** @var IInstructionsPool набор инструкций, обеспечением которыми
    * занимается GameDirector при конфигурации игры */
    protected $workerInstructionsPool;

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

    public function addInstructionsPool(IWorkerInstructionsPool $workerInstructionsPool): void
    {
        $this->workerInstructionsPool = $workerInstructionsPool;
    }
}
