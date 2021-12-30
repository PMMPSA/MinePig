<?php

namespace SCoinAPI\Event;

use pocketmine\Player;
use pocketmine\event\player\PlayerEvent;
use pocketmine\event\Cancellable;

class PlayerChangeSCoinEvent extends PlayerEvent implements Cancellable
{

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function getPlayer() : Player
    {
       return $this->player;
    }
}