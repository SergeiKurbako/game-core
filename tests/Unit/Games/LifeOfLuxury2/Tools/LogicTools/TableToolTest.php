<?php

namespace Tests\Games\LifeOfLuxury2\Tools\LogicTools;

use Tests\TestCase;
use App\Models\V2GameRule;
use App\Classes\Games\LifeOfLuxury2\Tools\LogicTools\TableTool;

class TableToolTest extends TestCase
{
    /**
     * тест не возможности выпадения одинаковых символов на одном барабане
     * Условия: выполняется 100к итераций
     * Результат: одинаковые символы не выпадают
     */
    public function testGetRandomTable1()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'mainGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            $drums = [];
            $counter = 0;
            foreach ($table as $item) {
                if ($counter < 3) {
                    $drums[] = $item;
                    $counter += 1;
                } else {
                    if ($drums[0] === $drums[1]) {
                        $check = false;
                    }
                    if ($drums[1] === $drums[2]) {
                        $check = false;
                    }
                    if ($drums[2] === $drums[0]) {
                        $check = false;
                    }

                    $drums = [];
                    $drums[] = $item;
                    $counter = 1;
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест не возможности выпадения алмазов на крайних барабанах
     * Условия: выполняется 100к итераций
     * Результат: на крайних барабанах не выпадают алмазы
     */
    public function testGetRandomTable2()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'mainGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($key < 3) {
                    if ($item === 0) {
                        $check = false;
                    }
                }

                if ($key > 11) {
                    if ($item === 0) {
                        $check = false;
                    }
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест не возможности выпадения на одном барабане алмаза и монеты
     * Условия: выполняется 100к итераций
     * Результат: на одном барабане не может выпасть алмаз и монета
     */
    public function testGetRandomTable3()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'mainGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            $drums = [];
            $counter = 0;
            foreach ($table as $item) {
                if ($counter < 3) {
                    $drums[] = $item;
                    $counter += 1;
                } else {
                    if ($drums[0] === 0) {
                        if ($drums[1] === 10) {
                            $check = false;
                        }
                        if ($drums[2] === 10) {
                            $check = false;
                        }
                    }

                    if ($drums[1] === 0) {
                        if ($drums[2] === 10) {
                            $check = false;
                        }
                        if ($drums[0] === 10) {
                            $check = false;
                        }
                    }

                    if ($drums[2] === 0) {
                        if ($drums[1] === 10) {
                            $check = false;
                        }
                        if ($drums[0] === 10) {
                            $check = false;
                        }
                    }

                    if ($check === false) {
                        dd(__METHOD__, $table);
                    }

                    $drums = [];
                    $drums[] = $item;
                    $counter = 1;
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест выпадения алмазов на барабанах 2,3 и 4
     * Условия: выполняется 100к итераций
     * Результат: алмазы выпадают на средних барабанах
     */
    public function testGetRandomTable4()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'mainGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        $counter = 0;
        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($key > 2 && $key < 12) {
                    if ($item === 0) {
                        $counter += 1;
                    }
                }
            }
        }

        if ($counter === 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * тест выпадения не существующих символов
     * Условия: выполняется 100к итераций
     * Результат: не существующие символы не выпадают
     */
    public function testGetRandomTable5()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'mainGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($item === 123123) {
                    $check = false;
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест не возможности выпадения одинаковых символов на одном барабане
     * Условия: выполняется 100к итераций
     * Результат: одинаковые символы не выпадают
     */
    public function testGetRandomTableForFreeSpin1()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'featureGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            $drums = [];
            $counter = 0;
            foreach ($table as $item) {
                if ($counter < 3) {
                    $drums[] = $item;
                    $counter += 1;
                } else {
                    if ($drums[0] === $drums[1]) {
                        $check = false;
                    }
                    if ($drums[1] === $drums[2]) {
                        $check = false;
                    }
                    if ($drums[2] === $drums[0]) {
                        $check = false;
                    }

                    $drums = [];
                    $drums[] = $item;
                    $counter = 1;
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест не возможности выпадения алмазов на крайних барабанах
     * Условия: выполняется 100к итераций
     * Результат: на крайних барабанах не выпадают алмазы
     */
    public function testGetRandomTableForFreeSpin2()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'featureGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($key < 3) {
                    if ($item === 0) {
                        $check = false;
                    }
                }

                if ($key > 11) {
                    if ($item === 0) {
                        $check = false;
                    }
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест не возможности выпадения на одном барабане алмаза и монеты
     * Условия: выполняется 100к итераций
     * Результат: на одном барабане не может выпасть алмаз и монета
     */
    public function testGetRandomTableForFreeSpin3()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'featureGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            $drums = [];
            $counter = 0;
            foreach ($table as $item) {
                if ($counter < 3) {
                    $drums[] = $item;
                    $counter += 1;
                } else {
                    if ($drums[0] === 0) {
                        if ($drums[1] === 10) {
                            $check = false;
                        }
                        if ($drums[2] === 10) {
                            $check = false;
                        }
                    }

                    if ($drums[1] === 0) {
                        if ($drums[2] === 10) {
                            $check = false;
                        }
                        if ($drums[0] === 10) {
                            $check = false;
                        }
                    }

                    if ($drums[2] === 0) {
                        if ($drums[1] === 10) {
                            $check = false;
                        }
                        if ($drums[0] === 10) {
                            $check = false;
                        }
                    }

                    if ($check === false) {
                        dd($table);
                    }

                    $drums = [];
                    $drums[] = $item;
                    $counter = 1;
                }
            }
        }

        $this->assertTrue($check);
    }

    /**
     * тест выпадения алмазов на барабанах 2,3 и 4
     * Условия: выполняется 100к итераций
     * Результат: алмазы выпадают на средних барабанах
     */
    public function testGetRandomTableForFreeSpin4()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'featureGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        $counter = 0;
        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($key > 2 && $key < 12) {
                    if ($item === 0) {
                        $counter += 1;
                    }
                }
            }
        }

        if ($counter === 0) {
            $check = false;
        }

        $this->assertTrue($check);
    }

    /**
     * тест выпадения не существующих символов
     * Условия: выполняется 100к итераций
     * Результат: не существующие символы не выпадают
     */
    public function testGetRandomTableForFreeSpin5()
    {
        $check = true;

        $tableTool = new TableTool;

        $percentagesRules = json_decode(V2GameRule::where('game_id', 2)->where('name', 'percentages')->first()->rules);
        $screen = 'featureGame';
        $bet = 20;

        // получение процентов выпадения символов
        $currentPercentages = $tableTool->getCurrentPercentages(
            $percentagesRules,
            $screen,
            $bet
        );

        for($i = 0; $i < 100000; $i++) {
            $table = $tableTool->getRandomTable(
                $currentPercentages
            );

            foreach ($table as $key => $item) {
                if ($item === 123123) {
                    $check = false;
                }
            }
        }

        $this->assertTrue($check);
    }


}
