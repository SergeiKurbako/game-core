<?php

namespace App\Classes\Game\Base;

use App\Classes\GameCore\Base\IGame;

interface IGameLoader
{
    public function load(Request $request): IGame;
}
