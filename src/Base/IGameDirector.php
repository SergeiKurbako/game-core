<?php

namespace App\Classes\GameCore\Base;

use App\Classes\GameCore\Base\IGame;
use Illuminate\Http\Request;

interface IGameDirector
{
    public function build(string $mode): IGame;
}
