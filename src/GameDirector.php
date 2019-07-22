<?php

namespace Avior\GameCore;

use Illuminate\Http\Request;
use Avior\GameCore\Base\IGameDirector;
use Avior\GameCore\Base\IGame;

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

    protected $instructionsPool;

    public $game;

    public function __construct()
    {
        // сбор данных
        $this->dataPool = new \Avior\GameCore\Data\DataPool();
        $this->dataPool->addData('sessionData', new \Avior\GameCore\Data\SessionData);
        $this->dataPool->addData('stateData', new \Avior\GameCore\Data\StateData);
        $this->dataPool->addData('balanceData', new \Avior\GameCore\Data\BalanceData);
        $this->dataPool->addData('logicData', new \Avior\GameCore\Data\LogicData);
        $this->dataPool->addData('requestData', new \Avior\GameCore\Data\RequestData);
        $this->dataPool->addData('userStatisticsData', new \Avior\GameCore\Data\StatisticsData);
        $this->dataPool->addData('gameStatisticsData', new \Avior\GameCore\Data\StatisticsData);
        $this->dataPool->addData('longData', new \Avior\GameCore\Data\LongData);
        $this->dataPool->addData('systemData', new \Avior\GameCore\Data\SystemData);

        // сбор набора данных, который будет обрабатываться при соответсвующих запросах
        $this->requestDataSetPool = new \Avior\GameCore\RequestDataSets\RequestDataSets;
        $this->requestDataSetPool->addRequestData('open_game', new \Avior\GameCore\RequestDataSets\OpenGameRequestData);
        $this->requestDataSetPool->addRequestData('close_game', new \Avior\GameCore\RequestDataSets\CloseGameRequestData);
        $this->requestDataSetPool->addRequestData('spin', new \Avior\GameCore\RequestDataSets\SpinRequestData);
        $this->requestDataSetPool->addRequestData('free_spin', new \Avior\GameCore\RequestDataSets\FreeSpinRequestData);
        $this->requestDataSetPool->addRequestData('simulation', new \Avior\GameCore\RequestDataSets\SimulationRequestData);

        // сбор воркеров
        $this->workersPool = new \Avior\GameCore\Workers\WorkersPool;
        $this->workersPool->addWorker('sessionWorker', new \Avior\GameCore\Workers\SessionWorker);
        $this->workersPool->addWorker('stateWorker', new \Avior\GameCore\Workers\StateWorker);
        $this->workersPool->addWorker('balanceWorker', new \Avior\GameCore\Workers\BalanceWorker);
        $this->workersPool->addWorker('logicWorker', new \Avior\GameCore\Workers\LogicWorker);
        $this->workersPool->addWorker('requestWorker', new \Avior\GameCore\Workers\RequestWorker);
        $this->workersPool->addWorker('responseWorker', new \Avior\GameCore\Workers\ResponseWorker);
        $this->workersPool->addWorker('recoveryWorker', new \Avior\GameCore\Workers\RecoveryWorker);
        $this->workersPool->addWorker('statisticsWorker', new \Avior\GameCore\Workers\StatisticsWorker);
        $this->workersPool->addWorker('verifierWorker', new \Avior\GameCore\Workers\VerifierWorker);

        // сбор инструкций
        $this->instructionsPool = new \Avior\GameCore\Instructions\InstructionsPool;
        $this->instructionsPool->addInstruction('logicWorkerInstructions', 'loadData', new \Avior\GameCore\Instructions\WorkersInstructions\LogicWorkerInstructions\LogicWorkerLoadDataInstruction);
        $this->instructionsPool->addInstruction('logicWorkerInstructions', 'spin', new \Avior\GameCore\Instructions\WorkersInstructions\LogicWorkerInstructions\LogicWorkerSpinInstruction);
        $this->instructionsPool->addInstruction('logicWorkerInstructions', 'freeSpin', new \Avior\GameCore\Instructions\WorkersInstructions\LogicWorkerInstructions\LogicWorkerFreeSpinInstruction);
        $this->instructionsPool->addInstruction('userStatisticsWorkerInstructions', 'loadData', new \Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions\UserStatisticsWorkerLoadDataInstruction);
        $this->instructionsPool->addInstruction('userStatisticsWorkerInstructions', 'spin', new \Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions\UserStatisticsWorkerSpinInstruction);
        $this->instructionsPool->addInstruction('userStatisticsWorkerInstructions', 'freeSpin', new \Avior\GameCore\Instructions\WorkersInstructions\UserStatisticsWorkerInstructions\UserStatisticsWorkerFreeSpinInstruction);
        $this->instructionsPool->addInstruction('gameStatisticsWorkerInstructions', 'loadData', new \Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions\GameStatisticsWorkerLoadDataInstruction);
        $this->instructionsPool->addInstruction('gameStatisticsWorkerInstructions', 'spin', new \Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions\GameStatisticsWorkerSpinInstruction);
        $this->instructionsPool->addInstruction('gameStatisticsWorkerInstructions', 'freeSpin', new \Avior\GameCore\Instructions\WorkersInstructions\GameStatisticsWorkerInstructions\GameStatisticsWorkerFreeSpinInstruction);
        $this->instructionsPool->addInstruction('stateWorkerInstructions', 'loadData', new \Avior\GameCore\Instructions\WorkersInstructions\StateWorkerInstructions\StateWorkerLoadDataInstruction);
        $this->instructionsPool->addInstruction('stateWorkerInstructions', 'spin', new \Avior\GameCore\Instructions\WorkersInstructions\StateWorkerInstructions\StateWorkerSpinInstruction);
        $this->instructionsPool->addInstruction('stateWorkerInstructions', 'freeSpin', new \Avior\GameCore\Instructions\WorkersInstructions\StateWorkerInstructions\StateWorkerFreeSpinInstruction);
        $this->instructionsPool->addInstruction('balanceWorkerInstructions', 'loadData', new \Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions\BalanceWorkerLoadDataInstruction);
        $this->instructionsPool->addInstruction('balanceWorkerInstructions', 'spin', new \Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions\BalanceWorkerSpinInstruction);
        $this->instructionsPool->addInstruction('balanceWorkerInstructions', 'freeSpin', new \Avior\GameCore\Instructions\WorkersInstructions\BalanceWorkerInstructions\BalanceWorkerFreeSpinInstruction);

        // сбор инструменов
        $this->toolsPool = new \Avior\GameCore\Tools\ToolsPool;
        $this->toolsPool->addTool('dataTools', 'balanceDataTool', new \Avior\GameCore\Tools\DataTools\BalanceDataTool);
        $this->toolsPool->addTool('dataTools', 'recoveryDataTool', new \Avior\GameCore\Tools\DataTools\RecoveryDataTool);
        $this->toolsPool->addTool('dataTools', 'requestDataTool', new \Avior\GameCore\Tools\DataTools\RequestDataTool);
        $this->toolsPool->addTool('dataTools', 'sessionDataTool', new \Avior\GameCore\Tools\DataTools\SessionDataTool);
        $this->toolsPool->addTool('dataTools', 'stateDataTool', new \Avior\GameCore\Tools\DataTools\StateDataTool);
        $this->toolsPool->addTool('dataTools', 'statisticsDataTool', new \Avior\GameCore\Tools\DataTools\StatisticsDataTool);
        $this->toolsPool->addTool('dataTools', 'logicDataTool', new \Avior\GameCore\Tools\DataTools\LogicDataTool);
        $this->toolsPool->addTool('logicTools', 'tableTool', new \Avior\GameCore\Tools\LogicTools\TableTool);
        $this->toolsPool->addTool('logicTools', 'winLinesTool', new \Avior\GameCore\Tools\LogicTools\WinLinesTool);
        $this->toolsPool->addTool('logicTools', 'bonusCalculatorTool', new \Avior\GameCore\Tools\LogicTools\BonusCalculatorTool);
        $this->toolsPool->addTool('balanceTools', 'payoffCalculatorTool', new \Avior\GameCore\Tools\BalanceTools\PayoffCalculatorTool);
        $this->toolsPool->addTool('balanceTools', 'possibilityСhecker', new \Avior\GameCore\Tools\BalanceTools\PossibilityСhecker);
        $this->toolsPool->addTool('stateTools', 'stateCalculatorTool', new \Avior\GameCore\Tools\StateTools\StateCalculatorTool);
        $this->toolsPool->addTool('statisticsTools', 'statisticsCalculatorTool', new \Avior\GameCore\Tools\StatisticsTools\StatisticsCalculatorTool);

        // сбор действий
        $this->actionsPool = new \Avior\GameCore\Actions\ActionsPool;
        $this->actionsPool->addAction('open_game', new \Avior\GameCore\Actions\ActionOpenGame);
        $this->actionsPool->addAction('close_game', new \Avior\GameCore\Actions\ActionCloseGame);
        $this->actionsPool->addAction('spin', new \Avior\GameCore\Actions\ActionSpin);
        $this->actionsPool->addAction('free_spin', new \Avior\GameCore\Actions\ActionFreeSpin);
        $this->actionsPool->addAction('simulation', new \Avior\GameCore\Actions\ActionSimulation);

        // подпись обсерверов на события
        $this->workersPool->stateWorker->attach(new \Avior\GameCore\Observers\GameProcessObservers\StartFeatureGameObserver);
        $this->workersPool->stateWorker->attach(new \Avior\GameCore\Observers\GameProcessObservers\EndFeatureGameObserver);
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
        $this->game = new \Avior\GameCore\Game;
        $this->game->setActionsPool($this->actionsPool);
        $this->game->setRequestDataSets($this->requestDataSetPool);
        $this->game->setDataPool($this->dataPool);
        $this->game->setWorkersPool($this->workersPool);
        $this->game->setToolsPool($this->toolsPool);
        $this->game->setInstructionsPool($this->instructionsPool);

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
