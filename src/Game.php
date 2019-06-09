<?php

namespace App\Classes\GameCore;

use App\Classes\GameCore\Base\IGame;
use App\Classes\GameCore\Base\IDataPool;
use App\Classes\GameCore\Base\IWorkersPool;
use App\Classes\GameCore\Base\IActionsPool;
use App\Classes\GameCore\Base\IToolsPool;
use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Data\DataPool;
use App\Classes\GameCore\Workers\WorkersPool;

/**
 * Общий класс для управления игрой
 *
 * Содержит данные и методы обеспечивающие работу игры и которые используется
 * контроллером для выполнения запросов с фронта
 */
class Game implements IGame
{
    /** @var object содержащий объекты с данными */
    protected $dataPool;

    /** @var object содержащий объекты классов работающих с данными игры */
    protected $workersPool;

    /** @var object содержащий объекты классов работающих над волнением определенных действий */
    protected $actionsPool;

    /** @var object содержащий объекты инструменов */
    protected $toolsPool;

    /** @var object содержащий объекты описывающие данные необходимые для запросов */
    protected $requestDataSets;

    /**
     * Добавление объекта с данными в игру
     *
     * @param string $dataname
     * @param IData $data
     *
     * @return void
     */
    public function setDataPool(IDataPool $dataPool): void
    {
        $this->dataPool = $dataPool;
    }

    /**
     * Добавление объекта класса работающего с данными
     *
     * @param string $workername
     * @param IWorker $worker
     *
     * @return void
     */
    public function setWorkersPool(IWorkersPool $workersPool): void
    {
        $this->workersPool = $workersPool;
    }

    /**
     * Добавление объекта содержащего объекты с действиями
     *
     * @param IActionsPool $actionsPool
     *
     * @return void
     */
    public function setActionsPool(IActionsPool $actionsPool): void
    {
        $this->actionsPool = $actionsPool;
    }

    /**
     * Добавление объекта содержащего объекты с действиями
     *
     * @param IToolsPool $actionsPool
     *
     * @return void
     */
    public function setToolsPool(IToolsPool $toolsPool): void
    {
        $this->toolsPool = $toolsPool;
    }

    /**
     * Добавление объекта описывающие данные получаемые в запросе
     *
     * @param IRequestsDataPool $requestsDataPool
     *
     * @return void
     */
    public function setRequestDataSets(IRequestDataSets $requestDataSets): void
    {
        $this->requestDataSets = $requestDataSets;
    }

    /**
     * Выполнение действия
     *
     * @param int $gameId
     * @param int $userId
     * @param string $sessionUuid
     * @param string $action
     *
     * @return string строка в формате json
     */
    public function executeAction(array $requestArray): string
    {
        $requesAction = $requestArray['action'];
        $action = $this->actionsPool->$requesAction;
        $response = $action(
            $requestArray,
            $this->workersPool,
            $this->dataPool,
            $this->toolsPool,
            $this->requestDataSets
        );

        return $response;
    }
}
