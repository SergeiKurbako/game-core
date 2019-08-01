<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Base\IData;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IToolsPool;

/**
 * Класс работающий с сессией пользователя
 */
class SessionWorker extends Worker
{
    /**
     * Загрузка сессии выполняемая при любом действии
     *
     * @param IDataPool $dataPool
     * @param IToolsPool $toolsPool
     *
     * @return IDataPool
     */
    public function loadSessionData(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        // проверка наличия работающей сессии у пользователя
        $isWorkingSession = $toolsPool->dataTools->sessionDataTool->checkWorkingSession(
            $dataPool->requestData->userId,
            $dataPool->requestData->gameId,
            $dataPool->requestData->mode
        );

        // при наличии работающей (не завершенной) сессии делается ее восстановление
        if ($isWorkingSession) {
            // получение не завершенной сессии
            $session = $toolsPool->dataTools->sessionDataTool->getWorkingSession(
                $dataPool->requestData->userId,
                $dataPool->requestData->gameId,
                $dataPool->requestData->mode
            );
        }

        // при отсутсвии сессии для данной игры, либо наличии завершенной
        // делается создание новой сессии
        if (!$isWorkingSession) {
            // создание новой сессии
            $session = $toolsPool->dataTools->sessionDataTool->makeNewSession(
                $dataPool->requestData->userId,
                $dataPool->requestData->gameId,
                $dataPool->requestData->mode
            );
        }

        // заполнение $dataPool данными о сессии
        $dataPool->sessionData->userId = $session->user_id;
        $dataPool->sessionData->gameId = $session->game_id;
        $dataPool->sessionData->mode = $session->mode;
        $dataPool->sessionData->sessionUuid = $session->session_uuid;

        return $dataPool;
    }


    /**
     * Закрытие пользовательской сессии
     *
     * @param IDataPool $dataPool [description]
     * @param IToolsPool $toolsPool [description]
     *
     * @return IDataPool             [description]
     */
    public function closeSession(
        IDataPool $dataPool,
        IToolsPool $toolsPool
    ): IDataPool {
        $toolsPool->dataTools->sessionDataTool->closeSession(
            $dataPool->requestData->sessionUuid,
            $dataPool->requestData->mode
        );

        return $dataPool;
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
