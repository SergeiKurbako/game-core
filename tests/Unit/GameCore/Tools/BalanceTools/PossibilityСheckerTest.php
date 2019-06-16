<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PossibilityСheckerTest extends TestCase
{
    /**
     * Проверка возможности выполнения действия spin
     * Условия: баланс достаточен для совершения спина в основной игре
     * Результат: разрешается выполнить действие
     */
    public function testCheckIsPossibilitySpin1()
    {
        $check = false;

        $possibilityСhecker = new \App\Classes\GameCore\Tools\BalanceTools\PossibilityСhecker;

        $balance = 15;
        $betLine = 1;
        $linesInGame = 15;
        $screen = 'mainGame';

        $result = $possibilityСhecker->checkIsPossibilitySpin(
            $balance,
            $betLine,
            $linesInGame,
            $screen
        );

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка возможности выполнения действия spin
     * Условия: баланс не достаточен для совершения спина в основной игре
     * Результат: запрещается выполнить действие
     */
    public function testCheckIsPossibilitySpin2()
    {
        $check = false;

        $possibilityСhecker = new \App\Classes\GameCore\Tools\BalanceTools\PossibilityСhecker;

        $balance = 10;
        $betLine = 1;
        $linesInGame = 15;
        $screen = 'mainGame';

        $result = $possibilityСhecker->checkIsPossibilitySpin(
            $balance,
            $betLine,
            $linesInGame,
            $screen
        );

        if ($result === false) {
            $check = true;
        }

        $this->assertTrue($check);
    }

    /**
     * Проверка возможности выполнения действия free_spin
     * Условия: баланс не достаточен для совершения спина в основной игре
     * Результат: разрешается
     */
    public function testCheckIsPossibilitySpin3()
    {
        $check = false;

        $possibilityСhecker = new \App\Classes\GameCore\Tools\BalanceTools\PossibilityСhecker;

        $balance = 0;
        $betLine = 1;
        $linesInGame = 15;
        $screen = 'featureGame';

        $result = $possibilityСhecker->checkIsPossibilitySpin(
            $balance,
            $betLine,
            $linesInGame,
            $screen
        );

        if ($result === true) {
            $check = true;
        }

        $this->assertTrue($check);
    }
}
