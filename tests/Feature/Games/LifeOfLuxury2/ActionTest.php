<?php


namespace Tests\Feature\Games\LifeOfLuxury2;

use Tests\TestCase;

class ActionTest extends TestCase
{
    public function testActionOpenGame()
    {
        $userId = 1;
        $gameId = 2;
        $mode = 'demo';
        $action = 'open_game';
        $sessionUuid = '';

        $response = $this->get("/api-v2/action?game_id=$gameId&user_id=$userId&mode=$mode&action=$action&session_uuid=$sessionUuid&token=");

        $responseData = \GuzzleHttp\json_decode($response->original);

        $response->assertStatus(200);
    }

}
