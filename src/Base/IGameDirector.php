<?php

namespace Avior\GameCore\Base;

use Avior\GameCore\Base\IGame;
use Illuminate\Http\Request;

interface IGameDirector
{
    public function build(string $mode): IGame;
}
