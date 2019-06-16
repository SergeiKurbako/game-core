<?php

namespace Avior\Game\Base;

use Avior\GameCore\Base\IGame;

interface IGameLoader
{
    public function load(Request $request): IGame;
}
