<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Classes\GameCore\GameFactory;
use App\Models\V2Session;

class ActionCloseGameTest extends TestCase
{
    /**
     * Проверка выполнения действия закрытия игры.
     * Условия: при наличии игровой сессии
     * Результат: делается сохранение сессии и делается выход
     */
    public function testActionCloseGame1()
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
            "action" => "close_game",
            "session_uuid" => $sessionUuid
        ];

        // закрытие сессии
        $response2 = (new \App\Classes\GameCore\GameFactory())
            ->makeGame($gameId, $mode)
            ->executeAction($requestArray);

        // получение значения статуса из БД
        $session = V2Session::where('session_uuid', $sessionUuid)
                            ->where('mode', $requestArray['mode'])
                            ->get()->first();

        if ($session->status === 'done') {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
