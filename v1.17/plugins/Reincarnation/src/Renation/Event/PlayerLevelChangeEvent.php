<?php

namespace Renation\Event;

use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\Player;

class PlayerLevelChangeEvent extends PlayerEvent implements Cancellable
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