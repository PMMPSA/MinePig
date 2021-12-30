<?php

namespace SCoinAPI;

use SCoinAPI\Event\PlayerChangeSCoinEvent;
use SCoinAPI\EventListener;
use SCoinAPI\Commands;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Loader extends PluginBase
{

    private static $instance;

    public function onLoad()
    {
        if(!is_dir($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }        
    }

    public function onEnable()
    {
        self::$instance = $this;
    
        $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML);
      
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

        $this->getServer()->getCommandMap()->register("scoin", new Commands($this));
    }

   public static function getInstance() : self
   {
      return self::$instance;
   }
    
    /** Function */

   public function mySCoin(Player $player)
   {
       return $this->config->get($player->getName());
   } 

   public function reduceSCoin(Player $player, int $numeric)
   {
       $this->config->set($player->getName(), ($this->config->get($player->getName()) - $numeric));
       $this->config->save();

        if($this->config->get($player->getName()) < 0) {
               $this->config->set($player->getName(), 0);
               $this->config->save();
           }

      $event = new PlayerChangeSCoinEvent($player);
      $this->getServer()->getPluginManager()->callEvent($event); 
   }

   public function addSCoin(Player $player, int $numeric)
   {
      $this->config->set($player->getName(), ($this->config->get($player->getName()) + $numeric));
      $this->config->save();

      $event = new PlayerChangeSCoinEvent($player);
      $this->getServer()->getPluginManager()->callEvent($event);

   }


   public function setSCoin(Player $player, int $numeric)
   {
      $this->config->set($player->getName(), $numeric);
      $this->config->save();

        if($this->config->get($player->getName()) < 0) {
               $this->config->set($player->getName(), 0);
               $this->config->save();
           }
      $event = new PlayerChangeSCoinEvent($player);
      $this->getServer()->getPluginManager()->callEvent($event);
   }

   public function showMain(Player $player)
   {
      $show = new SimpleForm(function (Player $player, int $data = null)
      {
          if($player->getName() == "LocDz2k9") {
             switch($data) {
             	case 0:
             break;
                case 1:
                   $this->showGive($player);
                break;
                case 2:
                   $this->showTake($player);
                break;
                case 3:
                   $command = "napthe";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
                break;
                case 4:
                $this->showPay($player);
                 break;
                case 5:
                $command = "nganhang open";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
                 break;
                 case 6:
                 $command = "shopcoin";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);                break;
         case 7:
                   $this->showChart($player);
                break;
             }
          } else {
             switch($data) {
             	case 0:
             break;
                case 1:
                   $command = "napthe";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
                break;
                case 2:
                $this->showPay($player);
                 break;
                case 3:
                $command = "nganhang open";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
                 break;
                 case 4:
                 $command = "shopcoin";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);                break;
         case 5:
                   $this->showChart($player);
                break;
             }
          }
      
      });
      
      $show->setTitle("§l§e•§c SCoin §e•");
      $show->setContent("§l§b⇀§a SCoin của bạn:§e ".$this->mySCoin($player));
      
      if($player->getName() == "LocDz2k9") {
     	$show->addButton("§l§e•§c Thoát §e•",0,"textures/other/axit");
         $show->addButton("§l§e•§c Give SCoin §e•",0,"textures/other/give");
         $show->addButton("§l§e•§c Take SCoin §e•",0,"textures/other/take");
         $show->addButton("§l§e•§c Nạp Thẻ §e•",0,"textures/other/atm");
         $show->addButton("§l§e•§c Chuyển SCoin §e•",0,"textures/other/duacoin");
         $show->addButton("§l§e•§c Ngân Hàng §e•",0,"textures/other/bank");
         $show->addButton("§l§e•§c Shop Coin §e•",0,"textures/other/shopcoin");
         $show->addButton("§l§e•§c TOP SCoin §e•",0,"textures/other/top");
      } else {
     	$show->addButton("§l§e•§c Thoát §e•",0,"textures/other/axit");
         $show->addButton("§l§e•§c Nạp Thẻ §e•",0,"textures/other/atm");
         $show->addButton("§l§e•§c Chuyển SCoin §e•",0,"textures/other/duacoin");
         $show->addButton("§l§e•§c Ngân Hàng §e•",0,"textures/other/bank");
         $show->addButton("§l§e•§c Shop Coin §e•",0,"textures/other/shopcoin");
         $show->addButton("§l§e•§c TOP SCoin §e•",0,"textures/other/top");
      }
      $show->sendToPlayer($player);
      
   return $show;      
   }
   
   /** FormAPI */
   
   /** Operator Function */
   
   public function showGive(Player $player)
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
        if($data === null) {
            $this->showMain($player);
           return;
           }

         switch($data[3]) {
            case true:
               $earn = $this->getServer()->getPlayer($data[1]);
               
               if(!$earn) {
                  $player->sendMessage("[§c!§f]§8 Thất bại! Người chơi ".$data[1]." không trực tuyến.");
                  return;  
               }
               
               if(!is_numeric($data[2])) {
                  $player->sendMessage("[§e!§f]§8 Thất bại! Hệ số nhập vào phải là số nguyên dương. (Z)");
                  return;
               }
 
               $this->addSCoin($earn, $data[2]);
               
               $player->sendMessage("[§a!§f]§7 Thành công.Người chơi§e ".$earn->getName()."§7 đã nhận được§6 ".$data[2]."§7 SCoin.");
               $earn->sendPopup("[§e+§f]§8 Cộng§a ".$data[2]."§8 SCoin vào tài khoản.");
               
               /** Sheeesh */
               
               
            break;
            case false:
               /** Check without real-ean */
               
               $earn = $this->getServer()->getPlayer($data[1]);
               
               if($earn) {
                  $player->sendMessage("[§c!§f]§8 Người chơi ".$earn->getName()." đang thật sự trực tuyến.");
                  return;
               }
            
               if($this->config->exists($data[1])) {
                  if(!is_numeric($data[2])) {
                     $player->sendMessage("[§c!§f]§8 Thất bại! Hệ số được đưa vào phải là số nguyên. (Z)");
                     return;
                     }
                    
                  $this->config->set($data[1], $this->config->get($data[1]) + $data[2]);
                  $this->config->save();
                  
                  $player->sendMessage("[§a!§f]§8 Thành công!§e ".$data[2]."§8 SCoin được chuyển đến DataBase của người chơi§c ".$data[1]);
                  
               } else $player->sendMessage("[§c!§f]§8 Không thể. DataBase của ".$data[1]." Chưa được thiếp lập!");
            break;
         }
      });
      
      $show->setTitle("§l§e•§c Give SCoin §e•");
      $show->addLabel("§l§c•§a Tính năng dành cho §eOP");
      $show->addInput("§l§b⇀§a Nhập tên người chơi");
      $show->addInput("§l§b⇀§a Nhập số SCoin cần chuyển");
      $show->addToggle("§l§eNgười Chơi Online?", true);
      $show->sendToPlayer($player);
      
   return $show;
   }
   
   public function showTake(Player $player)
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
         if($data == null) {
            $this->showMain($player);
            return;
         }
         
         if(!is_numeric($data[2])) {
            $player->sendMessage("[§e!§f]§8 Thất bại! Hệ số nhập vào phải là số nguyên dương. (Z)");
            return;
         }
         
         foreach($this->getServer()->getOnlinePlayers() as $pr) {
            if($pr->getName() == $data[1]) {
               $earn = $pr;
               }
         }
         
         $this->setSCoin($earn, $this->mySCoin($earn) - $data[2]);
         $player->sendMessage("[§e!§f]§8 Thành công!§e ".$data[2]."§8 của người chơi §a".$earn->getName());
         
      });
      
      $show->setTitle("§l§e•§c Take SCoin §e•");
      $show->addLabel("§l§c•§a Tính năng dành cho §eOP");
      
      $this->showPlayer = array();
        foreach($this->getServer()->getOnlinePlayers() as $i) {
           $this->showPlayer[] = $i->getName();
        }
        
      $show->addDropdown("§l§b⇀§a Chọn người chơi", $this->showPlayer);
      $show->addInput("§l§b⇀§a Nhập số SCoin cần thu hồi");
      $show->sendToPlayer($player);
      
   return $show;     
   }
   
   /** Member Function */
   
   public function showPay(Player $player)
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
          
      if($data === null) {
         $this->showMain($player);
         return;
      }

  
      foreach($this->getServer()->getOnlinePlayers() as $pr) {
         if($pr->getName() == $data[1]) {
             $earn = $pr;
            }
      }
      
      if(!is_numeric($data[2])) {
         $player->sendMessage("[§e!§f]§8 Thất bại! Hệ số nhập vào phải là số nguyên dương. (Z)");
         return;
      }
      
      if($this->mySCoin($player) > ($data[2] - 1)) {
         $this->addSCoin($earn, $data[2]);
         $this->reduceSCoin($player, $data[2]);
         
         $player->sendPopup("[§c-§f]§6 Loại§e ".$data[2]."§6 SCoin Khỏi tài khoản!");
         $earn->sendPopup("[§a+§f]§8 Cộng§e ".$data[2]."§8 SCoin vào tài khoản!");
      } else $player->sendMessage("[§c!§f]§8 Không thể! Số SCoin của bạn hiện tại không thể thực hiện.");
      
      });
      
      $show->setTitle("§l§e•§c Chuyển SCoin §e•");
      $show->addLabel("§l§e⇀§c SCoin của bạn:§e ".$this->mySCoin($player));
      
      $this->playerOn = array();
      foreach($this->getServer()->getOnlinePlayers() as $i) {
         $this->playerOn[] = $i->getName();
      }
      $show->addDropdown("§l§b⇀§a Chọn người chơi", $this->playerOn);
      $show->addInput("§l§b⇀§a SCoin cần chuyển");
      $show->sendToPlayer($player);
      
   return $show;
   }
   
   public function showChart(Player $player)
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
      
         if($data == null) {
            $this->showMain($player);
            return;
         }
      });

      $show->setTitle("§l§e•§c TOP SCoin §e•");
      
      $i = 0;
      $cfg = $this->config->getAll();
      arsort($cfg);
      
      
      foreach($cfg as $user => $int) {
         $i++;
         $show->addLabel("§l§c⇀ §3TOP§6 ".$i."§e ".$user.":§b ".$int);
         
         if($i == 10) break;
      }     
          
      $show->sendToPlayer($player);
      
   return $show;
   }
   
   

}