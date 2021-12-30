<?php

namespace MinePIG;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\entity\Effect;
use MinePIG\Main;

Class PopupTask extends Task{


    public function __construct(Main $plugin, Player $player){

        $this->plugin = $plugin;
        $this->player = $player;
    }

	public function onRun($currentTick){
		foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
		  $level = $this->plugin->getLevel($player);
            $exp = $this->plugin->getExp($player);
            $next = $this->plugin->getNextExp($player);
            $namepic = $this->plugin->getNamePickaxe($player);
		  $i = $player->getInventory()->getItemInHand();
		  $hand = $i->getCustomName();
            $it = explode(" ", $hand);
            $damage = $i->getDamage();

				if($it[0] == "§e|§b"){
					if($damage > 30){
						 $i->setDamage(0);
             		           $player->getInventory()->setItemInHand($i);
		                     $player->sendMessage("§6⚒§e Cúp của bạn đã được sửa chữa!");
						}
						$player->sendPopup("§e⎳§c CÚP:§b ＭＩＮＥ§d ＰＩＧ§6 ❖\n§a⇀§9 Kinh nghiệm:§e ".$exp."/§6".$next."§f |§6 Cấp cúp:§4 ".$level);
					} else{
						$this->plugin->getServer()->getScheduler()->cancelTask($this->getTaskId());
					}
		}
	}
}