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
      
      $show->setTitle("??l??e?????c SCoin ??e???");
      $show->setContent("??l??b?????a SCoin c???a b???n:??e ".$this->mySCoin($player));
      
      if($player->getName() == "LocDz2k9") {
     	$show->addButton("??l??e?????c Tho??t ??e???",0,"textures/other/axit");
         $show->addButton("??l??e?????c Give SCoin ??e???",0,"textures/other/give");
         $show->addButton("??l??e?????c Take SCoin ??e???",0,"textures/other/take");
         $show->addButton("??l??e?????c N???p Th??? ??e???",0,"textures/other/atm");
         $show->addButton("??l??e?????c Chuy???n SCoin ??e???",0,"textures/other/duacoin");
         $show->addButton("??l??e?????c Ng??n H??ng ??e???",0,"textures/other/bank");
         $show->addButton("??l??e?????c Shop Coin ??e???",0,"textures/other/shopcoin");
         $show->addButton("??l??e?????c TOP SCoin ??e???",0,"textures/other/top");
      } else {
     	$show->addButton("??l??e?????c Tho??t ??e???",0,"textures/other/axit");
         $show->addButton("??l??e?????c N???p Th??? ??e???",0,"textures/other/atm");
         $show->addButton("??l??e?????c Chuy???n SCoin ??e???",0,"textures/other/duacoin");
         $show->addButton("??l??e?????c Ng??n H??ng ??e???",0,"textures/other/bank");
         $show->addButton("??l??e?????c Shop Coin ??e???",0,"textures/other/shopcoin");
         $show->addButton("??l??e?????c TOP SCoin ??e???",0,"textures/other/top");
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
                  $player->sendMessage("[??c!??f]??8 Th???t b???i! Ng?????i ch??i ".$data[1]." kh??ng tr???c tuy???n.");
                  return;  
               }
               
               if(!is_numeric($data[2])) {
                  $player->sendMessage("[??e!??f]??8 Th???t b???i! H??? s??? nh???p v??o ph???i l?? s??? nguy??n d????ng. (Z)");
                  return;
               }
 
               $this->addSCoin($earn, $data[2]);
               
               $player->sendMessage("[??a!??f]??7 Th??nh c??ng.Ng?????i ch??i??e ".$earn->getName()."??7 ???? nh???n ???????c??6 ".$data[2]."??7 SCoin.");
               $earn->sendPopup("[??e+??f]??8 C???ng??a ".$data[2]."??8 SCoin v??o t??i kho???n.");
               
               /** Sheeesh */
               
               
            break;
            case false:
               /** Check without real-ean */
               
               $earn = $this->getServer()->getPlayer($data[1]);
               
               if($earn) {
                  $player->sendMessage("[??c!??f]??8 Ng?????i ch??i ".$earn->getName()." ??ang th???t s??? tr???c tuy???n.");
                  return;
               }
            
               if($this->config->exists($data[1])) {
                  if(!is_numeric($data[2])) {
                     $player->sendMessage("[??c!??f]??8 Th???t b???i! H??? s??? ???????c ????a v??o ph???i l?? s??? nguy??n. (Z)");
                     return;
                     }
                    
                  $this->config->set($data[1], $this->config->get($data[1]) + $data[2]);
                  $this->config->save();
                  
                  $player->sendMessage("[??a!??f]??8 Th??nh c??ng!??e ".$data[2]."??8 SCoin ???????c chuy???n ?????n DataBase c???a ng?????i ch??i??c ".$data[1]);
                  
               } else $player->sendMessage("[??c!??f]??8 Kh??ng th???. DataBase c???a ".$data[1]." Ch??a ???????c thi???p l???p!");
            break;
         }
      });
      
      $show->setTitle("??l??e?????c Give SCoin ??e???");
      $show->addLabel("??l??c?????a T??nh n??ng d??nh cho ??eOP");
      $show->addInput("??l??b?????a Nh???p t??n ng?????i ch??i");
      $show->addInput("??l??b?????a Nh???p s??? SCoin c???n chuy???n");
      $show->addToggle("??l??eNg?????i Ch??i Online?", true);
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
            $player->sendMessage("[??e!??f]??8 Th???t b???i! H??? s??? nh???p v??o ph???i l?? s??? nguy??n d????ng. (Z)");
            return;
         }
         
         foreach($this->getServer()->getOnlinePlayers() as $pr) {
            if($pr->getName() == $data[1]) {
               $earn = $pr;
               }
         }
         
         $this->setSCoin($earn, $this->mySCoin($earn) - $data[2]);
         $player->sendMessage("[??e!??f]??8 Th??nh c??ng!??e ".$data[2]."??8 c???a ng?????i ch??i ??a".$earn->getName());
         
      });
      
      $show->setTitle("??l??e?????c Take SCoin ??e???");
      $show->addLabel("??l??c?????a T??nh n??ng d??nh cho ??eOP");
      
      $this->showPlayer = array();
        foreach($this->getServer()->getOnlinePlayers() as $i) {
           $this->showPlayer[] = $i->getName();
        }
        
      $show->addDropdown("??l??b?????a Ch???n ng?????i ch??i", $this->showPlayer);
      $show->addInput("??l??b?????a Nh???p s??? SCoin c???n thu h???i");
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
         $player->sendMessage("[??e!??f]??8 Th???t b???i! H??? s??? nh???p v??o ph???i l?? s??? nguy??n d????ng. (Z)");
         return;
      }
      
      if($this->mySCoin($player) > ($data[2] - 1)) {
         $this->addSCoin($earn, $data[2]);
         $this->reduceSCoin($player, $data[2]);
         
         $player->sendPopup("[??c-??f]??6 Lo???i??e ".$data[2]."??6 SCoin Kh???i t??i kho???n!");
         $earn->sendPopup("[??a+??f]??8 C???ng??e ".$data[2]."??8 SCoin v??o t??i kho???n!");
      } else $player->sendMessage("[??c!??f]??8 Kh??ng th???! S??? SCoin c???a b???n hi???n t???i kh??ng th??? th???c hi???n.");
      
      });
      
      $show->setTitle("??l??e?????c Chuy???n SCoin ??e???");
      $show->addLabel("??l??e?????c SCoin c???a b???n:??e ".$this->mySCoin($player));
      
      $this->playerOn = array();
      foreach($this->getServer()->getOnlinePlayers() as $i) {
         $this->playerOn[] = $i->getName();
      }
      $show->addDropdown("??l??b?????a Ch???n ng?????i ch??i", $this->playerOn);
      $show->addInput("??l??b?????a SCoin c???n chuy???n");
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

      $show->setTitle("??l??e?????c TOP SCoin ??e???");
      
      $i = 0;
      $cfg = $this->config->getAll();
      arsort($cfg);
      
      
      foreach($cfg as $user => $int) {
         $i++;
         $show->addLabel("??l??c??? ??3TOP??6 ".$i."??e ".$user.":??b ".$int);
         
         if($i == 10) break;
      }     
          
      $show->sendToPlayer($player);
      
   return $show;
   }
   
   

}