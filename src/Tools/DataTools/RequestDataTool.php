<?php

namespace Avior\GameCore\Tools\DataTools;

use Avior\GameCore\Base\ITool;
use Avior\GameCore\Base\IDataPool;
use Avior\GameCore\Base\IData;
use Avior\GameCore\Base\IRequestDataSet;

/**
 * помошник для работы с запросом с фронта
 */
class RequestDataTool implements ITool
{
    public function loadData(
        IData $requestData,
        IRequestDataSet $requestDataSet,
        array $requestArray
    ): IData
    {
        // Трансфонмация имен для параметров полученных из запроса
        $transformRequestArray = [];
        foreach ($requestArray as $key => $value) {
            // получение названия параметра
            $paramName = $this->transformParamName($key);

            $transformRequestArray[$paramName] = $value;
        }

        // перебор параметров которые должны быть в запросе
        foreach ($requestDataSet as $key => $value) {
            // при наличии параметра с таким имененм в наборе предопределенным действием
            // выполняется запись параметра в объект $requestData
            if (array_key_exists($key, $transformRequestArray)) {
                $type = gettype($requestData->$key);
                settype($transformRequestArray[$key], $type);
                $requestData->$key = $transformRequestArray[$key];
            } else {
                dd('В запросе отсутствует параметр ' . $key);
            }
        }

        return $requestData;
    }

    /**
     * Преобразование имя параметра из вида "имя_парамета" к виду "имяПараметра"
     * @param string $paramName [description]
     * @return string            [description]
     */
    protected function transformParamName(string $paramName): string
    {
        $newParamName = '';

        // разбиваем строку на массив оп разделителю
        $nameParts = explode('_', $paramName);

        // перевод первого символа в верхнийрегистр (кроме первого части)
        $transformedNameParts = [];
        foreach ($nameParts as $key => $value) {
            if ($key !== 0) {
                $transformedNameParts[] = ucfirst($value);
            } else {
                $transformedNameParts[] = $value;
            }
        }

        // объединение частей в строку
        $newParamName = implode('', $transformedNameParts);

        return $newParamName;
    }
}
