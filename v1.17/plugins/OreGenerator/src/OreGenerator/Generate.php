<?php

namespace OreGenerator;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\item\Item;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\block\Block;
use pocketmine\block\Iron;
use pocketmine\block\Cobblestone;
use pocketmine\block\Glowingobsidian;
use pocketmine\block\Diamond;
use pocketmine\block\Emerald;
use pocketmine\block\Gold;
use pocketmine\block\Coal;
use pocketmine\block\Lapis;
use pocketmine\block\Redstone;
use pocketmine\block\IronOre;
use pocketmine\block\DiamondOre;
use pocketmine\block\EmeraldOre;
use pocketmine\block\GoldOre;
use pocketmine\block\CoalOre;
use pocketmine\block\LapisOre;
use pocketmine\block\RedstoneOre;
use onebone\economyapi\EconomyAPI;
use onebone\coinapi\CoinAPI;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};

class Generate extends PluginBase implements Listener{

    public function onEnable(){
                $this->getLogger()->info("OreGenerator By PIG");
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");		
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
@mkdir($this->getDataFolder(), 0744, true);
       $this->ore = new Config($this->getDataFolder()."ore.yml",Config::YAML);
    if(!$this->ore->exists("ore")){
        $this->ore->set("ore", 245); 
        $this->ore->set("orevip", 220);
        $this->ore->save();
    }
    }
    public function onbreak(BlockBreakEvent $event) {
        $idblock = $event->getBlock()->getId();
        $ore = $this->ore->get("ore");
        $orevip = $this->ore->get("orevip");             
        if ($idblock == $ore){
            if (!$event->getPlayer()->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$event->setCancelled(true);
				$event->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Túi đồ của bạn đã đầy!");
				return;
			} 
            $event->setDrops(array());
			$item = Item::get($this->ore->get("ore"), 0, 1);
			$item->setCustomName("§l§6•§b Block §cO§eR§aE§6 •");
            $item->setLore(array("§l§e•§a Tác Dụng:\n§l§f↣§e Tạo Quặng Hỗn Hợp Để Khai Thác!"));
			$event->getPlayer()->getInventory()->addItem($item);            
        }
        if ($idblock == $orevip){
            if (!$event->getPlayer()->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$event->setCancelled(true);
				$event->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Túi đồ của bạn đã đầy!");
				return;
			}  
            $event->setDrops(array());
			$item = Item::get($this->ore->get("orevip"), 0, 1);
			$item->setCustomName("§l§6•§b Block §cO§eR§aE§d VIP §6•");
            $item->setLore(array("§l§e•§a Tác Dụng:\n§l§f↣§e Tạo Quặng Đá Và Kim Cương Để Khai Thác!"));
			$event->getPlayer()->getInventory()->addItem($item);           
        }        
            
    }
    
    public function oreDiamond(BlockUpdateEvent $event){
                $block = $event->getBlock();
        $end = false;
        for ($i = 0; $i <= 0; $i++) {
            $idblock = $block->getSide($i)->getId();
            $ore = $this->ore->get("orevip");        
            if ($idblock !== $ore) {
                return;
            }
                $id = mt_rand(1, 3);
                switch ($id) {
                    case 1;
                        $newBlock = new DiamondOre();
                        break;

                    case 2;
                        $newBlock = new Diamond();
                        break;
                    default:
                        $newBlock = new Cobblestone();                        
                }
                sleep(0.1);
                $block->getLevel()->setBlock($block, $newBlock, true, false);
                return;
        }
    }
            
    
    public function oreRandom(BlockUpdateEvent $event){
        $block = $event->getBlock();
        $end = false;
        for ($i = 0; $i <= 0; $i++) {
           $idblock = $block->getSide($i)->getId();
            $ore = $this->ore->get("ore");          
            if ($idblock !== $ore) {
                return;
            }
                $id = mt_rand(1, 20);
                switch ($id) {
                    case 2;
                        $newBlock = new IronOre();
                        break;
                    case 4;
                        $newBlock = new GoldOre();
                        break;
                    case 6;
                        $newBlock = new EmeraldOre();
                        break;
                    case 8;
                        $newBlock = new CoalOre();
                        break;
                    case 10;
                        $newBlock = new RedstoneOre();
                        break;
                    case 12;
                        $newBlock = new DiamondOre();
                        break;
					case 14;
                        $newBlock = new LapisOre();
                        break;
                    case 7;
                        $newBlock = new Iron();
                        break;
                    case 9;
                        $newBlock = new Gold();
                        break;
                    case 6;
                        $newBlock = new Emerald();
                        break;
                    case 11;
                        $newBlock = new Coal();
                        break;
                    case 15;
                        $newBlock = new Redstone();
                        break;
                    case 17;
                        $newBlock = new Diamond();
                        break;
					case 19;
                        $newBlock = new Lapis();
                        break;	
                    default:
                        $newBlock = new Cobblestone();
                }
                sleep(0.2);
                $block->getLevel()->setBlock($block, $newBlock, true, false);
                return;
        }
    }
 
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "generator":
            $this->menu($sender);
            return true;
        }
        return true;
	}
	
	 public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
            if (!$sender->getInventory()->canAddItem(Item::get(0, 0, 1))){
				$event->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Túi đồ của bạn đã đầy!");
				return;
			}               
          $result = $data;
          if($result === null){
          }
          switch($result){
              case 0:
              break;
              case 1:
            $money = $this->eco->MyMoney($sender);
            if($money >= 100000){
               $this->eco->reduceMoney($sender, 100000);	
               $id = $this->ore->get("ore");			
			   $item = Item::get($id, 0, 1);
			   $item->setCustomName("§l§6•§b Block §cO§eR§aE§6 •");
               $item->setLore(array("§l§e•§a Tác Dụng:\n§l§f↣§e Tạo Quặng Hỗn Hợp Để Khai Thác!"));
			   $sender->getInventory()->addItem($item);
		       $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Bạn đã nhận được Block Ore");
            }else{
			  $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ tiền mua Block Ore");                
            } 
              break;
              case 2:
            $coin = $this->coin->myCoin($sender);
            if($coin >= 10){
               $this->coin->reduceCoin($sender, 10);
              $id = $this->ore->get("orevip");              
			  $item = Item::get($id, 0, 1);
		      $item->setCustomName("§l§6•§b Block §cO§eR§aE§d VIP §6•");
              $item->setLore(array("§l§e•§a Tác Dụng:\n§l§f↣§e Tạo Quặng Đá Và Kim Cương Để Khai Thác!"));
			  $sender->getInventory()->addItem($item);
			  $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Bạn đã nhận được Block Ore VIP");
            }else{
			  $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ tiền mua Block Ore VIP");                
            }
              break;              
         }
        });
        $money = EconomyAPI::getInstance()->myMoney($sender);
		 $coin = CoinAPI::getInstance()->myCoin($sender);
        $form->setTitle("§l§e•§a Hệ Thống BUY Random Ore §e•");
        $form->setContent("§l§c↣ §aTiền của bạn: §e".$money."\n§l§c↣ §aCoin của bạn: §e".$coin);
		$form->addButton("§l§e•§c Thoát §e•");
        $form->addButton("§l§e•§a Block Ore §e•\n§l§bGiá: §e100.000 Xu");
        $form->addButton("§l§e•§a Block Ore VIP §e•\n§l§bGiá: §e10 Coin");        
        $form->sendToPlayer($sender);
	}
    
}
