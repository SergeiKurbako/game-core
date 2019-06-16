<?php

namespace Avior\GameCore;

use Avior\GameCore\Base\IGameFactory;
use Avior\GameCore\Base\IGame;
use Avior\GameCore\GameDirector as BaseGameDirector;
use Illuminate\Support\ServiceProvider;

/**
 * Фабрика, которая по game_id выбирает классы для сборки игры и при помощи
 * строителя возвращает объект игры
 */
class GameCoreProvider extends ServiceProvider
{
    public function boot()
    {
        // $this->mergeConfigFrom( __DIR__ . '/config/nondictionary.php', 'nondictionary' );
        // $this->loadTranslationsFrom( __DIR__ . '/lang', 'restrict-wordlist-passwords' );
        //
        // $this->publishes( [
        //     __DIR__ . '/lang' => resource_path( 'lang/vendor/restrict-wordlist-passwords' ),
        // ], 'lang' );
        //
        // \Validator::extend( 'non_dictionary', function ( $attribute, $value, $parameters, $validator )
        // {
        //     $dictionary = file( \Config::get( 'nondictionary.file' ) );
        //     $dictionary = array_map( 'trim', $dictionary );
        //
        //     return !in_array( $value, $dictionary );
        // }, trans( 'restrict-wordlist-passwords::validation.non_dictionary' ) );
    }

    public function register()
    {
        //
    }

    /**
     * Метод создает объект игры по параметру game_id
     *
     * @param int $gameId
     * @param string $mode
     *
     * @return IGame
     */
    public function makeGame(int $gameId, string $mode): IGame
    {
        switch ($gameId) {
            case 1:
                return (new BaseGameDirector())->build($mode);
                break;

            default:
                return (new BaseGameDirector())->build($mode);
                break;
        }
    }
}
