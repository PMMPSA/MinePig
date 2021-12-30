<?php

namespace KaYuu081\DoiXu;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;
use pocketmine\{Player, Server};
use jojoe7777\FormAPI;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase{
	
	public function onEnable(){
		$this->getServer()->getLogger()->info("BuyXu Đã Bật");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
	}
	
	public function onLoad(): void{
		$this->getServer()->getLogger()->notice("Loading Data.....");
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()){
			case "buyxu":
			if(!($sender instanceof Player)){
				$this->getLogger()->notice("Please Dont Use that command in here.");
				return true;
			}
			$money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
			$coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI")->myCoin($sender);
			$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
			$form = $api->createSimpleForm(Function (Player $sender, $data){
				
				$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 0:
					$this->goixu1($sender);
					break;
					case 1:
					$this->goixu2($sender);
					break;
					case 2:
					$this->goixu3($sender);
					break;
					case 3:
					$this->goixu4($sender);
					break;
					case 4:
					$this->goixu5($sender);
					break;
				}
			});
			
			$form->setTitle("§l§f•§9 Buy Xu §f•");
			$form->setContent("§l§f•§c Coin của bạn: §e" .$coin."\n§l§f•§c Tiền của bạn: §e".$money);
			$form->addButton("§l§f•§9 Gói Xu I §f•", 0);
			$form->addButton("§l§f•§9 Gói Xu II §f•", 1);
			$form->addButton("§l§f•§9 Gói Xu III §f•", 2);
			$form->addButton("§l§f•§9 Gói Xu IV §f•", 3);
			$form->addButton("§l§f•§9 Gói Xu V §f•", 4);
			$form->sendToPlayer($sender);
		}
		return true;
	}
	
	public function goixu1($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 1:
			    $coin = $this->coin->myCoin($sender);
				$cost = 1;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->money->addMoney($sender, 100000);
					$sender->sendMessage("§l§c•§e Bạn đã mua thành công gói xu 1");
				}else{
					$sender->sendPopup("§l§e•§c Bạn Không Đủ Coin §e•");
					return true;
				}
			 break;
			         case 2:
					$command = "buyxu";
					$this->getServer()->getCommandMap()->dispatch($sender, $command);
					 break;
				}
			 
		});
		$form->setTitle("§l§f•§9 Gói Xu I §f•");
		$form->setContent("§l§c•§e Gói Xu I:\n§l§c•§e 1 Coin = 100.000Xu\n§l§c•§e Bạn có đồng ý mua không?");
		$form->setButton1("§l§e•§a Đồng Ý §e•", 1);
		$form->setButton2("§l§e•§c Không §e•", 2);
		$form->sendToPlayer($sender);
	}
	public function goixu2($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 1:
			    $coin = $this->coin->myCoin($sender);
				$cost = 2;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->money->addMoney($sender, 200000);
					$sender->sendMessage("§l§c•§e Bạn đã mua thành công gói xu 2");
				}else{
					$sender->sendPopup("§l§e•§c Bạn Không Đủ Coin §e•");
					return true;
				}
			 break;
			         case 2:
					$command = "buyxu";
					$this->getServer()->getCommandMap()->dispatch($sender, $command);
					break;
				}
			 
		});
		$form->setTitle("§l§f•§9 Gói Xu II §f•");
		$form->setContent("§l§c•§e Gói Xu II:\n§l§c•§e 2 Coin = 200.000Xu\n§l§c•§e Bạn có đồng ý mua không?");
		$form->setButton1("§l§e•§a Đồng Ý §e•", 1);
		$form->setButton2("§l§e•§c Không §e•", 2);
		$form->sendToPlayer($sender);
	}
	public function goixu3($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 1:
			    $coin = $this->coin->myCoin($sender);
				$cost = 3;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->money->addMoney($sender, 300000);
					$sender->sendMessage("§l§c•§e Bạn đã mua thành công gói xu 3");
				}else{
					$sender->sendPopup("§l§e•§c Bạn Không Đủ Coin §e•");
					return true;
				}
			 break;
			         case 2:
					$command = "buyxu";
					$this->getServer()->getCommandMap()->dispatch($sender, $command);
					break;
				}
			 
		});
		$form->setTitle("§l§f•§9 Gói Xu III §f•");
		$form->setContent("§l§c•§e Gói Xu III:\n§l§c•§e 3 Coin = 300.000Xu\n§l§c•§e Bạn có đồng ý mua không?");
		$form->setButton1("§l§e•§a Đồng Ý §e•", 1);
		$form->setButton2("§l§e•§c Không §e•", 2);
		$form->sendToPlayer($sender);
	}
	public function goixu4($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 1:
			    $coin = $this->coin->myCoin($sender);
				$cost = 4;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->money->addMoney($sender, 400000);
					$sender->sendMessage("§l§c•§e Bạn đã mua thành công gói xu 4");
				}else{
					$sender->sendPopup("§l§e•§c Bạn Không Đủ Coin §e•");
					return true;
				}
			 break;
			         case 2:
					$command = "buyxu";
					$this->getServer()->getCommandMap()->dispatch($sender, $command);
					break;
				}
			 
		});
		$form->setTitle("§l§f•§9 Gói Xu IV §f•");
		$form->setContent("§l§c•§e Gói Xu IV:\n§l§c•§e 4 Coin = 400.000Xu\n§l§c•§e Bạn có đồng ý mua không?");
		$form->setButton1("§l§e•§a Đồng Ý §e•", 1);
		$form->setButton2("§l§e•§c Không §e•", 2);
		$form->sendToPlayer($sender);
	}
	public function goixu5($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $api->createModalForm(Function (Player $sender, $data){
			$result = $data;
				if ($result == null){
				}
				switch ($result) {
					case 1:
			    $coin = $this->coin->myCoin($sender);
				$cost = 5;
				if($coin >= $cost){
					$this->coin->reduceCoin($sender, $cost);
					$this->money->addMoney($sender, 500000);
					$sender->sendMessage("§l§c•§e Bạn đã mua thành công gói xu 5");
				}else{
					$sender->sendPopup("§l§e•§c Bạn Không Đủ Coin §e•");
					return true;
				}
			 break;
			         case 2:
					$command = "buyxu";
					$this->getServer()->getCommandMap()->dispatch($sender, $command);
					break;
				}
			 
		});
		$form->setTitle("§l§f•§9 Gói Xu V §f•");
		$form->setContent("§l§c•§e Gói Xu V:\n§l§c•§e 5 Coin = 500.000Xu\n§l§c•§e Bạn có đồng ý mua không?");
		$form->setButton1("§l§e•§a Đồng Ý §e•", 1);
		$form->setButton2("§l§e•§c Không §e•", 2);
		$form->sendToPlayer($sender);
	}
	
	
}