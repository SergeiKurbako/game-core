<?php

namespace Avior\GameCore\Base;

use Illuminate\Http\Request;
use Avior\GameCore\Base\IData;

/**
 * Интерфейс для класса, который делает загрузку данных исходя из запроса
 */
interface IDataLoader
{
    /**
     * Получение объекта содержащего данные
     *
     * @param Request
     *
     * @return IData
     */
    static public function getData(Request $request): IData;
}
