<?php

namespace Avior\GameCore\Workers;

use Avior\GameCore\Workers\Worker;
use Avior\GameCore\Base\IToolsPool;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2Statistic;

/**
 * Класс работаюзий со статистикой пользователя
 */
class StatisticsWorker extends Worker
{
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
