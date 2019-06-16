<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IGame;
use Illuminate\Http\Request;

interface IGameFactory
{
    public function makeGame(int $gameId, string $mode): IGame;
}
