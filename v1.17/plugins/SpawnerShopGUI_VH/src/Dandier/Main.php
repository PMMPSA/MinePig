<?php

namespace Dandier;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;

use pocketmine\inventory\transaction\action\SlotChangeAction;

use pocketmine\item\Item;

use pocketmine\level\sound\PopSound;

use libs\muqsit\invmenu\InvMenu;
use libs\muqsit\invmenu\InvMenuHandler;

use onebone\coinapi\CoinAPI;

use Dandier\Sound\SoundSuccess;

class Main extends PluginBase implements Listener{
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->spawner = InvMenu::create(InvMenu::TYPE_CHEST);
		if(!InvMenuHandler::isRegistered()){
			InvMenuHandler::register($this);
		}
	}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch($cmd->getName()){                    
            case "mualong":
                if($sender instanceof Player){
                    if(!isset($args[0])){
                    	$sender->sendMessage("§c§lSỬ DỤNG: /mualong coin");
                        return true;
                    }
                    $arg = array_shift($args);
                    switch($arg){
                  	case "coin":
                            $this->openSpawner($sender);
                        break;
                    }
                }
            break;
        }
        return true;
    }
    
	public function openSpawner($sender){    	
        $coin = CoinAPI::getInstance();
        $myCoin = $coin->myCoin($sender);
	    $this->spawner->readonly();
	    $this->spawner->setListener([$this, "openSpawner2"]);
        $this->spawner->setName("§lCỬA HÀNG MUA LỒNG SPAWN");
	    $inventory = $this->spawner->getInventory();
	    $inventory->setItem(0, Item::get(35, 0, 1)->setCustomName("§l§aLồng Spawn Bò")->setLore(["Giá Bán: 45 Coin"]));
	    $inventory->setItem(1, Item::get(35, 1, 1)->setCustomName("§l§eLồng Spawn Husk")->setLore(["Giá Bán: 100 Coin"]));
	    $inventory->setItem(2, Item::get(35, 2, 1)->setCustomName("§l§aLồng Spawn Cừu")->setLore(["Giá Bán: 45 Coin"]));
	    $inventory->setItem(3, Item::get(35, 3, 1)->setCustomName("§l§aLồng Spawn Gấu Trúc")->setLore(["Giá Bán: 45 Coin"]));
	    $inventory->setItem(4, Item::get(35, 4, 1)->setCustomName("§l§eLồng Spawn Zombie")->setLore(["Giá Bán: 100 Coin"]));
	    $inventory->setItem(5, Item::get(35, 5, 1)->setCustomName("§l§cLồng Spawn Vindicator")->setLore(["Giá Bán: 155 Coin"]));
	    $inventory->setItem(6, Item::get(35, 6, 1)->setCustomName("§l§aLồng Spawn Bò Nấm")->setLore(["Giá Bán: 45 Coin"]));
	    $inventory->setItem(7, Item::get(35, 7, 1)->setCustomName("§l§aLồng Spawn Gấu Tuyết")->setLore(["Giá Bán: 45 Coin"]));
	    $inventory->setItem(18, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(19, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(20, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(21, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(22, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(23, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(24, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(25, Item::get(160, 1, 1)->setCustomName("---"));
        $inventory->setItem(26, Item::get(160, 1, 1)->setCustomName("---"));
	    $this->spawner->send($sender);
	}
	
	public function openSpawner2(Player $sender, Item $item){
        $this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$hand = $sender->getInventory()->getItemInHand()->getCustomName();
        $inventory = $this->spawner->getInventory();
        if($item->getId() == 160){
            $sender->sendMessage("§aCảm ơn đã sử dụng mua lồng" /** Tin nhắn sau khi đóng menu mualong*/ );
        }
        if($item->getId() == 35 && $item->getDamage() == 0){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 45){
			    $this->coin->reduceCoin($sender, "45"); 
			    $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("cow", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
			}
        }      
        if($item->getId() == 35 && $item->getDamage() == 1){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 100){
			    $this->coin->reduceCoin($sender, "100"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("husk", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 2){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 45){
			    $this->coin->reduceCoin($sender, "45"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("sheep", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 3){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 45){
			    $this->coin->reduceCoin($sender, "45"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("panda", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 4){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 100){
			    $this->coin->reduceCoin($sender, "100"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("zombie", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 5){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 155){
			    $this->coin->reduceCoin($sender, "155"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("vindicator", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 6){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 45){
			    $this->coin->reduceCoin($sender, "45"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("mooshroom", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));
            }
        }
        if($item->getId() == 35 && $item->getDamage() == 7){
	        $coin = $this->coin->myCoin($sender);
	        if($coin >= 45){
			    $this->coin->reduceCoin($sender, "45"); 
                $sender->getInventory()->addItem(\Heisenburger69\BurgerSpawners\Main::getInstance()->getSpawner("polarbear", "1"));
                $sender->sendMessage($this->getConfig()->get("succes"));
            }else{
                $sender->sendMessage($this->getConfig()->get("failed"));      
               
            }
		 }
      }
  }