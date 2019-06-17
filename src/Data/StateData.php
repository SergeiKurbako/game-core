<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс данных описывающий состояние игры полученное в результате действия
 */
class StateData implements IData
{
    /** @var string на каком экране находится игрок (mainGame, featureGame, jackpot, doubleGame ...) */
    public $screen = '';

    /** @var string на каком экране находился игрок в предыдущем действии (mainGame, featureGame, jackpot, doubleGame ...) */
    //public $prevScreen = '';

    /** @var bool общий результат хода */
    public $isWin = false;

    /** @var bool выигрыш в основной игре (ход на котором выпадают фриспиных относится к mainGame) */
    public $isWinOnMain = false;

    /** @var bool выигрыш на бонусных символах */
    public $isWinOnBonus = false;

    /** @var bool выигрыш в основной игре */
    public $isWinOnFeatureGame = false;

    /** @var bool выигрыш джекпота */
    public $isWinOnJackpot = false;

    /** @var bool выигрыш в игре на удвоение */
    public $isWinOnDouble = false;

    /** @var bool на данном ходу в mainGame выпала игра featureGame */
    public $isDropFeatureGame = false;

    /** @var bool на данном ходу выпал последний бесплатный спин */
    public $isEndFeatureGame = false;

    /** @var bool на данном ходу выпал джекпот */
    public $isDropJackpot = false;

    /** @var int номер хода в текущей featureGame */
    public $moveNumberInFeatureGame = 0;
}
