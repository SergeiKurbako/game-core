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
    public function build(string $mode): IGame
    {
        switch ($mode) {
            case 'full':
                return $this->buildFullGame();
                break;

            case 'demo':
                return $this->buildDemoGame();
                break;

            default:
                return $this->buildDemoGame();
                break;
        }
    }

    protected function buildFullGame(): IGame
    {

    }

    protected function buildDemoGame(): IGame
    {
        // сбор данных
        $dataPool = new \App\Classes\GameCore\Data\DataPool();
        $dataPool->addData('sessionData', new \App\Classes\GameCore\Data\SessionData);
        $dataPool->addData('stateData', new \App\Classes\GameCore\Data\StateData);
        $dataPool->addData('balanceData', new \App\Classes\GameCore\Data\BalanceData);
        $dataPool->addData('logicData', new \App\Classes\GameCore\Data\LogicData);
        $dataPool->addData('requestData', new \App\Classes\GameCore\Data\RequestData);
        $dataPool->addData('statisticsData', new \App\Classes\GameCore\Data\StatisticsData);

        // сбор рабочих
        $workersPool = new \App\Classes\GameCore\Workers\WorkersPool;
        $workersPool->addWorker('sessionWorker', new \App\Classes\GameCore\Workers\SessionWorker);
        $workersPool->addWorker('stateWorker', new \App\Classes\GameCore\Workers\StateWorker);
        $workersPool->addWorker('balanceWorker', new \App\Classes\GameCore\Workers\BalanceWorker);
        $workersPool->addWorker('logicWorker', new \App\Classes\GameCore\Workers\LogicWorker);
        $workersPool->addWorker('requestWorker', new \App\Classes\GameCore\Workers\RequestWorker);
        $workersPool->addWorker('responseWorker', new \App\Classes\GameCore\Workers\ResponseWorker);
        $workersPool->addWorker('recoveryWorker', new \App\Classes\GameCore\Workers\RecoveryWorker);
        $workersPool->addWorker('statisticsWorker', new \App\Classes\GameCore\Workers\StatisticsWorker);
        $workersPool->addWorker('verifierWorker', new \App\Classes\GameCore\Workers\VerifierWorker);

        // сбор инструменов
        $toolsPool = new \App\Classes\GameCore\Tools\ToolsPool;
        $toolsPool->addTool('dataTools', 'balanceDataTool', new \App\Classes\GameCore\Tools\DataTools\BalanceDataTool);
        $toolsPool->addTool('dataTools', 'recoveryDataTool', new \App\Classes\GameCore\Tools\DataTools\RecoveryDataTool);
        $toolsPool->addTool('dataTools', 'requestDataTool', new \App\Classes\GameCore\Tools\DataTools\RequestDataTool);
        $toolsPool->addTool('dataTools', 'sessionDataTool', new \App\Classes\GameCore\Tools\DataTools\SessionDataTool);
        $toolsPool->addTool('dataTools', 'stateDataTool', new \App\Classes\GameCore\Tools\DataTools\StateDataTool);
        $toolsPool->addTool('dataTools', 'statisticsDataTool', new \App\Classes\GameCore\Tools\DataTools\StatisticsDataTool);
        $toolsPool->addTool('dataTools', 'logicDataTool', new \App\Classes\GameCore\Tools\DataTools\LogicDataTool);
        $toolsPool->addTool('logicTools', 'tableTool', new \App\Classes\GameCore\Tools\LogicTools\TableTool);
        $toolsPool->addTool('logicTools', 'winLinesTool', new \App\Classes\GameCore\Tools\LogicTools\WinLinesTool);
        $toolsPool->addTool('logicTools', 'bonusCalculatorTool', new \App\Classes\GameCore\Tools\LogicTools\BonusCalculatorTool);
        $toolsPool->addTool('balanceTools', 'payoffCalculatorTool', new \App\Classes\GameCore\Tools\BalanceTools\PayoffCalculatorTool);
        $toolsPool->addTool('balanceTools', 'possibilityСhecker', new \App\Classes\GameCore\Tools\BalanceTools\PossibilityСhecker);
        $toolsPool->addTool('stateTools', 'stateCalculatorTool', new \App\Classes\GameCore\Tools\StateTools\StateCalculatorTool);
        $toolsPool->addTool('statisticsTools', 'statisticsCalculatorTool', new \App\Classes\GameCore\Tools\StatisticsTools\StatisticsCalculatorTool);

        // сбор действий
        $actionsPool = new \App\Classes\GameCore\Actions\ActionsPool;
        $actionsPool->addAction('open_game', new \App\Classes\GameCore\Actions\ActionOpenGame);
        $actionsPool->addAction('close_game', new \App\Classes\GameCore\Actions\ActionCloseGame);
        $actionsPool->addAction('spin', new \App\Classes\GameCore\Actions\ActionSpin);
        $actionsPool->addAction('free_spin', new \App\Classes\GameCore\Actions\ActionFreeSpin);
        $actionsPool->addAction('simulation', new \App\Classes\GameCore\Actions\ActionSimulation);

        // сбор набора данных, который будет обрабатываться при соответсвующих запросах
        $requestDataSetPool = new \App\Classes\GameCore\RequestDataSets\RequestDataSets;
        $requestDataSetPool->addRequestData('open_game', new \App\Classes\GameCore\RequestDataSets\OpenGameRequestData);
        $requestDataSetPool->addRequestData('close_game', new \App\Classes\GameCore\RequestDataSets\CloseGameRequestData);
        $requestDataSetPool->addRequestData('spin', new \App\Classes\GameCore\RequestDataSets\SpinRequestData);
        $requestDataSetPool->addRequestData('free_spin', new \App\Classes\GameCore\RequestDataSets\FreeSpinRequestData);
        $requestDataSetPool->addRequestData('simulation', new \App\Classes\GameCore\RequestDataSets\SimulationRequestData);

        // создание игры
        $game = new \App\Classes\GameCore\Game;
        $game->setActionsPool($actionsPool);
        $game->setRequestDataSets($requestDataSetPool);
        $game->setDataPool($dataPool);
        $game->setWorkersPool($workersPool);
        $game->setToolsPool($toolsPool);

        return $game;
    }
}
