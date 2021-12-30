<?php

namespace LvPickaxe;

use LvPickaxe\Loader;
use LvPickaxe\Event\TrophyEvent;

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
    public static function hasData(Player $player) : bool
    {
        return self::$plugin->config->exists($player->getName());
    }
        
    public static function createData(Player $player) : void
    {
        
       self::$plugin->config->set($player->getName(), ["Level" => 1, "Current" => 0, "Max" => 120]);
       self::$plugin->config->save();
        
    }
        
    public static function getLevel(Player $player)
: Int
    {
        return self::$plugin->config->get($player->getName())["Level"];
    }
    
    
    public static function setLevel(Player $player, Int $int) : void
    {
              
        $value = ((self::$plugin->config->get($player->getName())["Level"] + 1) * 120);
        
        self::$plugin->config->set($player->getName(), ["Level" => $int, "Current" => 0, "Max" => $value]);
        self::$plugin->config->save();
        
        $event = new TrophyEvent($player);
        self::$plugin->getServer()->getPluginManager()->callEvent($event);

    }
        
    public static function getCurrent(Player $player) : Int
    {
       return self::$plugin->config->get($player->getName())["Current"];
   }
      
    public static function addCurrent(Player $player, Int $int) : void 
    {
        self::$plugin->config->set($player->getName(), ["Level" => self::$plugin->config->get($player->getName())["Level"], "Current" => self::$plugin->config->get($player->getName())["Current"] + $int, "Max" => self::$plugin->config->get($player->getName())["Max"]]);
        self::$plugin->config->save();
        
        $event = new TrophyEvent($player);
        self::$plugin->getServer()->getPluginManager()->callEvent($event);
    }
        
    public static function getMax(Player $player) : Int
    {
        return self::$plugin->config->get($player->getName())["Max"];
    }
        
    public static function getNamePickaxe(Player $player) : String {
        
        $prefix = "§r§l§c⇀ §6VTL§e Trophy§8 |§b ".$player->getName()." §9Lvl§a ".self::getLevel($player);
        
        return $prefix;
        }
        
    public static function getLorePickaxe(Player $player) : String
    {
        
        $strig = "Không có Enchantment.";
        
        if(self::getLevel($player) > 1 && self::getLevel($player) < 21){
           $strig = "§f[§a*§f]§e Cường hoá bổ sung:\n§bHiệu năng ".(self::getLevel($player)/2)."\n§7Không bị phá hủy\n§7Jackpot";
           } elseif(self::getLevel($player) > 20 && self::getLevel($player) < 40){
            $strig = "§f[§a*§f]§e Cường hoá bổ sung:\n§bHiệu năng ".(self::getLevel($player)/2)."\n§bKhông bị phá hủy ".(self::getLevel($player)/2)."\n§7Jackpot";
            } else{
                $strig = "§f[§a*§f]§e Cường hoá bổ sung:\n§bHiệu năng ".(self::getLevel($player)/2)."\n§bKhông bị phá hủy ".(self::getLevel($player)/2)."\n§aJackpot ".(self::getLevel($player)/2);
                }
                
                
        
        $prefix = "§r§l-------\n§c⇀§6 Lvl:§e ".self::getLevel($player)."\n§c⇀§b Experience:§e ".self::getCurrent($player)."§f/§6".self::getMax($player)."\n§l§f-------\n".$strig;
        
        return $prefix;
        
        }
        
    }