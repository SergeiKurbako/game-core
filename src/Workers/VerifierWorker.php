<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Workers\Worker;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Exeptions\Worker\ValidationWorker\IncorrectRequestExeption;

class VerifierWorker extends Worker
{
    /**
     * Проверка данных запускающих игру
     *
     * @param array $requestArray
     * @param IToolsPool $toolsPool
     */
    public function verificationStartGameRequest(
        IDataPool $requestArray,
        IToolsPool $toolsPool
    ) {
        return true;
    }

    /**
     * Проверка игровых данных для spin запроса
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return bool
     */
    public function verificationSpinRequest(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): bool {
        try {
            if ($dataPool->requestData->linesInGame > $dataPool->logicData->maxLinesInGame) {
                throw new IncorrectRequestExeption('linesInGame > ' . $dataPool->logicData->maxLinesInGame);
            }

            if ($dataPool->requestData->lineBet > $dataPool->logicData->maxLineBet) {
                throw new IncorrectRequestExeption('lineBet > ' . $dataPool->logicData->maxLineBet);
            }
        } catch (IncorrectRequestExeption $e) {
            die(json_encode(["status" => "false", "message" => $e->getMessage()]));
        }

        return true;
    }

    /**
     * Проверка игровых данных для free_spin запроса
     *
     * @param array $requestArray
     * @param IToolsPool $toolsPool
     */
    public function verificationFreeSpinRequest(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ) {
        try {
            if ($dataPool->requestData->linesInGame > $dataPool->logicData->maxLinesInGame) {
                throw new IncorrectRequestExeption('linesInGame > ' . $dataPool->logicData->maxLinesInGame);
            }

            if ($dataPool->requestData->lineBet > $dataPool->logicData->maxLineBet) {
                throw new IncorrectRequestExeption('lineBet > ' . $dataPool->logicData->maxLineBet);
            }
        } catch (IncorrectRequestExeption $e) {
            die(json_encode(["status" => "false", "message" => $e->getMessage()]));
        }

        return true;
    }

    /**
     * Метод отправляющий уведомления о событиях
     *
     * @param  IDataPool  $dataPool  [description]
     * @param  IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    protected function sendNotifies(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        return $dataPool;
    }
}
