<?php


namespace Tests\Feature\Games\LifeOfLuxury2;

use App\Models\V2Balance;
use Tests\TestCase;

class GodTest extends TestCase
{
    public function testGod()
    {
        // пополнение баланса
        $balance = V2Balance::find(1);
        $balance->value = 1000000;
        $balance->save();

        $userId = 1;
        $gameId = 2;
        $mode = 'demo';
        $action = 'open_game';
        $sessionUuid = '';

        $responseOpenGame = $this->get("/api-v2/action?game_id=$gameId&user_id=$userId&mode=$mode&action=$action&session_uuid=$sessionUuid&token=");
        $dataGame = \GuzzleHttp\json_decode($responseOpenGame->original);
        $prevBalance = $dataGame->balanceData->balance;

        for ($i = 0; $i < 1000; $i++) {
            if ($dataGame->stateData->screen === 'mainGame') {
                $action = 'spin';
                $sessionUuid = $dataGame->sessionData->sessionUuid;
                $lineBet = 1;
                $linesInGame = 20;
                $bet = $lineBet * $linesInGame;

                $prevData = $dataGame;
                $responseSpin = $this->get("api-v2/action?game_id=$gameId&user_id=$userId&mode=$mode&action=$action&session_uuid=$sessionUuid&token=&linesInGame=$linesInGame&lineBet=$lineBet");
                $dataGame = json_decode($responseSpin->original);

                $totalPayoff = $dataGame->balanceData->totalPayoff;
                $afterBalance = $dataGame->balanceData->balance;

                if (($afterBalance + $bet - $totalPayoff) !== $prevBalance) {
                    dd('не верный баланс в GodTest');
                }

                $prevBalance = $dataGame->balanceData->balance;
            } elseif ($dataGame->stateData->screen === 'featureGame') {
                $action = 'free_spin';
                $sessionUuid = $dataGame->sessionData->sessionUuid;
                $lineBet = 1;
                $linesInGame = 20;
                $bet = $lineBet * $linesInGame;

                $prevData = $dataGame;
                $responseSpin = $this->get("api-v2/action?game_id=$gameId&user_id=$userId&mode=$mode&action=$action&session_uuid=$sessionUuid&token=&linesInGame=$linesInGame&lineBet=$lineBet");
                $dataGame = \GuzzleHttp\json_decode($responseSpin->original);

                $totalPayoff = $dataGame->balanceData->totalPayoff;
                $afterBalance = $dataGame->balanceData->balance;

                if (($afterBalance + - $totalPayoff) !== $prevBalance) {
                    dd('не верный баланс в featureGame в GodTest');
                }

                $prevBalance = $dataGame->balanceData->balance;
            }
        }

        $responseSpin->assertStatus(200);
    }
}
