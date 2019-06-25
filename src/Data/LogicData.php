<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс содержит все данные, которые связаны с игровой логикой
 */
class LogicData implements IData
{
    /** @var int кол-во символов в игре */
    public $countSymbolsInGame = 10;

    /** @var int ставка */
    public $lineBet = 1;

    /** @var int выбранное кол-во линий для хода */
    public $linesInGame = 1;

    /** @var int min ставка */
    public $minLineBet = 1;

    /** @var int max ставка */
    public $maxLineBet = 10;

    /** @var int min кол-во линий для хода */
    public $minLinesInGame = 1;

    /** @var int max кол-во линий для хода */
    public $maxLinesInGame = 15;

    /** @var array игровые линии [[cellNumber, cellNumber, cellNumber, cellNumber, cellNumber], ...] */
    public $linesRules = [];

    /** @var array правила выпадения featureGame, которые не зависят от линий [symbol, requiredAmount] */
    public $featureGameRules = [];

    /** @var array [[symbol => [count => winValue, ... ], ...]
     * Правила выигрыша на бонусных символах, которые не зависят от линий
     * Выигрышь может работать по разному в зависимости от правил игры:
     * прибавка к общему выигрышу за ход; множитель общей ставки сделанной за ход
     * и т.д.
     * В base игре общая ставка умножается на множитель.
     */
    public $bonusRules = [];

    /** @var array правила выигрыша на бонусных символах [[symbol => [count => winValue, ... ], ...] */
    public $combinationsRules = [];

    /** @var array правила выпадения джекпота */
    public $jackpotRules = [];

    /** @var array правила задающие вероятность выпадения символов */
    public $percentagesRules = [];

    /** @var array на данном ходу значения ячеек на барабане */
    public $table = [];

    /** @var array перечень выигрышных линий [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ...] */
    public $winningLines = [];

    /** @var array перечень выигрышей на бонусных символах [['symbol' => , 'count' => , 'winning' => ], ...] */
    public $payoffsForBonus = [];

    /** @var array выигрышь по линиям [['lineNumber' => , 'winValue' => ], ...] */
    public $payoffsForLines = [];

    /** @var array выигрышь от игры на удвоение */
    public $payoffsForDouble = [];

    /** @var array выигрышь от джекпота */
    public $payoffsForJackpot = [];

    /** @var array выигрышные ячейки [cellNumber => symbol, ...] */
    public $winningCells = [];

    /** @var int максимально кол-во ходов в featureGame которое доступно для пользователя на данный момент */
    public $countOfMovesInFeatureGame = 10;

    /** @var int максимально кол-во ходов в featureGame которое доступно для пользователя со старта */
    public $startCountOfFreeSpinsInFeatureGame = 10;

    /** @var int текущий множитель */
    public $multiplier = 1;

    /** @var int стартовый множитель в основной игре */
    public $startMultiplierInMainGame = 1;

    /** @var int стартовый множитель в featureGame */
    public $startMultiplierInFeatureGame = 2;
}
