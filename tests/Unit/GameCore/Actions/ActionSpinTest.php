<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActionSpinTest extends TestCase
{
    /**
     * Проверка выполнения действия получения результатов спина.
     * Условия: Делается запрос на открытие игры
     * и делается запрос на выполнение спина
     * Результат: получается ответ
     */
    public function testActionSpin1()
    {
        $check = false;

        // сброс БД
        $result = shell_exec('php artisan migrate:rollback && php artisan migrate && php artisan db:seed');

        $gameId = 1;
        $mode = 'demo';

        $response1 = (new \App\Classes\GameCore\GameFactory())
            ->makeGame($gameId, $mode)
            ->executeAction([
                "game_id" => "1",
                "user_id" => "1",
                "mode" => "demo",
                "action" => "open_game",
                "session_uuid" => null
            ]);

        $response2 = (new \App\Classes\GameCore\GameFactory())
            ->makeGame($gameId, $mode)
            ->executeAction([
                "game_id" => "1",
                "user_id" => "1",
                "mode" => "demo",
                "action" => "spin",
                "session_uuid" => json_decode($response1)->sessionData->sessionUuid,
                "lineBet" => 1,
                "linesInGame" => 15
            ]);


        if (json_decode($response1)->sessionData->sessionUuid === json_decode($response2)->sessionData->sessionUuid) {
            $check = true;
        }

        $this->assertTrue($check);
    }

}
