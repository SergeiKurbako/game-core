<?php

namespace Avior\GameCore;

use Avior\GameCore\Base\IGameFactory;
use Avior\GameCore\Base\IGame;
use Avior\GameCore\GameDirector as BaseGameDirector;

/**
 * Фабрика, которая по game_id выбирает классы для сборки игры и при помощи
 * строителя возвращает объект игры
 */
class GameFactory implements IGameFactory
{
    /**
     * Метод создает объект игры по параметру game_id
     *
     * @param int $gameId
     * @param string $mode
     *
     * @return IGame
     */
    public function makeGame(int $gameId, string $mode): IGame
    {
        switch ($gameId) {
            case 1:
                return (new BaseGameDirector())->build($mode);
                break;

            case 2:
                return (new Lol2GameDirector())->build($mode);
                break;

            default:
                return (new BaseGameDirector())->build($mode);
                break;
        }
    }
}
