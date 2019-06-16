<?php

namespace Avior\GameCore\Tools\LogicTools;

use Avior\GameCore\Base\ITool;

/**
 * Класс с методами для получения данных о выигрышных линиях
 */
class WinLinesTool implements ITool
{
    /**
     * Получение выигрышных линий (при отсутвии джокерного символа)
     *
     * @param array $table
     * @param array $lines
     * @param int $linesInGame
     *
     * @return array [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ]
     */
    public function getWinningLines(
        array $table,
        array $lines,
        int $linesInGame
    ): array
    {
        $winningLines = []; // [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ]
        foreach ($lines as $key => $line) {
            // проверка играет ли данная линия
            if ($key > ($linesInGame - 1)) {
                break;
            }

            // подсчет кол-ва выигрышных ячеек
            $winCellCount = $this->getNumberOfWonCells($table, $line);

            // проверка наличия выигрышной комбинации
            $checkWinLines = $this->checkWhetherLineWon($table, $line, $winCellCount);

            // генерация результата
            if ($checkWinLines === true) {
                $winningLines[] = [
                    'lineNumber' => $key,
                    'symbol' => $table[$line[0]],
                    'winCellCount' => $winCellCount
                ];
            }
        }

        return $winningLines;
    }

    /**
     * Получение выигрыша по линиям
     *
     * @param int $lineBet
     * @param array $table
     * @param array $winningLines
     * @param array $winCombinations
     * @param array $lines
     *
     * @return array [['lineNumber' => , 'winValue' => ], ...]
     */
    public function getPayoffsForLines(
        int $lineBet,
        array $table,
        array $winningLines,
        array $winCombinations,
        array $lines,
        int $multiplier = 1
    ): array
    {
        $payoffsForLines = [];
        foreach ($winningLines as $winningLine) {
            if (isset($winCombinations[$winningLine['symbol']][$winningLine['winCellCount']])) {

                $winValue = $winCombinations[$winningLine['symbol']][$winningLine['winCellCount']];

                // умножение на ставку по линии
                $winValue *= $lineBet;

                // умножение на множитель
                $winValue *= $multiplier;

                $payoffsForLines[] = [
                    'lineNumber' => $winningLine['lineNumber'],
                    'winValue' => $winValue
                ];
            }
        }

        return $payoffsForLines;
    }

    /**
     * Получение позиций выигрышных ячеек и их символы
     *
     * @param array $table
     * @param array $winningLines [['lineNumber' => , 'symbol' => , 'winCellCount' => ], ]
     * @param array $lines
     *
     * @return array [cellNumber => symbol, ...]
     */
    public function getWinningCells(array $table, array $winningLines, array $lines): array
    {
        $winningCells = [];

        // перебор выигрышных линий
        foreach ($winningLines as $winningLine) {
            for ($i = 0; $i < $winningLine['winCellCount']; $i++) {
                $winningCells[$lines[$winningLine['lineNumber']][$i]] = $table[$lines[$winningLine['lineNumber']][$i]];
            }
        }

        return $winningCells;
    }

    /**
     * Получение кол-ва символов в комбинации
     *
     * @param array $table сгенерированные значения ячеек стола
     * @param array $line номера клеток через которые проходит линия
     *
     * @return int кол-ва символов в комбинации
     */
    protected function getNumberOfWonCells(array $table, array $line): int
    {
        $winCellCount = 0;
        if ($table[$line[0]] === $table[$line[1]]) {
            $winCellCount = 2;

            if ($table[$line[1]] === $table[$line[2]]) {
                $winCellCount = 3;

                if ($table[$line[2]] === $table[$line[3]]) {
                    $winCellCount = 4;

                    if ($table[$line[3]] === $table[$line[4]]) {
                        $winCellCount = 5;
                    }
                }
            }
        }

        return $winCellCount;
    }

    /**
     * Проверка есть ли на линии выигрышная комбинация
     *
     * @param array $table сгенерированные значения ячеек стола
     * @param array $line номера клеток через которые проходит линия
     * @param int $winCellCount кол-во выигрышных символов
     *
     * @return bool
     */
    protected function checkWhetherLineWon(
        array $table,
        array $line,
        int $winCellCount
    ): bool
    {
        $checkWinLines = false;
        if ($winCellCount > 2) {
            $checkWinLines = true;
        } elseif ($winCellCount === 2 && $table[$line[0]] === 1) {
            $checkWinLines = true;
        } elseif ($winCellCount === 2 && $table[$line[0]] === 9) {
            $checkWinLines = true;
        }

        return $checkWinLines;
    }

}
