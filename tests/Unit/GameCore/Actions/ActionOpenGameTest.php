<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Classes\GameCore\GameFactory;

class ActionOpenGameTest extends TestCase
{
    /**
     * Проверка выполнения действия открытия игры.
     * Условия: при отсутсвии предыдущей игровой сессии
     * Результат: запускается новая игровая сессия
     */
    public function testActionOpenGame1()
    {
        $check = false;

        // сброс БД
        $result = shell_exec('php artisan migrate:rollback && php artisan migrate && php artisan db:seed');

        $gameId = 1;
        $mode = 'demo';

        $requestArray = [
            "game_id" => "1",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => null
        ];

        $response = (new GameFactory)
            ->makeGame($gameId, $mode)
            ->executeAction($requestArray);

        if (json_decode($response)->stateData->screen === 'mainGame') {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка выполнения действия открытия игры.
     * Условия: при наличии предыдущей игровой сессии
     * Результат: восстанавливается игровая сессия
     */
    public function testActionOpenGame2()
    {
        // сброс БД
        $result = shell_exec('php artisan migrate:rollback && php artisan migrate && php artisan db:seed');

        $check = false;

        $gameId = 1;
        $mode = 'demo';

        $requestArray = [
            "game_id" => "1",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => null
        ];

        // получение новой сессии
        $response1 = (new GameFactory)
            ->makeGame($gameId, $mode)
            ->executeAction($requestArray);

        $sessionUuid = json_decode($response1)->sessionData->sessionUuid;

        $requestArray = [
            "game_id" => "1",
            "user_id" => "1",
            "mode" => "demo",
            "action" => "open_game",
            "session_uuid" => $sessionUuid
        ];

        // востановление сессии
        $response2 = (new \App\Classes\GameCore\GameFactory())
            ->makeGame($gameId, $mode)
            ->executeAction($requestArray);

        if (json_decode($response1)->sessionData->sessionUuid === json_decode($response2)->sessionData->sessionUuid) {
            $check = true;
        }

        $this->assertTrue($check);
    }

}
