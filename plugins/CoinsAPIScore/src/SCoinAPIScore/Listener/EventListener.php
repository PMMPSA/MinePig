<?php

namespace SCoinAPIScore\Listener;

use SCoinAPI\Event\PlayerChangeSCoinEvent;
use SCoinAPI\Loader;
use SCoinAPIScore\Main;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;

use pocketmine\Player;
use pocketmine\event\Listener;


class EventListener implements Listener
{

   public function __construct(Main $plugin)
   {
      $this->plugin = $plugin;
   }

   public function onChange(PlayerChangeSCoinEvent $event) 
   {
      $player = $event->getPlayer();

      (new PlayerTagUpdateEvent($player, new ScoreTag("scoinapiscore.scoin", strval(Loader::getInstance()->mySCoin($player)))))->call();
   }

}
