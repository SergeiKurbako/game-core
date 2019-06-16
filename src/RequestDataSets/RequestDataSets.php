<?php

namespace Avior\GameCore\RequestDataSets;

use Avior\GameCore\Base\IRequestDataSets;
use Avior\GameCore\Base\IRequestDataSet;

/**
 *
 */
class RequestDataSets implements IRequestDataSets
{
    public function addRequestData(string $name, IRequestDataSet $requestDataSet): void {
        $this->$name = $requestDataSet;
    }
}
