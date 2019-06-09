<?php

namespace App\Classes\GameCore\RequestDataSets;

use App\Classes\GameCore\Base\IRequestDataSets;
use App\Classes\GameCore\Base\IRequestDataSet;

/**
 *
 */
class RequestDataSets implements IRequestDataSets
{
    public function addRequestData(string $name, IRequestDataSet $requestDataSet): void {
        $this->$name = $requestDataSet;
    }
}
