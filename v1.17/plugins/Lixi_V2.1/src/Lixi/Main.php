<?php

namespace Lixi;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		}
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()) {
			case "lixi":
         if(!isset($args[0])){
             $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §l§6Sử dụng: §b/lixi §e<§a Coin §e>§b để lì xì cho toàn máy chủ");
               return true;
               }
				if($this->coin->myCoin($sender->getName()) >= count($this->getServer()->getOnlinePlayers())*$args[0]){
				foreach($this->getServer()->getOnlinePlayers() as $p){
              $this->coin->reduceCoin($sender->getName(), $args[0]);
					$this->coin->addCoin($p->getName(), $args[0]);
					$sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã tặng cho người chơi§a ".$p->getName()."§e ".$args[0]." Coin!");
              $p->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Người chơi§6 ".$sender->getName()."§b đã tặng bạn§e ".$args[0]." Coin!");
					}
				} else{
					$sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Đang có §e".(count($this->getServer()->getOnlinePlayers()))." §cnguời chơi. Bạn không có đủ Coin!");
}
return true;
}
}
}