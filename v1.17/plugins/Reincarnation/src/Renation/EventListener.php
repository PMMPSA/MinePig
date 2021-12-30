<?php

namespace Renation;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\EcoAPIScore\Main;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;

use Renation\Loader;
use Renation\UsersData;
use Renation\Event\PlayerLevelChangeEvent;

use function is_null;
use function strval;

class EventListener implements Listener
{
   
   
   public function __construct(Loader $plugin)
   {
      $this->plugin = $plugin;
   }
   
   public function getPlugin() : Loader
   {
      return $this->plugin;
   }
   
   public function onJoin(PlayerJoinEvent $event)
   {
      $player = $event->getPlayer();
      if(!UsersData::hasData($player)) {
         UsersData::createData($player);
      }else {
          $this->getPlugin()->syncData($player); 
      }
   }
   
   public function onRespawn(PlayerRespawnEvent $event) 
   {
      $player = $event->getPlayer();
      $this->getPlugin()->syncData($player);
   }
   
   public function onChangeMode(PlayerGameModeChangeEvent $event)
   {
      if($event->getNewGamemode() == 0) {
         $this->getPlugin()->syncData($event->getPlayer());
      }
   }
   
   public function onBreak(BlockBreakEvent $event)
   {
      $block = $event->getBlock();
      $player = $event->getPlayer();
      $int = (UsersData::getSaleItem($player) * count($event->getDrops()));
      $drops = array();     
      
      foreach($event->getDrops() as $item){
         $drops[] = Item::get($item->getId(), 0, $int);
      }
      
      $event->setDrops($drops);
   }
   
   public function onChange(PlayerLevelChangeEvent $event)
   {
      $player = $event->getPlayer();
      
      (new PlayerTagUpdateEvent($player, new ScoreTag("chuyensinh.level", strval(UsersData::getLevel($player)))))->call();
   }
}