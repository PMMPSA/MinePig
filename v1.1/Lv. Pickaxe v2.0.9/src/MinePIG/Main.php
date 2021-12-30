<?php

namespace MinePIG;

/*
* Lv. Pickaxe Plugin
* Support PocketMine MCPE 1.1.5
* Plugin-Market: TinhYTGaming
* Plugin Price: 60.000 VND
* Type: Viettel
*
*/

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use MinePIG\PopupTask;

class Main extends PluginBase implements Listener{
	
		public function onEnable(){
			$this->getServer()->getPluginManager()->registerEvents($this, $this);
			$this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
			@mkdir($this->getDataFolder());
				$this->config = new Config($this->getDataFolder()."players.yml",Config::YAML);
					$this->getLogger()->info("§9Enable!");
		}
		
		public function onJoin(PlayerJoinEvent $event){
			$player = $event->getPlayer();
			$user = $event->getPlayer()->getName();
				if(!$this->config->exists(strtolower($user))){
					
					$this->getLogger()->notice("§cKhông tìm thấy dữ liệu của§e ".$user.".§6 Đang tạo dữ liệu...");
					$this->getLogger()->info("§e§lĐã tạo dữ liệu!");
					
					$item = Item::get(274, 0, 1);
					$item->setCustomName($this->getNamePickaxe($user));
					$item->setLore(array($this->getLore($user)));
					
					$player->getInventory()->addItem($item);
					$player->sendMessage("§eBạn nhận được§c CÚP§b ＭＩＮＥ§d ＰＩＧ.§6 Hãy biến nó thành chiếc CÚP§c VIP§6 Nhất Server!");
					
					$this->config->set(strtolower($user), ["Level" => 1, "exp" => 0, "nextexp" => 100]);
					$this->config->save();
					}
				return true;
		}
		public function getNamePickaxe($player){
			if($player instanceof Player){
				$player = $player->getName();
				}
				$brv = "§e|§b ＭＩＮＥ§d ＰＩＧ§6 ❖§c ".$this->config->get(strtolower($player))["Level"]."§e ⇀§4 ".$player;
			return $brv;	
		}
		public function getLore($player){
			if($player instanceof Player){
				$player = $player->getName();
				}
				$player = strtolower($player);
				$lore = "§r§e⎳§c CÚP:§b ＭＩＮＥ §dＰＩＧ§6 ❖\n\n§e⚒§6 Chủ sỡ hữu:§4 ".$player."\n\n§e⇀§6 Cấp độ:§b ".$this->config->get($player)["Level"]."\n\n§l§eCÚP VIP Được chế tạo từ§b ＭＩＮＥ§d ＰＩＧ";
			return $lore;				
		}
		public function onHeld(PlayerItemHeldEvent $event){
			
			$task = new PopupTask($this, $event->getPlayer());
			$this->task[$event->getPlayer()->getId()] = $task;
				$this->getServer()->getScheduler()->scheduleRepeatingTask($task, 1);
				
				$player = $event->getPlayer();
				$item = $player->getInventory()->getItemInHand();
				
				if(isset($this->need[$player->getName()])){
					
					$item->setCustomName($this->getNamePickaxe($player));
					$item->setLore(array($this->getLore($player)));
					
					if($this->getLevel($player) == 3){
						
						$item = Item::get(257, 0, 1);
						$item->setCustomName($this->getNamePickaxe($player));
						$item->setLore(array($this->getLore($player)));
						$player->sendMessage("§eCÚP Của bạn đã được lên cúp§c Sắt!");
					
						
						} elseif($this->getLevel($player) == 20){
							
							$item = Item::get(278, 0, 1);
							$item->setCustomName($this->getNamePickaxe($player));
							$this->setLore(array($this->getLore($player)));
							$player->sendMessage("§eCÚP Của bạn đã được lên cúp§b Kim cương!");
							
							}
							if(in_array($this->getLevel($player), array(10, 20))){						
								}
								if(in_array($this->getLevel($player), array(2, 4, 6, 8, 10, 12, 14, 16, 18, 20, 22, 24, 26, 28, 30, 32, 34, 36, 38, 40, 42, 44, 46, 48, 50, 52, 54, 56, 58, 60))){
									$enchant = Enchantment::getEnchantment(17)->setLevel($this->getLevel($player)/2);
									$item->addEnchantment($enchant);
									$player->sendMessage("§eCÚP Của bạn đã được cường hoá§6 Độ cứng§b ".($this->getLevel($player)/2).".");
									} elseif(in_array($this->getLevel($player), array(3, 5, 7, 9, 11, 13, 15, 17, 19, 21, 23, 25, 27, 29, 31, 33, 35, 37, 39, 41, 43, 45, 47, 49, 51, 53, 55, 57, 59))){
										$enchant = Enchantment::getEnchantment(15)->setLevel($this->getLevel($player)/3);
										$item->addEnchantment($enchant);
										$player->sendMessage("§eCÚP Của bạn đã được cường hoá§6 Hiệu năng§b ".($this->getLevel($player)/3).".");
										} 								
											$player->getInventory()->setItemInHand($item);
												unset($this->need[$player->getName()]);											
					}			
				}
					
			public function onBreak(BlockBreakEvent $event){
				$player = $event->getPlayer();
				$item = $player->getInventory()->getItemInHand();
				$icn = $item->getCustomName();
				$pas = explode(" ", $icn);
					if($pas[0] == "§e|§b"){
			if(!in_array($player->getName(), explode(" ", $icn))){
				$event->setCancelled(true);
				$player->sendMessage("§cKhông phải cúp của bạn!");
	
				}
			}
					if(!$event->isCancelled()){
						if($pas[0] == "§e|§b"){
							$name = strtolower($player->getName());
							$n = $this->config->get($name);
							$block = $event->getBlock();
								
								switch($block->getId()){
									case "1":
									$this->addExp($player, 4);
									break;
									case "4":
									$this->addExp($player, 1);
									break;
									case "14":
									$this->addExp($player, 4);
									break;
									case "15":
									$this->addExp($player, 3);
									break;
									case "16":
									$this->addExp($player, 3);
									break;
									case "21":
									$this->addExp($player, 4);
									break;
									case "59":
									$this->addExp($player, 7);
									break;
									case "129":
									$this->addExp($player, 10);
									break;								
									}
										$player->sendPopup("§e⎳§c CÚP:§b ＭＩＮＥ§d ＰＩＧ§6 ❖\n§a⇀§9 Kinh nghiệm:§e ".$n["exp"]."§f/§6".$n["nextexp"]."§f |§6 Cấp độ:§4 ".$n["Level"]);
									
											if($this->getExp($player) >= $this->getNextExp($player)){
												$this->setLevel($player, $this->getLevel($player) + 1);
												$player->sendMessage("§a✔§l§9 Cúp của bạn đã lên cấp§e ".$this->getLevel($player)."!");
												$player->addTitle("§a❖§l§9 Lên cấp§e ".$this->getLevel($player));
												$this->getServer()->broadcastMessage("§b♪ §eChúc mừng người chơi§c ".$player->getName()."§e Đã lên cúp§6 ".$this->getLevel($player)."!");
													if(in_array($this->getLevel($player), array(3, 20, 40, 80, 200))){
														$this->EconomyAPI->addMoney($player->getName(), $this->getLevel($player)*1000);
														$player->sendMessage("[§a+§f]§e Bạn nhận được§6 ".($this->getLevel($player)*1000)."§e Xu. Quà tặng cúp cấp§6 ".$this->getLevel($player).".");
														}
														$this->need[$player->getName()] = true;
												}						
											}
										}
									}
		public function onCommand(CommandSender $sender, Command $command, $label, array $args){
				switch($command->getName()){
					case "givecup":
				if($sender->isOp()){
					if(!isset($args[0])){
						$sender->sendMessage("§cSử dụng lệnh§e /givecup <người chơi>§c Đưa cúp cho người chơi.");
						return true;
                  }
					$player = $this->getServer()->getPlayer($args[0]);
					if(!$player){
						$sender->sendMessage("§cKhông tìm thấy người chơi này!");
						return true;
							}
					if($this->getLevel($player) < 3){
						$item = Item::get(274, 0, 1);
						$item->setCustomName($this->getNamePickaxe($player));
						$item->setLore(array($this->getLore($player)));
							} elseif($this->getLevel($player) >= 3 && $this->getLevel($player) < 20){
								$item = Item::get(257, 0, 1);
								$item->setCustomName($this->getNamePickaxe($player));
								$item->setLore(array($this->getLore($player)));
								} elseif($this->getLevel($player) >= 20){
									$item = Item::get(278, 0, 1);
									$item->setCustomName($this->getNamePickaxe($player));
									$item->setLore(array($this->getLore($player)));
									}
									$player->getInventory()->addItem($item);
										$player->sendMessage("§eCÚP§b ＭＩＮＥ §dＰＩＧ...");
										$sender->sendMessage("§a✔§e Đưa thành công!");										
					} else{
						$sender->sendMessage("§cKhông có Quyền!");
					}
					break;
						case "topcup":
							$max = 0;
							foreach($this->config->getAll() as $c){
								$max += count($c);
								}
							$page = ceil($max /5);
							$page = array_shift($args);
							$page = max(1, $page);
							$page = min($max, $page);
							$page = (int)$page;
							$sender->sendMessage("§e❖§b Bảng xếp hạng§c CÚP:§f <".$page."/".$max.">");
							$aa = $this->config->getAll();
							arsort($aa);
							$i = 0;
							foreach($aa as $b=>$a){
								if(($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4){
									$i1 = $i + 1;
									$c = $this->config->get(strtolower($b))["Level"];
									$sender->sendMessage("§6⎳§c TOP§e ".$i1."§4 ".$b.":§9 ".$c);
									}
								$i++;
							}
						return true;						
				}
			}
			public function getLevel($player){
		if($player instanceof Player){
			$player = $player->getName();
			}
			$level = $this->config->get(strtolower($player))["Level"];
			return $level;
		}
	public function setLevel($player, $level){
		if($player instanceof Player){
			$name = $player->getName();
		}

		$nextexp = ($this->getLevel($player)+1)*50;
		$this->config->set(strtolower($name), ["Level" => $level, "exp" => 0, "nextexp" => $nextexp]);
		$this->config->save();
	}
	public function setNextExp($player, $exp){
		if($player instanceof Player){
			$player = $player->getName();
		}

		$player = strtolower($player);
		$lv = $this->config->get($player)["nextexp"] + $exp;
		$this->config->set($player, ["Level" => $this->config->get($player)["Level"], "exp" => $this->config->get($player)["exp"], "nextexp" => $lv]);
		$this->config->save();
	}
	public function getExp($player){
		if($player instanceof Player){
			$player = $player->getName();
		}

		$player = strtolower($player);
		$e = $this->config->get($player)["exp"];
		return $e;
	}
	public function getNextExp($player){
		if($player instanceof Player){
			$player = $player->getName();
		}

		$player = strtolower($player);
		$lv = $this->config->get($player)["nextexp"];
		return $lv;
	}
	public function addExp($player, $exp){
		if($player instanceof Player){
			$player = $player->getName();
		}

		$player = strtolower($player);
		$e = $this->config->get($player)["exp"];
		$lv = $this->config->get($player)["Level"];
		$this->config->set($player, ["Level" => $lv, "exp" => $e + $exp, "nextexp" => $this->getNextExp($player)]);
		$this->config->save();
       	}
		}	
		