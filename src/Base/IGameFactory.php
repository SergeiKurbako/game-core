<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IGame;
use Illuminate\Http\Request;

interface IGameFactory
{
    public function makeGame(int $gameId, string $mode): IGame;
}
