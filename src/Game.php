<?php

namespace Avior\GameCore;

use Avior\GameCore\Base\IGame;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IWorkersPool;
use Avior\GameCore\Base\IActionsPool;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Base\IInstructionsPool;
use Avior\GameCore\Data\DataPool;
use Avior\GameCore\Workers\WorkersPool;

/**
 * Общий класс для управления игрой
 *
 * Содержит данные и методы обеспечивающие работу игры и которые используется
 * контроллером для выполнения запросов с фронта
 */
class Game implements IGame
{
    /** @var object содержащий объекты с данными */
    public $dataPool;

    /** @var object содержащий объекты классов работающих с данными игры */
    public $workersPool;

    /** @var object содержащий объекты классов работающих над волнением определенных действий */
    public $actionsPool;

    /** @var object содержащий объекты инструменов */
    public $toolsPool;

    /** @var object содержащий объекты описывающие данные необходимые для запросов */
    public $requestDataSets;

    /** @var object содержащий объекты описывающие инструкции
    * (набор методов, который последовательно выполняется) */
    public $instructionsPool;

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
     * Добавление объекта содержащего объекты инструкции
     *
     * @param IInstructionsPool $instructionsPool
     *
     * @return void
     */
    public function setInstructionsPool(IInstructionsPool $instructionsPool): void
    {
        $this->instructionsPool = $instructionsPool;
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
     * @param  array  $requestArray параметры запроса
     * @param  array  $tablePreset        массив с значениями ячеек для проведения теста
     *
     * @return string               [description]
     */
    public function executeAction(
        array $requestArray,
        array $tablePreset = [],
        bool $isSimulation = false
    ): string
    {
        // запись предустановленного стола в systemData
        $this->dataPool->systemData->tablePreset = $tablePreset;
        $this->dataPool->systemData->isSimulation = $isSimulation;

        $this->dataPool->systemData->startExecutionTime = microtime(true);

        // получение объекта действия
        $requesAction = $requestArray['action'];
        $action = $this->actionsPool->$requesAction;

        $response = $action(
            $requestArray,
            $this->workersPool,
            $this->dataPool,
            $this->toolsPool,
            $this->instructionsPool,
            $this->requestDataSets
        );

        return $response;
    }
}
