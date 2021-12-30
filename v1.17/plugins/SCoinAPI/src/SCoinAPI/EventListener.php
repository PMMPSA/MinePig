<?php

namespace SCoinAPI;

use SCoinAPI\Loader;
use SCoinAPI\Event\PlayerChangeSCoinEvent;
use pocketmine\Player;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;


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

    public function onChange(PlayerChangeSCoinEvent $event)
    {
       $player = $event->getPlayer();
    }

    public function onJoin(PlayerJoinEvent $event)
    {
       $player = $event->getPlayer();
       
       if(!$this->getPlugin()->config->exists($player->getName())) {
             $this->getPlugin()->config->set($player->getName(), 0);
             $this->getPlugin()->config->save();
             $eve = new PlayerChangeSCoinEvent($player);
             $this->getPlugin()->getServer()->getPluginManager()->callEvent($eve);
          } else {
             $eve = new PlayerChangeSCoinEvent($player);
             $this->getPlugin()->getServer()->getPluginManager()->callEvent($eve);
          }
    }
    

}