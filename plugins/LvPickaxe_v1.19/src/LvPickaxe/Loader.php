<?php

namespace LvPickaxe;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

use pocketmine\level\Level;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\math\Vector3;

use pocketmine\utils\Config;
use pocketmine\nbt\tag\StringTag;

use pocketmine\network\mcpe\protocol\PlaySoundPacket;

use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\block\Air;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use pocketmine\scheduler\Task;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\InvMenuHandler;
use muqsit\invmenu\transaction\DeterministicInvMenuTransaction;



use LvPickaxe\UsersData;
use LvPickaxe\EventListener;
use LvPickaxe\TimerRun;

class Loader extends PluginBase{


   /* 
██╗░░██╗██████╗░███████╗
██║░██╔╝╚════██╗╚════██║
█████═╝░░░███╔═╝░░░░██╔╝
██╔═██╗░██╔══╝░░░░░██╔╝░
██║░╚██╗███████╗░░██╔╝░░
╚═╝░░╚═╝╚══════╝░░╚═╝░░░ */
    
    public $doubleItem;
    
    public $haste;

    public $statusBar;
    
    public $active;
    
    private static $instance;
    
    public function onLoad(){
        if(!is_dir($this->getDataFolder()."users/")){
           mkdir($this->getDataFolder()."users/");
            }
        }
    
    public function onEnable(){

     $this->getLogger()->emergency("
██╗░░██╗██████╗░███████╗
██║░██╔╝╚════██╗╚════██║
█████═╝░░░███╔═╝░░░░██╔╝
██╔═██╗░██╔══╝░░░░░██╔╝░
██║░╚██╗███████╗░░██╔╝░░
╚═╝░░╚═╝╚══════╝░░╚═╝░░░");

      self::$instance = $this;
        
      if(!InvMenuHandler::isRegistered()){
          InvMenuHandler::register($this);
         }

           UsersData::init($this);
         
           $this->EventListener = new EventListener($this);
           $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);

           $this->FormAPI = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        
           $this->config = new Config($this->getDataFolder()."users/config.yml", Config::YAML);
            
        
        if(is_null($this->getServer()->getPluginManager()->getPlugin("EconomyAPI"))){
                   $this->getLogger()->emergency("Không tìm thấy giá trị kinh tế EconomyAPI. Yêu cầu cài đặt Plugin tại Poggit!");
                   $this->getServer()->getPluginManager()->disablePlugin($this);
            } else $this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        
        if(is_null($this->getServer()->getPluginManager()->getPlugin("SCoinAPI"))){
                   $this->getLogger()->critical(" Không tìm thấy Plugin SCoinAPI. Các vấn đề trao đổi sẽ không được thực hiện");  
            } else $this->SCoinAPI = $this->getServer()->getPluginManager()->getPlugin("SCoinAPI");
            
            
    
        }

   
   public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
           switch ($command->getName()){  
                  case "pickaxe":
                  if(!$sender instanceof Player) {
                     $this->getLogger()->emergency("?");
                     return false;
                  }
                       $this->showForm($sender);
                  break;
                  }
     return false;
    }
        
   
    
    public function showForm($player){
        $dir = $this->FormAPI->createSimpleForm(function (Player $player, int $data = null){
        if($data === null){
           return true;
           }
           
           switch ($data){
             case 0:
              if(UsersData::getLevel($player) > 99){
              $this->onDebugEffect($player);    
              } else $player->sendMessage("§l[§6*§f]§8 Không thể mở tính năng ngay bây giờ.");
             break;    
             case 1:
             $this->invTrophy($player);
             break;
             case 2:
                foreach($player->getInventory()->getContents() as $index => $item) {
                   $icn = explode(" ", $item->getCustomName());
                   if($icn[0] == "§r§l§c⇀") {
                      $player->getInventory()->setItem($index, Item::get(0, 0, 0));
                   }
                }
             break;
             case 3:
           if(isset($this->statusBar[$player->getName()])){
              unset($this->statusBar[$player->getName()]);   
           } else {
              $this->statusBar[$player->getName()] = true;
             }       
             break;
             
      }
        });
        
        $dir->setTitle("§l§8Lvl Pickaxe");
        $dir->setContent("§l§e⇀§6 Cấp độ:§c ".UsersData::getLevel($player)."\n\n§e⇀§b Kinh nghiệm hiện tại:§6 ".UsersData::getCurrent($player)."§f/§c".UsersData::getMax($player));
        $dir->addButton("§l§8Bổ sung\n§f[§2!§f]§8 Yêu cầu trình độ đạt cấp§e 100");
        $dir->addButton("§l§8Tính năng\n§f[§2!§f]§8 Đổi Trophy của bạn đang dùng.");
        $dir->addButton("§l§8Loại bỏ LvPickaxe\n§f[§2*§f]§8 Nhấn để thực hiện");
        if(isset($this->statusBar[$player->getName()])){
           $statusBar = "§l§cTắt thanh trạng thái";
           } else $statusBar = "§l§bBật thanh trạng thái";
        $dir->addButton($statusBar);
        $dir->sendToPlayer($player);
    return $dir;
        
    }
    
    public function onDebugEffect($player){
       $pir = $this->FormAPI->createCustomForm(function (Player $player, array $data = null){
           
       if($data == null){
          $this->showForm($player);
          return true;    
          }
          
      /**  if($data[2] == true && $data[3] == true){
          $player->sendMessage("§l[§c!§f]§c Không thể bật cả hai cùng lúc! Xin cân nhắc.");
          return true;
          } */
          
      switch($data[1]) {
         case true:
            $this->doubleItem[$player->getName()] = true;
         break;
         case false:
            unset($this->doubleItem[$player->getName()]);
         break;        
      }

      
      switch($data[2]) {
         case true:
            $this->haste[$player->getName()] = true;
         break;
         case false;
            unset($this->haste[$player->getName()]);
         break;
      }      
       
       
           
       });
      
       $pir->setTitle("§8Lvl Pickaxe Debug");
       
  #     $pir->addLabel("§l[§!§f]§8 Không thể bật cả hai Debug cùng lúc. Xin cân nhắc!\n");
       
          $lvl = UsersData::getLevel($player);
       if($lvl > 99 && $lvl< 151){
           $value = 30;
          } elseif($lvl > 149 && $lvl < 201){
                   $value = 50;
            } elseif($lvl > 200){
                   $value = 100;
                   }
                   
       $pir->addLabel("§l[§e*§f]§8 Tỉ suất thực hiện:§a ".$value."%");
       
       if(isset($this->doubleItem[$player->getName()])) {
          $idb = true;
       } else $idb = false;
       
       $pir->addToggle("§l§b⇀§8 Tăng khả năng Ores rơi ra", $idb);
       if(isset($this->haste[$player->getName()])) {
          $current = true;
          } else $current = false;
       $pir->addToggle("§l§b⇀§8 Nhanh nhẹn khi làm việc", $current);
       $pir->sendToPlayer($player);
       
      return $pir;
      
      /** Heeh */
    
    
    }
        
    public function invTrophy($player) {
        
    /** Function */
    
        $unt = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $unt->setName("§l§8Trophy Menu");
        $ine = $unt->getInventory();
        
    /** Current Trophy */
    
      if(UsersData::getLevel($player) > 0 && UsersData::getLevel($player) < 21){
         $i1 = Item::get(274, 0, 1);
      } elseif(UsersData::getLevel($player) > 20 && UsersData::getLevel($player) < 41){
         $i1 = Item::get(257, 0, 1);    
      } elseif(UsersData::getLevel($player) > 40){
         $i1 = Item::get(278, 0, 1);      
    }
      
      $this->EventListener->initItem($player, $i1);
 
      $ine->setItem(13, $i1);
      
    /** Upgrade Trophy */
    
    $imer = Item::get(388, 0, 1);
    $imer->setCustomName("§r§l§a⇀§8 Nâng cấp");
    $imer->setLore(array("§r§l§8Lvl Pickaxe\n\n§b-------\n§e⇀§6 Chi phí nâng cấp§b 20§6 SCoin.\n§e⇀§c Lvl Hiện tại:§b ".(UsersData::getLevel($player))."§e ↦§b ".(UsersData::getLevel($player) + 1)));
    $imer->setNamedTagEntry(new StringTag("Trophy", "imer"));
    $ine->setItem(46, $imer);
      
    /** Chose Trophy */
    
    $i2 = Item::get(274, 0, 1);
    $i3 = Item::get(257, 0, 1);
    $i4 = Item::get(278, 0, 1);
    
   foreach(array($i2, $i3, $i4) as $it){
      $this->EventListener->initItem($player, $it);
      }
    
   $i2->setNamedTagEntry(new StringTag("Trophy", "i2"));
   $i3->setNamedTagEntry(new StringTag("Trophy", "i3"));
   $i4->setNamedTagEntry(new StringTag("Trophy", "i4"));
   
   $ine->setItem(30, $i2);
   $ine->setItem(31, $i3);
   $ine->setItem(32, $i4);
   
  for($index = 0; $index < 54; $index++){
     if(!in_array($index, array(13, 30, 31, 32, 46))){
        $ine->setItem($index, Item::get(101, 0, 1));
       }    
  }
   
   $unt->setListener(InvMenu::readonly(function (DeterministicInvMenuTransaction $transaction): void {
   $player = $transaction->getPlayer();
   $item = $transaction->getItemClicked();
   $itemClickWith = $transaction->getItemClickedWith();
   $inv = $transaction->getAction()->getInventory();
   $position = new Vector3 ($player->getX(), $player->getY(), $player->getZ());
   
   /** Event Action */
   
  if($item->getNamedTag()->hasTag("Trophy")){
     switch ($item->getNamedTag()->getString("Trophy")){
             case "i2":
   foreach($player->getInventory()->getContents() as $index => $i){
           $icn = explode(" ", $i->getCustomName());       
        if($icn[0] == "§r§l§c⇀"){           
           $player->getInventory()->setItem($index, Item::get(0, 0, 0));
           }                          
         }             
            $item->getNamedTag()->removeTag("Trophy");
            $player->getInventory()->addItem($item);
            $player->getLevel()->addSound(new AnvilUseSound($position));

            break;
            case "i3":
          if(UsersData::getLevel($player) > 19){
    foreach($player->getInventory()->getContents() as $index => $i){
             $icn = explode(" ", $i->getCustomName());
         if($icn[0] == "§r§l§c⇀"){
             $player->getInventory()->setItem($index, Item::get(0, 0, 0));
            } 
          }                 
        } else {
           $player->getLevel()->addSound(new AnvilFallSound($position));    
           }
       if(UsersData::getLevel($player) > 19){
           $item->getNamedTag()->removeTag("Trophy");
           $player->getInventory()->addItem($item);
           $player->getLevel()->addSound(new AnvilUseSound($position));
         } else{
            $player->getLevel()->addSound(new AnvilFallSound($position));
            }                 
            break;
            case "i4":
       if(UsersData::getLevel($player) > 39){
         foreach($player->getInventory()->getContents() as $index => $i){
              $icn = explode(" ", $i->getCustomName());
          if($icn[0] == "§r§l§c⇀"){
              $player->getInventory()->setItem($index, Item::get(0, 0, 0));
             } 
          }
                    
        } else  {
          $player->getLevel()->addSound(new AnvilFallSound($position));    
          }
      if(UsersData::getLevel($player) > 39){
          $item->getNamedTag()->removeTag("Trophy");
          $player->getInventory()->addItem($item);
          $player->getLevel()->addSound(new AnvilUseSound($position));
         } else{
            $player->getLevel()->addSound(new AnvilFallSound($position));
              }       
           break;
           case "imer":
           
       if($this->SCoinAPI->mySCoin($player) > 19){
          $this->SCoinAPI->reduceSCoin($player, 20);          
          $item->setLore(array("§r§l§8Lvl Pickaxe\n\n§b-------\n§e⇀§6 Chi phí nâng cấp§b 20§6 SCoin.\n§e⇀§c Lvl Hiện tại:§b ".(UsersData::getLevel($player))."§e ↦§b ".(UsersData::getLevel($player) + 1)));
          $inv->setItem(46, $item);
          
          $i1 = Item::get(274, 0, 1);
          $i1->setNamedTagEntry(new StringTag("Trophy", "i2"));
          $i2 = Item::get(257, 0, 1);
          $i2->setNamedTagEntry(new StringTag("Trophy", "i3"));
          $i3 = Item::get(278, 0, 1);
          $i3->setNamedTagEntry(new StringTag("Trophy", "i4"));
          
          
         
       foreach(array($i1, $i2, $i3) as $ite){
          $this->EventListener->initItem($player, $ite);
         }
         
         $inv->setItem(30, $i1);
         $inv->setItem(31, $i2);
         $inv->setItem(32, $i3);
         
         UsersData::setLevel($player, UsersData::getLevel($player) + 1);
         $this->EventListener->onUsersDataSync($player);

       foreach($player->getInventory()->getContents() as $index => $ia){
                     $pas = explode(" ", $ia->getCustomName());

              if($pas[0] == "§r§l§c⇀"){
                  $player->getInventory()->setItem($index, Item::get(0, 0, 0));
                 }
      }
      
         
        if(UsersData::getLevel($player) > 0 && UsersData::getLevel($player) < 21){
           $ie = Item::get(274, 0, 1);
      } elseif(UsersData::getLevel($player) > 20 && UsersData::getLevel($player) < 41){
         $ie = Item::get(257, 0, 1);    
      } elseif(UsersData::getLevel($player) > 40){
         $ie = Item::get(278, 0, 1);      
      }
    
       $this->EventListener->initItem($player, $ie);
       $inv->setItem(13, $ie);
      
       $player->getInventory()->addItem($ie);
      

      
         
         $this->runSound($player, "random.levelup");
         
              
       } else $this->runSound($player, "mob.villager.no");
           
           break;
           
           }
          
        }
      
   }));  
    $unt->send($player);
    }
    
    public static function getInstance() : self
    {
       return self::$instance;
    }
    
    public function runSound(Player $player, string $string) : void
    {
       $packet = new PlaySoundPacket();
       $packet->soundName = $string;
       $packet->x = $player->getX();
       $packet->y = $player->getY();
       $packet->z = $player->getZ();
       $packet->volume = 1;
       $packet->pitch = 1;
       $player->sendDataPacket($packet);
       
    }
 }