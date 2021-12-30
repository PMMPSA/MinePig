<?php

namespace BuyVIP\loc;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\{Player, Server};
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\item\Item;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as CP;
use pocketmine\math\Vector3;
use jojoe7777\FormAPI;

class Main extends PluginBase implements Listener{
	public $tag = "";
	
	public function onEnable(){
		$this->getLogger()->info(CP::GREEN . "Plugins Edit By MinePig");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch(strtolower($cmd->getName())){
			case "buyvip":
			if(!($sender instanceof Player)){
				$this->getLogger()->info(CP::RED . "Please Dont Use that command in here.");
				return true;
			}
			$tien = $this->coin->myCoin($sender);
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null) {
				}
				switch ($result) {
					case 0:
					break;
					case 1:
					$this->rank1($sender);
					break;
					case 2:
					$this->rank2($sender);
					break;
					case 3:
					$this->rank3($sender);
					break;
					case 4:
					$this->rank4($sender);
					break;
					case 5:
					$this->rank5($sender);
					break;
				}
			});
			
			$form->setTitle("§l§9⚫§a BUY Vip §9⚫");
			$form->setContent("§l§c↣§a Your Coin: §e". $tien);
			$form->addButton("§l§e• §l§cThoát §e•",0,"textures/other/axit");
			$form->addButton("§l§e•§9 VIP §6I §e•",1,"https://img.zing.vn/volamthuphi/images/data/event2015/vip1.png");
			$form->addButton("§l§e•§9 VIP §6II §e•",2,"https://img.zing.vn/volamthuphi/images/data/event2015/vip2.png");
			$form->addButton("§l§e•§9 VIP §6III §e•",3,"https://img.zing.vn/volamthuphi/images/data/event2015/vip3.png");
			$form->addButton("§l§e•§9 VIP §6IV §e•",4,"https://img.zing.vn/volamthuphi/images/data/event2015/vip4.png");;
			$form->addButton("§l§e•§9 VIP §6V §e•",5,"https://img.zing.vn/volamthuphi/images/data/event2015/vip5.png");
			$form->sendToPlayer($sender);
		}
		return true;
	}
	
	public function rank1(Player $sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			
			$result = $data;
			if ($result == null) {
			}
			switch ($result) {
				case 1:
				$coin = $this->coin->myCoin($sender);
				$cost = 10;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ".strtolower($sender->getName()). " VIPI 7");
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã mua thành công §dVIP§e I");
				}else{
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua VIP");
					return true;
				}
				break;
				case 2:
				$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã hủy mua VIP");
				break;
			}
		});
		
		$form->setTitle("§l§e•§d VIP §eI §e•");
		$form->setContent("§l§e•§b Bạn có muốn mua §dVIP§e I, với giá 10 Coin\n§l§e• Các Quyền Lợi §e•\n§l§c↣ §b/fly: §aBay\n§l§c↣ §b/feed: §aHồi thanh thức ăn");
		$form->setButton1("§l§e•§a Có §e•", 1);
		$form->setButton2("§l§e•§c Hủy §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	public function translateMessage($scut, $message){
		$message = str_replace($scut."{name}", $sender->getName(), $message);
		return $message;
	}
	
	public function onDeath(PlayerDeathEvent $ev){
		$player = $ev->getPlayer();
		$pp = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
		$rank = $this->pp->getUserDataMgr()->getGroup($player);
		if($rank == "VIPI" || $rank == "VIPII" || $rank == "VIPIII" || $rank == "VIPIV" || $rank == "VIPV"){
			$player->sendMessage("§l§e•§a Bạn đang dùng §f[ §dVIP§e ".$rank."§f ]§a nên sẻ không bị phạt xu khi chết!");
			return true;
		}
	}
	
	public function rank2(Player $sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			
			$result = $data;
			if ($result == null) {
			}
			switch ($result) {
				case 1:
				$coin = $this->coin->myCoin($sender);
				$cost = 20;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ". strtolower($sender->getName()). " VIPII 15");
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã mua thành công §dVIP§e II");
				}else{
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua VIP");
					return true;
				}
				break;
				case 2:
				$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã hủy mua VIP");
				break;
			}
		});
		
		$form->setTitle("§l§e•§d VIP §eII §e•");
		$form->setContent("§l§e•§b Bạn có muốn mua §dVIP§e II, với giá 20 Coin\n§l§e• Các Quyền Lợi §e•\n§l§c↣ §b/fly:§a Bay\n§l§c↣ §b/feed: §aHồi thanh thức ăn\n§l§c↣ §b/heal: §aHồi thanh máu\n§l§c↣ §b/tp:§a Dịch chuyển");
		$form->setButton1("§l§e•§a Có §e•", 1);
		$form->setButton2("§l§e•§c Hủy §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	public function rank3(Player $sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			
			$result = $data;
			if ($result == null) {
			}
			switch ($result) {
				case 1:
				$coin = $this->coin->myCoin($sender);
				$cost = 35;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ". strtolower($sender->getName()). " VIPIII 25");
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã mua thành công §dVIP§e III");
					$this->getItem($sender);
				}else{
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua VIP");
					return true;
				}
				break;
				case 2:
				$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã hủy mua VIP");
				break;
			}
		});
		
		$form->setTitle("§l§e•§d VIP §eIII §e•");
		$form->setContent("§l§e•§b Bạn có muốn mua §dVIP§e III, với giá 35 Coin\n§l§e• Các Quyền Lợi §e•\n§l§c↣ §b/fly: §aBay\n§l§c↣ §b/feed: §aHồi thanh thức ăn\n§l§c↣ §b/heal:§a Thanh hồi máu\n§l§c↣ §b/tp: §aDịch chuyển\n§l§c↣ §b/spider: §aLeo tường\n§l§c↣ §b/vanish:§a Tàn hình\n§l§c↣ §b/nick: §aĐổi tên");
		$form->setButton1("§l§e•§a Có §e•", 1);
		$form->setButton2("§l§e•§c Hủy §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	public function rank4($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			
			$result = $data;
			if ($result == null) {
			}
			switch ($result) {
				case 1:
				$coin = $this->coin->myCoin($sender);
				$cost = 50;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ". strtolower($sender->getName()). " VIPIV 45");
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã mua thành công §dVIP§e IV");
				}else{
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua VIP");
					return true;
				}
				break;
				case 2:
				$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã hủy mua VIP");
				break;
			}
		});
		
		$form->setTitle("§l§e•§d VIP §eIV §e•");
		$form->setContent("§l§e•§b Bạn có muốn mua §dVIP§e IV, với giá 50 Coin\n§l§e• Các Quyền Lợi §e•\n§l§c↣ §b/fly: §aBay\n§l§c↣ §b/feed: §aHồi thanh thức ăn\n§l§c↣ §b/heal:§a Thanh hồi máu\n§l§c↣ §b/tp: §aDịch chuyển\n§l§c↣ §b/spider: §aLeo tường\n§l§c↣ §b/vanish:§a Tàn hình\n§l§c↣ §b/nick: §aĐổi tên\n§l§c↣ §b/size: §aĐổi kích thước");
		$form->setButton1("§l§e•§a Có §e•", 1);
		$form->setButton2("§l§e•§c Hủy §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	public function rank5($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			
			$result = $data;
			if ($result == null) {
			}
			switch ($result) {
				case 1:
				$coin = $this->coin->myCoin($sender);
				$cost = 100;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setvip ". strtolower($sender->getName()). " VIPV 150");
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Bạn đã mua thành công §dVIP§e V");
				}else{
					$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua VIP");
					return true;
				}
				break;
				case 2:
				$sender->sendMessage($this->tag . "§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã hủy mua VIP");
				break;
			}
		});
		
		$form->setTitle("§l§e•§d VIP §eV §e•");
		$form->setContent("§l§e•§b Bạn có muốn mua §dVIP§e V, với giá 100 Coin\n§l§e• Các Quyền Lợi §e•\n§l§c↣ §b/fly: §aBay\n§l§c↣ §b/feed: §aHồi thanh thức ăn\n§l§c↣ §b/heal:§a Thanh hồi máu\n§l§c↣ §b/tp: §aDịch chuyển\n§l§c↣ §b/spider: §aLeo tường\n§l§c↣ §b/vanish:§a Tàn hình\n§l§c↣ §b/nick: §aĐổi tên\n§l§c↣ §b/size: §aĐổi kích thước");
		$form->setButton1("§l§e•§a Có §e•", 1);
		$form->setButton2("§l§e•§c Hủy §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	public function processor(Player $player, string $string): string{
		$string = str_replace("{name}", $player->getName(), $string);
		return $string;
	}
}