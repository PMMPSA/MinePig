<?php

namespace Coins;

/** Author: KAIDOMC */

use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		@mkdir($this->getDataFolder());
		$this->coins = new Config($this->getDataFolder()."coins.yml",Config::YAML);
		$this->getLogger()->info("§aLoading...");
		}
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
			if(!$this->coins->exists(strtolower($player->getName()))){
				$this->coins->set(strtolower($player->getName()), 0);
				$this->coins->save;
			}
			return true;
		}
	public function onCommand(CommandSender $sender, Command $cmd, $label, array $args){
		switch($cmd->getName()){
			case "mycoins":
			$sender->sendMessage("§e❖§9 Số Coins:§a ".$this->coins->get($sender->getName()));
			break;		
			case "seecoins":
			if(!isset($args[0])){
				$sender->sendMessage("§e⇀§6 Nhập lệnh§f /seecoins <player>.");
				return true;
				}
				$player = $this->getServer()->getPlayer($args[0]);
				if(!$player){
					$sender->sendMessage("§cKhông tìm thấy người chơi.");
					return true;
					}
					$sender->sendMessage("§e⇀§9 Số coins của§6 ".$player->getName().":§c ".$this->coins->get($player->getName()));
					$player->sendMessage("§6❖§c ".$sender->getName()."§e Đang xem số coins của bạn!");
			break;
			case "paycoins":
				if(!isset($args[0])){
					$sender->sendMessage("§e⇀§6 Nhập lệnh§f /paycoins <player> <amount>.");
					return true;
					}
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player){
						$sender->sendMessage("§cKhông tìm thấy người chơi.");
						return true;
						}
						if(!is_numeric($args[1])){
							$sender->sendMessage("§e⇀§6 Nhập lệnh§f /paycoins <player> <amount>.");
							return true;
							}
							if($this->coins->get($sender->getName()) >= $args[1]){
							$name = $player->getName();
							$this->coins->set($name, $this->coins->get($name) + $args[1]);
							$this->coins->set($sender->getName(), $this->coins->get($sender->getName()) - $args[1]);
							$this->coins->save();
							$player->sendMessage("§a✔§b Bạn nhận được§6 ".$args[1]."§b Coins từ người chơi§c ".$sender->getName().".");
							$sender->sendMessage("§a✔§b Gửi thành công§6 ".$args[1]."§b Coins cho người chơi§c ".$player->getName().".");
							} else{
								$sender->sendMessage("§cKhông đủ Lượng!");
								break;
								}
								break;
			case "topcoins":
				
			$max = 0;
			foreach($this->coins->getAll() as $c){
			$max += count($c);
			}
			$max = ceil(($max / 5));
			$page = array_shift($args);
			$page = max(1, $page);
			$page = min($max, $page);
			$page = (int)$page;
				$sender->sendMessage("§e❖§9 Bảng xếp hạng §6Coins: §f<§6".$page."§f/§c".$max."§f>");
				$aa = $this->coins->getAll();
					arsort($aa);
					$i = 1;
							foreach($aa as $b=>$a){
									if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
										$i1 = $i + 1;
										$c = $this->coins->get($b);
										$sender->sendMessage("§f▶§c TOP§e ".$i."§b ".$b.":§6 ".$c);
										}
									$i++;
							}
							break;
				case "setcoins":
				if($sender->isOp() || $sender->hasPermission("setcoins.command")){
					if(!isset($args[0])){
						$sender->sendMessage("§e⇀§6 Nhập lệnh§f /setcoins <player> <amount>.");
						return true;
						}
						$player = $this->getServer()->getPlayer($args[0]);
						if(!$player){
							$sender->sendMessage("§cKhông tìn thấy người chơi.");
							return true;
							}
							if(!is_numeric($args[1])){
								$sender->sendMessage("§§e⇀§6 Nhập lệnh§f /setcoins <player> <amount>.");
								return true;
								}
								$this->coins->set($player->getName(), $args[1]);
								$this->coins->save();
								$sender->sendMessage("§a✔§b Số Coins của§6 ".$player->getName()."§b Đã được đặt thành§c ".$args[1]."§b Coins!");
								} else{
									$sender->sendMessage("§cKhông có quyền!");
									break;
								}
								break;
				case "addcoins":
				if($sender->isOp() || $sender->hasPermission("addcoins.command")){
					if(!isset($args[0])){
						$sender->sendMessage("§e⇀§6 Nhập lệnh§f /addcoins <player> <amount>.");
						return true;
							}			
							$player = $this->getServer()->getPlayer($args[0]);
							if(!$player){
								$sender->sendMessage("§cKhông tìm thấy người chơi.");
								return true;
								}
								if(!is_numeric($args[1])){
									$sender->sendMessage("§e⇀§6 Nhập lệnh§f /addcoins <player> <amount>.");
									return true;
									}
									$this->coins->set($player->getName(), $this->coins->get($player->getName()) + $args[1]);
									$this->coins->save();
									$sender->sendMessage("§a✔§b Đã thêm thành công§6 ".$args[1]."§b Coins vào tài khoản của§e ".$player->getName().".");
									$player->sendMessage("§c❖§b Số Coins của bạn đã được thêm§6 ".$args[1]."§b Coins!");
					} else{
						$sender->sendMessage("§cKhông có Quyền!");
						break;
						}
						break;
			case "takecoins":
			if($sender->isOp() || $sender->hasPermission("takecoins.command")){
				if(!isset($args[0])){
					$sender->sendMessage("§e⇀§6 Nhập lệnh§f /takecoins <player> <amount>.");
					return true;
					}
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player){
						$sender->sendMessage("§cKhông tìm thấy người chơi.");
						return true;
						}
						if(!is_numeric($args[1])){
							$sender->sendMessage("§e⇀§6 Nhập lệnh§f /takecoins <player> <amount>.");
							return true;
							}
							$this->coins->set($player->getName(), $this->coins->get($player->getName()) - $args[1]);
							$this->coins->save();
							 $sender->sendMessage("§a✔§b Lấy thành công§c ".$args[1]."§b Coins của§6 ".$player->getName().".");
							# $player->sendMessage();
				} else{
					$sender->sendMessage("§cKhông có Quyền!");
					break;
				}
				break;
			case "helpcoins":
			 if($sender->isOp() || $sender->hasPermission("help.command")){
				$sender->sendMessage("§6❖§e Command Coins:");
				$sender->sendMessage("§9♪§a /mycoins§f -§b Xem số Coins hiện có.");
				$sender->sendMessage("§9♪§a /paycoins§f -§b Đưa số Coins của bạn cho người chơi.");
				$sender->sendMessage("§9♪§a /topcoins§f -§b Bảng xếp hạng Coins.");
				$sender->sendMessage("§e♪§a /setcoins§f -§b Đặt số Coins cho người chơi.");
				$sender->sendMessage("§e♪§a /addcoins§f -§b Thêm số Coins cho người chơi.");
				$sender->sendMessage("§e♪§a /takecoins§f -§b Lấy số Coins của người chơi.");
				}	else{
				$sender->sendMessage("§6❖§e Command Coins:");
				$sender->sendMessage("§9♪§a /mycoins§f -§b Xem số Coins hiện có.");
				$sender->sendMessage("§9♪§a /paycoins§f -§b Đưa số Coins của bạn cho người chơi.");
				$sender->sendMessage("§9♪§a /topcoins§f -§b Bảng xếp hạng Coins.");
					return true;
				}
				return true;
			}
		}
	public function addCoins($player, $coins){
     if($player instanceof Player){
         $player = $player->getName();
         }
         $this->coins = new Config($this->getDataFolder()."coins.yml",Config::YAML);
         $this->coins->set($player, (int)$this->coins->get($player) + $coins);
         $this->coins->save();
  }
   public function reduceCoins($player, $coins){
      if($player instanceof Player){
          $player = $player->getName();
    }
      if($this->myCoins($player) - $coins < 0){
          return true;
          }
          $this->coins = new Config($this->getDataFolder()."coins.yml",Config::YAML);
          $this->coins->set($player, (int)$this->coins->get($player) - $coins);
          $this->coins->save();
}
	public function myCoins($player){
		if($player instanceof Player){
			$player = $player->getName();
			}
			$coins = $this->coins->get($player);
				return $coins;
				}
	}