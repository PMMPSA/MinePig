<?php

namespace NamVN;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;

################API#################
use onebone\economyapi\EconomyAPI;
use onebone\coinapi\CoinAPI;
###################################

use pocketmine\{Player, Server};
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
	public $tag = "§l§6BuyCoin§r";
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getServer()->getLogger()->info($this->tag . "§e Plugin Code Việt Hóa 100℅ By NamVN");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
	  switch($cmd->getName()){
			case "buycoin":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("You cannot use this command in Console! ");
				return true;
			}else{
			  $this->openMyForm($sender);
			}
			break;
		}
		return true;
	}
	
	public function openMyForm(Player $sender){
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createSimpleForm(function (Player $sender, ?int $data = null){
				$result = $data;
				if ($result === null){
				  return;
				}
				switch ($result) {
					case 0:
            $this->coin($sender);
          break;
				}
			});
          $coin = CoinAPI::getInstance()->myCoin($sender);
          $money = EconomyAPI::getInstance()->myMoney($sender);
          $form->setTitle("§l§e•§c Buy Coin §e•");
		  $form->setContent("§l§f• §cYour Money:§e $money §f| §cYour Coin:§e $coin");
          $form->addButton("§l§e•§c Buy Coin §6(150.000Xu) §e•");
		  $form->sendToPlayer($sender);
	}
	
    public function coin($sender){
		$form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function(Player $sender, $data){
			if($data == null){
				return;
			}
			$data[0] >= 1;
			$tien = $this->money->myMoney($sender);
			if(!isset($data[0]) || !is_numeric($data[0])){
				$sender->sendMessage("§l§cVui lòng BuyCoin bằng số");
			    return false;
			}
			if($tien >= $data[0]*150000){
				$sender->sendMessage("§l§aBạn đã mua §c" . $data[0] . "§e Coin§a thành công!");
				$this->money->reduceMoney($sender, $data[0]*150000);
				$this->coin->addCoin($sender, $data[0]);
			}else{
				$sender->sendMessage("§l§c Bạn không đủ tiền để đổi Coin!");
				return true;
			}
		});
		$form->setTitle("§l§e•§c Buy Coin §e•");
		$form->addInput("§l§f•§a Ghi số Coin cần Mua", "§f0");
		$form->sendToPlayer($sender);
	}
} 
#Vui lòng không chỉnh tên Author
#Bạn chỉ có thể edit Format chữ, số tiền mua point