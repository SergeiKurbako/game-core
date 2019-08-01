<?php

namespace Avior\GameCore\Tools\DataTools;

use Avior\GameCore\Base\ITool;
use Avior\GameCore\Base\IDataPool;
use App\Models\V2Balance;

/**
 * Инструменов для работы с данными связанными с балансом
 */
class BalanceDataTool implements ITool
{
    /**
     * Получение баланса пользователя
     *
     * @param  int    $userId [description]
     * @param  string $mode   [description]
     *
     * @return int            [description]
     */
    public function getUserBalance(int $userId, string $mode): int
    {
        $balance = V2Balance::where('user_id', $userId)
        ->where('mode', $mode)
        ->first();

        return $balance->value;
    }

    public function updateUserBalance(int $balance, int $userId, string $mode): void
    {
        $balanceNote = V2Balance::where('user_id', $userId)
            ->where('mode', $mode)
            ->first();
        $balanceNote->value = $balance;
        $balanceNote->save();
    }

    /**
     * Возврат баланса пользователя к исходному значению в 10000 единиц
     * (выполняется для demo игр)
     *
     * @param  int  $userId [description]
     *
     * @return bool         [description]
     */
    public function resetUserBalanceForDemoGame(int $userId): bool
    {
        $balanceNote = V2Balance::where('user_id', $userId)
            ->where('mode', 'demo')
            ->first();

            if ($balanceNote) {
                $balanceNote->value = 10000;
                $balanceNote->save();
            } else {
                $balanceNote = new V2Balance;
                $balanceNote->mode = 'demo';
                $balanceNote->user_id = $userId;
                $balanceNote->value = 10000;
                $balanceNote->save();
            }


        return true;
    }
}
