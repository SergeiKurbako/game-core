<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс обисывает все данные по которым ведется статистика
 */
class StatisticsData implements IData
{
    /** @var int общий выигышь */
    public $winnings = 0;

    /** @var int общий выигышь на основной игре */
    public $winningsOnMainGame = 0;

    /** @var int общий выигышь на featureGame */
    public $winningsOnFeatureGame = 0;

    /** @var int общий проигрышь */
    public $loss = 0;

    /** @var int общий проигрышь на основной игре */
    public $lossOnMainGame = 0;

    /** @var int общий проигрышь за featureGame */
    public $loremossOnFeatureGame = 0;

    /** @var int общее кол-во кручений */
    public $spinCount = 0;

    /** @var int общее кол-во кручений в онсновной игре */
    public $spinCountInMainGame = 0;

    /** @var int общее кол-во кручений в featureGame */
    public $spinCountInFeatureGame = 0;

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

    /** @var float общий процент проигрыша относительно потраченных денег */
    public $losePercent = 0;

    /** @var float общий процент проигрыша в основной игре относительно
    * потраченных денег */
    public $losePercentOnMainGame = 0;

    /** @var float общий процент проигрыша на играх featureGame относительно
    * потраченных денег */
    public $losePercentOnFeatureGame = 0;


    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций */
    public $statisticOfWinCombinations = [];

    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций в основной игре */
    public $statisticOfWinCombinationsInMainGame = [];

    /** @var array [номер_символа => [кол-во_символов_в_комбинации =>
    * кол-во_выигрышей, ...], ... ]
    * общая статистика выигршных комбинаций в featureGame */
    public $statisticOfWinCombinationsInFeatureGame = [];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов */
    public $statisticsOfDroppedSymbols = [];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов в основной игре */
    public $statisticsOfDroppedSymbolsInMainGame = [];

    /** @var array [номер_символа => кол-во_выпадений]
    * общая статистика кол-ва выпадений символов в featureGame */
    public $statisticsOfDroppedSymbolsInFeatureGame = [];

    /** @var array [кол-во_символов_в_комбинации => [кол-во_джокеров_в_комбинации => кол-во_выпадений]]
    * статистика выигршных комбинаций из-за которых началась featureGame */
    public $statisticOfWinBonusCombinations = [];
}
