<?php

namespace App\Classes\GameCore\Tools\DataTools;

use App\Classes\GameCore\Base\ITool;
use App\Classes\GameCore\Base\IDataPool;
use App\Models\V2Balance;

/**
 * Инструменов для работы с данными связанными с балансом
 */
class BalanceDataTool implements ITool
{
    public function getUserBalance(int $userId, string $mode): int
    {
        $balance = V2Balance::where('user_id', $userId)
            ->where('mode', $mode)
            ->first()
            ->value;

        return $balance;
    }

    public function updateUserBalance(int $balance, int $userId, string $mode): void
    {
        $balanceNote = V2Balance::where('user_id', $userId)
            ->where('mode', $mode)
            ->first();
        $balanceNote->value = $balance;
        $balanceNote->save();
    }
}
