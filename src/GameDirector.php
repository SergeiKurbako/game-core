<?php

namespace App\Classes\GameCore;

use Illuminate\Http\Request;
use App\Classes\GameCore\Base\IGameDirector;
use App\Classes\GameCore\Base\IGame;

/**
 * Класс подбирает команду(командир, рабочие, данные) для выполнения запроса
 * учитывая режим в катором запущеная игра
 */
class GameDirector implements IGameDirector
{
    protected $dataPool;

    protected $requestDataSetPool;

    protected $workersPool;

    protected $toolsPool;

    protected $actionsPool;

    public $game;

    public function __construct()
    {
        // сбор данных
        $this->dataPool = new \App\Classes\GameCore\Data\DataPool();
        $this->dataPool->addData('sessionData', new \App\Classes\GameCore\Data\SessionData);
        $this->dataPool->addData('stateData', new \App\Classes\GameCore\Data\StateData);
        $this->dataPool->addData('balanceData', new \App\Classes\GameCore\Data\BalanceData);
        $this->dataPool->addData('logicData', new \App\Classes\GameCore\Data\LogicData);
        $this->dataPool->addData('requestData', new \App\Classes\GameCore\Data\RequestData);
        $this->dataPool->addData('statisticsData', new \App\Classes\GameCore\Data\StatisticsData);

        // сбор набора данных, который будет обрабатываться при соответсвующих запросах
        $this->requestDataSetPool = new \App\Classes\GameCore\RequestDataSets\RequestDataSets;
        $this->requestDataSetPool->addRequestData('open_game', new \App\Classes\GameCore\RequestDataSets\OpenGameRequestData);
        $this->requestDataSetPool->addRequestData('close_game', new \App\Classes\GameCore\RequestDataSets\CloseGameRequestData);
        $this->requestDataSetPool->addRequestData('spin', new \App\Classes\GameCore\RequestDataSets\SpinRequestData);
        $this->requestDataSetPool->addRequestData('free_spin', new \App\Classes\GameCore\RequestDataSets\FreeSpinRequestData);
        $this->requestDataSetPool->addRequestData('simulation', new \App\Classes\GameCore\RequestDataSets\SimulationRequestData);

        // сбор рабочих
        $this->workersPool = new \App\Classes\GameCore\Workers\WorkersPool;
        $this->workersPool->addWorker('sessionWorker', new \App\Classes\GameCore\Workers\SessionWorker);
        $this->workersPool->addWorker('stateWorker', new \App\Classes\GameCore\Workers\StateWorker);
        $this->workersPool->addWorker('balanceWorker', new \App\Classes\GameCore\Workers\BalanceWorker);
        $this->workersPool->addWorker('logicWorker', new \App\Classes\GameCore\Workers\LogicWorker);
        $this->workersPool->addWorker('requestWorker', new \App\Classes\GameCore\Workers\RequestWorker);
        $this->workersPool->addWorker('responseWorker', new \App\Classes\GameCore\Workers\ResponseWorker);
        $this->workersPool->addWorker('recoveryWorker', new \App\Classes\GameCore\Workers\RecoveryWorker);
        $this->workersPool->addWorker('statisticsWorker', new \App\Classes\GameCore\Workers\StatisticsWorker);
        $this->workersPool->addWorker('verifierWorker', new \App\Classes\GameCore\Workers\VerifierWorker);

        // сбор инструменов
        $this->toolsPool = new \App\Classes\GameCore\Tools\ToolsPool;
        $this->toolsPool->addTool('dataTools', 'balanceDataTool', new \App\Classes\GameCore\Tools\DataTools\BalanceDataTool);
        $this->toolsPool->addTool('dataTools', 'recoveryDataTool', new \App\Classes\GameCore\Tools\DataTools\RecoveryDataTool);
        $this->toolsPool->addTool('dataTools', 'requestDataTool', new \App\Classes\GameCore\Tools\DataTools\RequestDataTool);
        $this->toolsPool->addTool('dataTools', 'sessionDataTool', new \App\Classes\GameCore\Tools\DataTools\SessionDataTool);
        $this->toolsPool->addTool('dataTools', 'stateDataTool', new \App\Classes\GameCore\Tools\DataTools\StateDataTool);
        $this->toolsPool->addTool('dataTools', 'statisticsDataTool', new \App\Classes\GameCore\Tools\DataTools\StatisticsDataTool);
        $this->toolsPool->addTool('dataTools', 'logicDataTool', new \App\Classes\GameCore\Tools\DataTools\LogicDataTool);
        $this->toolsPool->addTool('logicTools', 'tableTool', new \App\Classes\GameCore\Tools\LogicTools\TableTool);
        $this->toolsPool->addTool('logicTools', 'winLinesTool', new \App\Classes\GameCore\Tools\LogicTools\WinLinesTool);
        $this->toolsPool->addTool('logicTools', 'bonusCalculatorTool', new \App\Classes\GameCore\Tools\LogicTools\BonusCalculatorTool);
        $this->toolsPool->addTool('balanceTools', 'payoffCalculatorTool', new \App\Classes\GameCore\Tools\BalanceTools\PayoffCalculatorTool);
        $this->toolsPool->addTool('balanceTools', 'possibilityСhecker', new \App\Classes\GameCore\Tools\BalanceTools\PossibilityСhecker);
        $this->toolsPool->addTool('stateTools', 'stateCalculatorTool', new \App\Classes\GameCore\Tools\StateTools\StateCalculatorTool);
        $this->toolsPool->addTool('statisticsTools', 'statisticsCalculatorTool', new \App\Classes\GameCore\Tools\StatisticsTools\StatisticsCalculatorTool);

        // сбор действий
        $this->actionsPool = new \App\Classes\GameCore\Actions\ActionsPool;
        $this->actionsPool->addAction('open_game', new \App\Classes\GameCore\Actions\ActionOpenGame);
        $this->actionsPool->addAction('close_game', new \App\Classes\GameCore\Actions\ActionCloseGame);
        $this->actionsPool->addAction('spin', new \App\Classes\GameCore\Actions\ActionSpin);
        $this->actionsPool->addAction('free_spin', new \App\Classes\GameCore\Actions\ActionFreeSpin);
        $this->actionsPool->addAction('simulation', new \App\Classes\GameCore\Actions\ActionSimulation);

        // подпись обсерверов на события
        $this->workersPool->stateWorker->attach(new \App\Classes\GameCore\Observers\GameProcessObservers\StartFeatureGameObserver);
        $this->workersPool->stateWorker->attach(new \App\Classes\GameCore\Observers\GameProcessObservers\EndFeatureGameObserver);
    }

    /**
     * Метод занимающийся сборкой игры
     *
     * @param  string $mode [description]
     *
     * @return IGame        [description]
     */
    public function build(string $mode): IGame
    {
        // дополнительная преконфигурация настроек
        $this->updateConfig($mode);

        // создание игры
        $this->game = new \App\Classes\GameCore\Game;
        $this->game->setActionsPool($this->actionsPool);
        $this->game->setRequestDataSets($this->requestDataSetPool);
        $this->game->setDataPool($this->dataPool);
        $this->game->setWorkersPool($this->workersPool);
        $this->game->setToolsPool($this->toolsPool);

        return $this->game;
    }

    /**
     * Метод который нужно использовать для дополнительной конфигурации игры
     *
     * @return bool
     */
    protected function updateConfig(string $mode): bool
    {
        return true;
    }
}
