<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс обисывает все данные по которым ведется статистика
 */
class StatisticsData implements IData
{
    /** @var int сумма сделанных ставок */
    public $totalBet = 0;

    /** @var int общий выигышь */
    public $winnings = 0;

    /** @var int общий выигышь на основной игре */
    public $winningsOnMainGame = 0;

    /** @var int общий выигышь на featureGame */
    public $winningsOnFeatureGame = 0;

    /** @var int общее кол-во денег потраченное на ходы */
    public $loss = 0;

    /** @var int общее кол-во денег потраченное на ходы в основной игре */
    public $lossOnMainGame = 0;

    /** @var int общее кол-во денег потраченное на ходы в featureGame */
    public $lossOnFeatureGame = 0;

    /** @var int общее кол-во кручений */
    public $spinCount = 0;

    /** @var int общее кол-во кручений в онсновной игре */
    public $spinCountInMainGame = 0;

    /** @var int общее кол-во кручений в featureGame */
    public $spinCountInFeatureGame = 0;

    /** @var int общее кол-во выигрышных кручений */
    public $winSpinCount = 0;

    /** @var int общее кол-во выигрышных кручений в онсновной игре */
    public $winSpinCountInMainGame = 0;

    /** @var int общее кол-во выигрышных кручений в featureGame */
    public $winSpinCountInFeatureGame = 0;

    /** @var int общее кол-во проигрышных кручений */
    public $loseSpinCount = 0;

    /** @var int общее кол-во проигрышных кручений в онсновной игре */
    public $loseSpinCountInMainGame = 0;

    /** @var int общее кол-во проигрышных кручений в featureGame */
    public $loseSpinCountInFeatureGame = 0;


    /** @var int общее кол-во выпавших featureGame */
    public $featureGamesDropped = 0;


    /** @var float общий процент выигрышных спинов относительно общего кол-ва
    * кручений (включая featureGame) */
    public $percentWinSpins = 0;

    /** @var float общий процент выигрышных спинов в основной игре */
    public $percentWinSpinsInMainGame = 0;

    /** @var float общий процент выигрышных спинов в featureGame */
    public $percentWinSpinsInFeatureGame = 0;

    /** @var float общий процент проигрышных спинов относительно общего кол-ва
    * кручений (включая featureGame) */
    public $percentLoseSpins = 0;

    /** @var float общий процент проигрышных спинов в основной игре */
    public $percentLoseSpinsInMainGame = 0;

    /** @var float общий процент проигрышных спинов в featureGame */
    public $percentLoseSpinsInFeatureGame = 0;


    /** @var float общий процент выигрыша относительно потраченных денег */
    public $winPercent = 0;

    /** @var float общий процент выигрыша полученный за основную игру
    * относительно потраченных денег */
    public $winPercentOnMainGame = 0;

    /** @var float общий процент выигрыша полученный за featureGame
    * относительно потраченных денег */
    public $winPercentOnFeatureGame = 0;


    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций */
    public $statisticOfWinCombinations = [[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0]];

    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций в основной игре */
    public $statisticOfWinCombinationsInMainGame = [[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0]];

    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций в featureGame */
    public $statisticOfWinCombinationsInFeatureGame = [[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0]];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов */
    public $statisticsOfDroppedSymbols = [0,0,0,0,0,0,0,0,0,0,0];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов в основной игре */
    public $statisticsOfDroppedSymbolsInMainGame = [0,0,0,0,0,0,0,0,0,0,0];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов в featureGame */
    public $statisticsOfDroppedSymbolsInFeatureGame = [0,0,0,0,0,0,0,0,0,0,0];

    /** @var array [кол-во_символов_в_комбинации => [кол-во_джокеров_в_комбинации => кол-во_выпадений]]
    * статистика выигршных комбинаций из-за которых началась featureGame */
    public $statisticOfWinBonusCombinations = [[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0],[0,0,0,0,0,0]];


    /** @var array [кол-во_бонусных_сомволов_выпавших_за_ход => кол-во_выпадений]
    * статистика кол-ва бонусных символов выпадающих за ход */
    public $droppedBonusSymbolsInOneSpin = [0,0,0,0,0,0];

    /** @var array [кол-во_бонусных_сомволов_выпавших_за_ход => кол-во_выпадений]
    * статистика кол-ва бонусных символов выпадающих за ход в основной игре */
    public $droppedBonusSymbolsInOneSpinInMainGame = [0,0,0,0,0,0];

    /** @var array [кол-во_бонусных_сомволов_выпавших_за_ход => кол-во_выпадений]
    * статистика кол-ва бонусных символов выпадающих за ход в featureGame */
    public $droppedBonusSymbolsInOneSpinInFeatureGame = [0,0,0,0,0,0];
}
