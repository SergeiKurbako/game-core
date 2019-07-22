<?php

namespace Avior\GameCore\Data;

use Avior\GameCore\Base\IData;

/**
 * Класс содержит данные которые хранятся длительное время.
 * Эти данные храняться постоянно пока не будут удалены из объекта
 */
class LongData implements IData
{
    /** @var object|null объект содержащий набор данных, который хранится длительное время */
    public $data = null;
}
