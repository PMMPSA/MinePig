<?php


namespace DaPigGuy\PiggyFactions\libs\DaPigGuy\libPiggyEconomy\providers;


use pocketmine\Player;

/**
 * Interface EconomyProvider
 * @package DaPigGuy\libPiggyEconomy\providers
 */
abstract class EconomyProvider
{
    public static function checkDependencies(): bool
    {
        return true;
    }

    public function getMonetaryUnit(): string
    {
        return "$";
    }

    abstract function getMoney(Player $player): float;

    abstract function giveMoney(Player $player, float $amount): void;

    abstract function takeMoney(Player $player, float $amount): void;

    abstract function setMoney(Player $player, float $amount): void;
}