<?php

namespace axelevel;

use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\math\Vector3;
use pocketmine\item\Item;
use pocketmine\command\{Command, CommandSender, ConsoleCommandSender};
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use jojoe77777\FormAPI\CustomForm;

Class Main extends PluginBase implements Listener{
	
	public function onEnable():void{
		$this->getServer()->getPluginManager()->registerEvents($this,$this);
$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->getLogger()->info("§l§aAxe Level By PIG");
@mkdir($this->getDataFolder(), 0744, true);
       $this->ldlc = new Config($this->getDataFolder()."ldlc.yml",Config::YAML);
       $this->cap = new Config($this->getDataFolder()."cap.yml",Config::YAML);
       $this->kn = new Config($this->getDataFolder()."kinhnghiem.yml",Config::YAML);
}

public function onJoin(PlayerJoinEvent $ev) {
 if(!$this->cap->exists($ev->getPlayer()->getName())) {
        $this->cap->set($ev->getPlayer()->getName(), 1);
        $this->cap->save();
         }
if(!$this->ldlc->exists($ev->getPlayer()->getName())) {
			$player = $ev->getPlayer();
			$inv = $player->getInventory();  
			$item = Item::get(279, 0, 1);
    $cap = $this->cap->get($ev->getPlayer()->getName(),);
			$item->setCustomName("§l§cAxe §f".$player->getName());
			$inv->addItem($item);
			$player->sendMessage("");
$this->ldlc->set($ev->getPlayer()->getName());
$this->ldlc->save();
    }
   //kinh Nghiệm 
     if(!$this->kn->exists($ev->getPlayer()->getName())) {
        $this->kn->set($ev->getPlayer ()->getName(), 0);
        $this->kn->save();
    }
  //cấp  

    }
public function onbreak(BlockBreakEvent $ev) {
    ///Add 
        $player = $ev->getPlayer();
        $item = $player->getInventory()->getItemInHand()->getCustomName();
        $id = $player->getInventory()->getItemInHand()->getId();
     if($id == 0){
     return;
     }
               $cap = $this->cap->get($ev->getPlayer()->getName());
               $nameplayer = $player->getName();
     $name = "§l§cAxe §f".$nameplayer;
      if($item == $name){
		if(!$ev->isCancelled()){
      if($ev->getBlock()->getId() == 17){
               $cap = $this->cap->get($ev->getPlayer()->getName());
               $kn = $this->kn->get($ev->getPlayer()->getName());
               $maxkn = $cap* 50;
         $ev->getPlayer()->sendPopup("§l§e⚡§6 ᑭᏆᏀ§b ᗩ᙭ᗴ §7[§cCấp:§c ". $cap."§7]§7 (§a".$player->getName()."§7) §e⚡\n§l§d☯§7 [§cNext:§a ".$kn."§7/ §c".$maxkn."§7] §d☯");
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) + 1));
       $this->kn->save();
           }
           //Skill
               $cap = $this->cap->get($ev->getPlayer()->getName());
               if($cap > 0){
                   if($cap < 65){
      if($ev->getBlock()->getId() == 17){
               $cap = $this->cap->get($ev->getPlayer()->getName());
			$player = $ev->getPlayer();
			$inv = $player->getInventory();  
			$sl = $cap - 1;
			$item = Item::get(17, 0, $sl);
			$inv->addItem($item);
      }
               }elseif($cap>63){
      if($ev->getBlock()->getId() == 17){
			$player = $ev->getPlayer();
			$inv = $player->getInventory();
			$item = Item::get(17, 0, 64);
			$inv->addItem($item);
$ev->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn Nhận Được §f64§b Block Gỗ");
      }
               }
               }
                   if($cap > 64){
      if($ev->getBlock()->getId() == 17){
               $cap = $this->cap->get($ev->getPlayer()->getName());
         $money = $cap*10;
       $this->eco->addmoney($ev->getPlayer(), $money);
$ev->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】 §bBạn nhận được §f".$money."§b xu");
                }
               }
		}
    $cap = $this->cap->get($ev->getPlayer()->getName());
    $lenhcap = $cap * 50;
     if($this->kn->get($ev->getPlayer()->getName()) >= $lenhcap) {
       $this->kn->set($ev->getPlayer()->getName(), ($this->kn->get($ev->getPlayer()->getName()) - $lenhcap));
       $this->kn->save();
       $money = $cap*1000;
       $this->eco->addmoney($ev->getPlayer(), $money);
       $ev->getPlayer()->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Axe nâng cấp bạn nhận được§f ".$money." §axu");
       $this->cap->set($ev->getPlayer()->getName(), ($this->cap->get($ev->getPlayer()->getName()) + 1));
       $this->cap->save();
		$cap = $this->cap->get($ev->getPlayer()->getName());
			$player = $ev->getPlayer();
			$inv = $player->getInventory();  
			$item = Item::get(279, 0, 1);
			$item->setCustomName("§l§cAxe §f".$player->getName());
			$give = $cap * 10;
			if($cap < 65){
					$item->setLore(array("§l§f⚫§a Đập 1 x ".$cap));
			}elseif($cap > 64){
					$item->setLore(array("§l§f⚫§a Đập 1 x ".$cap."\nĐập Block Nhận Xu ".$give));
			}
			$player->getInventory()->setItemInHand($item);
       $player = $ev->getPlayer();
			 $this->getServer()->broadcastMessage("§l§c•§e Người Chơi §f".$player->getName()."§e Axe Vừa Lên Cấp Cấp:§f ".$cap);
     }
      }
}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {
		if ($cmd->getName() == "topriu") {
			$levelplot = $this->cap->getAll();
			$max = 0;
			$max = count($levelplot);
			$max = ceil(($max / 5));
			
			$message = "";
			$message1 = "";
			
			$page = array_shift($args);
			$page = max(1, $page);
			$page = min($max, $page);
			$page = (int)$page;
			
			$aa = $this->cap->getAll();
			arsort($aa);
			$i = 0;
			
			foreach ($aa as $b=>$a) {
				if (($page - 1) * 5 <= $i && $i <= ($page - 1) * 5 + 4) {
					$i1 = $i + 1;
					$trang = "§l§c•§e Trang:§a ".$page."§f/§c".$max."\n";
					$message .= "§l§3[§aTOP §e".$i1."§3] §c".$b."§f: §e".$a." Cấp\n";
					$message1 .= "§l§3[§aTOP §e".$i1."§3] §c".$b."§f: §e".$a." Cấp\n";
				} $i++;
			}
			$form = new CustomForm(function (Player $sender, $data) use ($trang, $message) {
				if ($data === null) { return false; }
				$this->getServer()->dispatchCommand($sender, "topriu " . $data[1]);
			});
			$form->setTitle("§l§e•§c TOP Rìu §e•");
			$form->addLabel($trang. $message);
			$form->addInput("§l§c•§a Qua Trang");
			$form->sendToPlayer($sender);
		}
		if ($cmd->getName() == "axe") {
			$this->menu($sender);
			return true;
		}
		return true;
	}
                
    public function menu($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createSimpleForm(function(Player $sender, $data){
          $result = $data;
          if($result === null){
          }
          switch($result){
          	case 0:
              break;
              case 1:
			$player = $sender->getPlayer();
			$inv = $sender->getInventory();  
			$item = Item::get(279, 0, 1);
               $cap = $this->cap->get($sender->getName());
			$item->setCustomName("§l§cAxe §f".$sender->getName());
			$give = $cap * 10;
			if($cap < 65){
					$item->setLore(array("§l§f⚫§a Đập 1 x ".$cap));
			}elseif($cap > 64){
					$item->setLore(array("§l§f⚫§a Đập 1 x ".$cap."\nĐập Block Nhận Xu ".$give));
			}
			$inv->addItem($item);
			$sender->sendMessage("§l§f•§b Nhận Axe Thành Công");
              break;
              case 2:
             $command1 = "topriu";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command1); 
              break;
         }
        });
               $cap = $this->cap->get($sender->getName());
               $kn = $this->kn->get($sender->getName());
               $name = $sender->getName();
               $maxkn = $cap* 50;
        $form->setTitle("§l§9⚫§a Axe Menu §9⚫");
        $form->setContent("§l§e•§a Tên§f: §b".$name."\n§l§e•§a Cấp§f: §b".$cap."\n§l§e•§a Next§f: §b".$kn."§f / §b".$maxkn);
        $form->addButton("§l§6⚫ §cThoát §6⚫");
		$form->addButton("§l§6⚫ §bNhận Axe §6⚫");
		$form->addButton("§l§6⚫ §bTOP Axe §6⚫");
        $form->sendToPlayer($sender);
		return true;
	}
}
