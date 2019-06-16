<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс содержит все данные, которые связаны с балансом
 */
class BalanceData implements IData
{
    /** @var int баланс */
    public $balance = 0;

    /** @var int общий выигрышь на данном ходу */
    public $totalPayoff = 0;

    /** @var int выигрышь за линии на текущем действии */
    public $payoffByLines = 0;

    /** @var int выигрышь на бонусных символах на текущем действии */
    public $payoffByBonus = 0;

    /** @var int выигрышь на бонусных символах на текущем действии */
    public $payoffByJackpot = 0;

    /** @var int выигрышь в игре на удвоении на текущем действии */
    public $payoffByDouble = 0;

    /** @var int выигрышь в текущей feature game */
    public $totalWinningsInFeatureGame = 0;

}
