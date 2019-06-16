<?php

namespace Avior\GameCore\Tools\LogicTools;

use Avior\GameCore\Base\ITool;

/**
 * помошник для работы с запросом с фронта
 */
class TableTool implements ITool
{
    /**
     * Получение рандомного стола для основной игры
     *
     * @param array [номер барабана => [проценты, процент, ...]] $percentages проценты выпадения символов
     *
     * @return array [символ, симво, ...]
     */
    public function getRandomTable(array $percentages): array
    {
        // получение барабанов
        $drums = $this->generateDrums($percentages);

        // сбор всех значений в единый массив
        $table = $this->generateTable($drums);

        return $table;
    }

    /**
     * Получение объекта данных содержащего проценты выпадения символов
     * в зависимости от барабана
     * Получение процентов делается в зависимости от ставки и от текущего
     * общего состоияния игры
     * Перечисление процентов в зависимости от ставки должно начинаться с меньшей
     * ставки
     *
     * @param int $gameId
     * @param string $screen текущий экран
     * @param int $bet общая ставка
     *
     * @return object
     */
    public function getCurrentPercentages(array $percentages, string $screen, int $bet): array
    {
        $currentPercentages = [];
        foreach ($percentages as $percentage) {
            if ($percentage->bet === 0) {
                $currentPercentages = $percentage;
            } else {
                if ($bet >= $percentage->bet) {
                    $currentPercentages = $percentage;
                }
            }
        }

        if ($screen === 'mainGame') {
            $currentPercentages = $currentPercentages->mainGame;
        }
        if ($screen === 'featureGame') {
            $currentPercentages = $currentPercentages->featureGame;
        }

        return $currentPercentages;
    }

    /**
     * Генерация значений для отдельного барабана
     *
     * @param array [номер барабана => [проценты, процент, ...]] $percentages проценты выпадения символов
     *
     * @return array [value, value, value]
     */
    protected function generateDrums(array $percentages): array
    {
        // Преобразование процентов к диапазонам процентов
        $percentageRangs = $this->converPercentagesToRangs($percentages);

        $drums = [];
        foreach ($percentageRangs as $key => $percentageRang) {
            $cellRow = [];
            for ($i = 0; $i < 3; $i++) {
                $cellRow[] = $this->generateCellValue($percentageRang, $cellRow);
            }

            $drums[] = $cellRow;
        }

        return $drums;
    }

    /**
     * Генерация значения ячейки исходя из выпавшего процента
     *
     * @param array $currentPercentage [description]
     * @param array $cellRow [description]
     *
     * @return int                       [description]
     */
    protected function generateCellValue(array $currentPercentageRangs, array $cellRow): int
    {
        $rand = rand(1, 99);

        // получение значения соответсвующего выпавшему проценту
        $cellValue = 123123;
        foreach ($currentPercentageRangs as $key => $valuePercRang) {
            if ($rand >= $valuePercRang[0] && $rand < $valuePercRang[1]) {
                $cellValue = $key;
            }
        }

        // исключение совпадения символов в барабане
        if (!in_array($cellValue, $cellRow)) {
            return $cellValue;
        } else {
            return $this->generateCellValue($currentPercentageRangs, $cellRow);
        }

        return $cellValue;
    }

    /**
     * Сборка итогового стола из отдельных барабанов
     *
     * @param array $drums [description]
     *
     * @return array        [description]
     */
    protected function generateTable(array $drums): array
    {
        $table = [];
        foreach ($drums as $drum) {
            foreach ($drum as $value) {
                $table[] = $value;
            }
        }

        return $table;
    }

    /**
     * Конвертация процетов выпадения символов к диапазаонам процентов
     * для всех барабанов
     * (т.е. диапазоны которым соответсвуют символы)
     *
     * @param array $currentPercentage [0 => [процент, процент, ... , процент], 1 => ...]
     *
     * @return array [0 => [[от процент, до процент], [9,18], ... , [90,100]], 1 => ...]
     */
    protected function converPercentagesToRangs(array $percentages): array
    {
        $percentageRangs = [];
        foreach ($percentages as $percentage) {

            $rangs = [];
            foreach ($percentage as $key => $value) {
                if ($key === 0) {
                    $rangs[] = [0, $value];
                } else {
                    if (!is_int($key)) {
                        dd($percentage);
                    }
                    $rangs[] = [
                        $rangs[$key - 1][1],
                        $rangs[$key - 1][1] + $value
                    ];
                }
            }

            $percentageRangs[] = $rangs;
        }

        return $percentageRangs;
    }

}
