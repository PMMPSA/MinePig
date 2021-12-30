<?php

namespace BL;

use pocketmine\utils\TextFormat as __;
use pocketmine\utils\Random;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use onebone\economyapi\EconomyAPI;
use onebone\coinapi\CoinAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\PopSound;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\level\sound\GhastSound;
use pocketmine\level\sound\EndermanTeleportSound;

class Main extends PluginBase {
	
	public $eco;
	
	public function onEnable(){
		$this->eco = EconomyAPI::getInstance();
		$this->coin = CoinAPI::getInstance();
		$this->getLogger()->info("Buôn Lậu Đã Được Bật");
	}
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new ExpPickupSound($player));                                                
	}
	public function onDeath(PlayerDeathEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new GhastSound($player));
		$player->getlevel()->addSound(new ExpPickupSound($player));
	}
	public function onQuit(PlayerQuitEvent $event){
		$player = $event->getPlayer();
		$player->getlevel()->addSound(new ExpPickupSound($player));
	}
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		
		if($cmd->getName() == "buonlau") {
			if($sender instanceof Player){
				$rand = mt_rand(1, 80);
				$sender->getLevel()->addSound(new AnvilUseSound($sender));
				$inv = $sender->getInventory();
			 if ($sender->getInventory()->contains(Item::get(338, 0, 32)) === true) {
				$inv->removeItem(Item::get(338,0,32));
				switch($rand){
					case 1:
					$this->eco->addMoney($sender->getName(), 1000);
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§a đã buôn lậu thành công\n\n§l§e❖§a Phần Thưởng:§c 1.000 Tiền\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
		            break;
					case 18:
					$this->eco->addMoney($sender->getName(), 100000);
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§a đã buôn lậu thành công\n\n§l§e❖§a Phần Thưởng:§c 100.000 Tiền\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
					case 31:
					$this->eco->addMoney($sender->getName(), 10000);
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§a đã buôn lậu thành công\n\n§l§e❖§a Phần Thưởng:§c 10.000 Tiền\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
					case 45:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
					case 50:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
					case 55:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					case 65:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					case 70:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					case 75:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					case 80:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					case 85:
					$this->coin->addCoin($sender->getName(), 5);
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§a đã buôn lậu thành công\n\n§l§e❖§a Phần Thưởng:§c 5 Coin\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
					default:
					$this->getServer()->broadcastMessage("§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛\n§r\n§l§e❖§c ". $sender->getName() ."§c đã buôn lậu thất bại\n\n§l§e❖§a Hãy Báo Cảnh Sát §eVictory Of Legend \n\n§l§f⬛§b⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛⬛§f⬛");
					break;
				}
				}else{
					$sender->sendMessage("§l§6[§e Mine§a PIG §6]§f ↣§c Bạn cần §e32§c mía để buôn lậu");
				}
			}
			return false; 
		}
	}
}