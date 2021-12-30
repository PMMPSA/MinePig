<?php

namespace VIPJoin;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\player\PlayerJoinEvent;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
     $this->pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
		}
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
      $group = $this->pp->getUserDataMgr()->getGroup($player)->getName();
			if($player->hasPermission("vipjoin.use")){
				$this->getServer()->broadcastMessage("§6❖ §c ".$group."§b ".$player->getName()."§e Đã vào Server!");
			}
		}
	}