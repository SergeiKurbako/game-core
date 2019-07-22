<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IWorker;
use Avior\GameCore\Base\ISubject;
use Avior\GameCore\Base\IEvent;
use Avior\GameCore\Base\IObserver;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IInstruction;
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

    /**
     * Последовательное выполнение всех методов, которые есть у объекта инструкции.
     * После выполнения инструкции делается оповещение о событиях.
     *
     * @param  IDataPool    $dataPool    [description]
     * @param  IToolsPool   $toolsPool   [description]
     * @param  IInstruction $instruction [description]
     *
     * @return IDataPool                 [description]
     */
    public function executeInstruction(
        IDataPool $dataPool,
        IToolsPool $toolsPool,
        IInstruction $instruction
    ): IDataPool {
        $instructionMethodNames = get_class_methods($instruction);

        foreach ($instructionMethodNames as $methodName) {
            $dataPool = $instruction->$methodName($dataPool, $toolsPool);
        }

        // отправка уведомлений о событиях
        $dataPool = $this->sendNotifies($dataPool, $toolsPool);

        return $dataPool;
    }

    /**
     * Метод отправляющий уведомления о событиях.
     * Отправка событий делается исходя из данных в $dataPool (т.е. пишутся условия)
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    protected abstract function sendNotifies(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool;
}
