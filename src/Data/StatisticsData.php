<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс обисывает все данные которые могут быть получены из запроса
 */
class StatisticsData implements IData
{
    /** @var int общий выигышь */
    public $totalWinnings = 0;

    /** @var int общий выигышь в основной игре */
    public $totalWinningsOnMainGame = 0;

    /** @var int выигышь в featureGame */
    public $totalWinningsOnFeatureGame = 0;

    /** @var int выигышь в игре на удвоение */
    public $totalWinningsOnDoubleGame = 0;

    /** @var int общий выигышь на джекпотах */
    public $totalWinningsOnJackpots = 0;

    /** @var int общий проигрышь */
    public $totalLoss = 0;

    /** @var int общий проигрышь в основной игре */
    public $totalLossOnMainGame = 0;

    /** @var int общий проигрышь в featureGame */
    public $totalLossOnFeatureGame = 0;

    /** @var int общий проигрышь в игре на удвоение */
    public $totalLossOnDoubleGame = 0;

    /** @var int общее кол-во кручений */
    public $totalSpinCount = 0;

    /** @var int общее кол-во кручений в онсновной игре */
    public $totalSpinCountOnMainGame = 0;

    /** @var int общее кол-во кручений в featureGame */
    public $totalSpinCountOnFeatureGame = 0;

    /** @var int общее кол-во выпавших джекпотов */
    public $totalJackpots = 0;

    /** @var int общее кол-во выпавших featureGame */
    public $totalFeatureGamesDropped = 0;

}
