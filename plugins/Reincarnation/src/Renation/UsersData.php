<?php

namespace Renation;

use Renation\Loader;
use Renation\Event\PlayerLevelChangeEvent;

use pocketmine\Player;

class UsersData
{
   
   private static $plugin;
   
   public static function init(Loader $plugin) : void
   {
      self::$plugin = $plugin;
   }
   
   public static function getPlugin() : Loader 
   {
      return self::$plugin;
   }
   
   public static function hasData(Player $player) 
   {
      return self::$plugin->database->exists($player->getName());
   }
   
   public static function createData(Player $player) : void
   {
      self::$plugin->database->set($player->getName(), 0);
      self::$plugin->database->save();
   }
   
   public static function getLevel(Player $player) : Int
   {
      return self::$plugin->database->get($player->getName());
   }
   
   public static function setLevel(Player $player, Int $int) : void
   {
      self::$plugin->database->set($player->getName(), $int);
      self::$plugin->database->save();
      
      $event = new PlayerLevelChangeEvent($player);
      
      self::$plugin->getServer()->getPluginManager()->callEvent($event);
   }
   
   public static function getSaleItem(Player $player) : Int 
   {
      $i = 1;     
      if(self::getLevel($player) > 0) {
         $i += self::getLevel($player);
      }       
      return $i;
   }
   
   
  
}