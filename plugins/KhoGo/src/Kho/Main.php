<?php

namespace Kho;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\math\Vector3;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\utils\Config;
use pocketmine\level\particle\{
	AngryVillagerParticle, BlockForceFieldParticle, BubbleParticle, CriticalParticle, DustParticle, EnchantmentTableParticle, EnchantParticle, EntityFlameParticle, ExplodeParticle, FlameParticle, GenericParticle, HappyVillagerParticle, HeartParticle, HugeExplodeParticle, HugeExplodeSeedParticle, InkParticle, InstantEnchantParticle, ItemBreakParticle, LavaDripParticle, PortalParticle, RainSplashParticle, RedstoneParticle, SmokeParticle, SplashParticle, SporeParticle, TerrainParticle, WaterDripParticle, WaterParticle
};

use itemcmd\form\SimpleForm;


class Main extends PluginBase implements Listener{
 
	
	public $plugin;
	public $city55 = "";

	public function onEnable(){
		$this->getLogger()->info("Kho Gỗ Đã Bật");
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		 @mkdir($this->getDataFolder() . "backpack/");
	}
	
	public function onJoin(PlayerJoinEvent $event){
      $player = $event->getPlayer();
      $data = new Config($this->getDataFolder() . "backpack/" . strtolower($player->getName()) . ".yml", Config::YAML);
    for($i = 0; $i <= 6; $i++){
      if(empty($data->get("Log$i"))){
        $data->set("Log$i", "0");
        $data->save();
      }
    }
    }
	
	public function onCommand(CommandSender $sender, Command $command, String $label, array $args) : bool {
        switch($command->getName()){
            case "khogo":
            $this->backpackLog($sender);
            return true;
        }
        return true;
	}
	
	public function backpackLog($player){
	    
	    $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createCustomForm(function (Player $p, $data){
            $player = $p->getPlayer();
                    if($data !== null){
    
	    if($data[1] == 0){
	        if(!is_numeric($data[3])){
					  $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b $this->city55 §cBạn đã mắc sai lầm");
          }else{
	        if($data[2] == 0){
	        $id = 17;
			$da = 0;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						$player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log1") + $data[3];
			   $data1->set("Log1", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			      $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }else if($data[2] == 1){
	           $id = 17;
			$da = 1;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						$player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log2") + $data[3];
			   $data1->set("Log2", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			    $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }else if($data[2] == 2){
	           $id = 17;
			$da = 2;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						 $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log3") + $data[3];
			   $data1->set("Log3", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			    $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }else if($data[2] == 3){
	           $id = 17;
			$da = 3;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						 $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log4") + $data[3];
			   $data1->set("Log4", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			    $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }else if($data[2] == 4){
	           $id = 162;
			$da = 0;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						 $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log5") + $data[3];
			   $data1->set("Log5", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			   $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }else if($data[2] == 5){
	           $id = 162;
			$da = 1;
			$am = $data[3];
					$item = Item::get($id, $da, $am); 
					if(!$player->getInventory()->contains(Item::get($id, $da, $am))){
						 $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Không phải gỗ");
					}else{
			   $data1 = new Config($this->getDataFolder() . "backpack/" . strtolower($p->getName()) . ".yml", Config::YAML);
			   $pay1s = $data1->get("Log6") + $data[3];
			   $data1->set("Log6", "$pay1s");
			   $data1->save();
			   $player->getInventory()->removeItem($item);
			   $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã thêm§e $data[3] §bgỗ");
					}
	        }
          }
	    }
	    if($data[1] == 1){
	        if(!is_numeric($data[2])){
					 $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§cBạn đã mắc sai lầm");
          }else{
             $pay = 1 * $data[3];
		      $config = new Config($this->getDataFolder() . "backpack/" . strtolower($player->getName()) . ".yml", Config::YAML);
		if($data[2] == 0){
           if($config->get("Log1") >= $pay){
            
            $id = 17;
			$da = 0;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log1") - $pay;
             $scion = $config->get("Log1");
             $config->set("Log1", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }else if($data[2] == 1){
           if($config->get("Log2") >= $pay){
            
            $id = 17;
			$da = 1;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log2") - $pay;
             $scion = $config->get("Log2");
             $config->set("Log2", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }else if($data[2] == 2){
           if($config->get("Log3") >= $pay){
            
            $id = 17;
			$da = 2;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log3") - $pay;
             $scion = $config->get("Log3");
             $config->set("Log3", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }else if($data[2] == 3){
           if($config->get("Log4") >= $pay){
            
            $id = 17;
			$da = 3;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log4") - $pay;
             $scion = $config->get("Log4");
             $config->set("Log4", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }else if($data[2] == 4){
           if($config->get("Log5") >= $pay){
            
            $id = 162;
			$da = 0;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log5") - $pay;
             $scion = $config->get("Log5");
             $config->set("Log5", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }else if($data[2] == 5){
           if($config->get("Log6") >= $pay){
            
            $id = 162;
			$da = 1;
			$am = $pay;
			$item = Item::get($id, $da, $am); 
             $p->getInventory()->addItem($item);
             $pays = $config->get("Log6") - $pay;
             $scion = $config->get("Log6");
             $config->set("Log6", $pays);
             $config->save();
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã lấy§e $pay §bgỗ");
           } else {
             $player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §cBạn không có gỗ để lấy");
           }
          }
	    }
                    }
        }
		});
		$config = new Config($this->getDataFolder() . "backpack/" . strtolower($player->getName()) . ".yml", Config::YAML);
        $log1 = $config->get("Log1");
        $log2 = $config->get("Log2");
        $log3 = $config->get("Log3");
        $log4 = $config->get("Log4");
        $log5 = $config->get("Log5");
        $log6 = $config->get("Log6");
		$form->setTitle("§l§9⚫§a Kho Gỗ §9⚫");
		$form->addLabel("§l§e•§6 Gỗ của bạn:\n§l§c↣§a OakWo๐d: §f$log1\n§l§c↣§a SpruceWood: §f$log2\n§l§c↣§a BirchWood: §f$log3\n§l§c↣§a JungleWood: §f$log4\n§l§c↣§a AcaciaWood: §f$log5\n§l§c↣§a DarkOakWood:§f $log6");
		$form->addDropdown("§l§e•§b Hãy chọn §aThêm §f| §cLấy:", array("§l§e•§a Thêm §e•", "§l§e•§c Lấy §e•"));
		$form->addDropdown("§l§e•§b Hãy chọn loại gỗ:", array("§l§e•§a Oak §e•", "§l§e•§a Spruce §e•", "§l§e•§a Birch §e•", "§l§e•§a Jungle §e•" ,"§l§e•§a Acacia §e•" ,"§l§e•§a Dark Oak §e•"));
		$form->addInput("§l§e•§a Hãy chọn số lượng:", "");
		$form->sendToPlayer($player);
		}
    
}