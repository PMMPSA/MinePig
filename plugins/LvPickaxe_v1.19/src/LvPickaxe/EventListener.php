<?php

namespace LvPickaxe;

use LvPickaxe\Loader;
use LvPickaxe\UsersData;
use LvPickaxe\TimerRun;
use LvLickaxe\Event\TrophyEvent;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;

use pocketmine\inventory\PlayerInventory;
use pocketmine\inventory\ChestInventory;
use pocketmine\inventory\DoubleChestInventory;
use pocketmine\inventory\AnvilInventory;

use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

use DaPigGuy\PiggyCustomEnchants\CustomEnchantManager;
use DaPigGuy\PiggyCustomEnchants\PiggyCustomEnchants;


class EventListener implements Listener{
    
    public function __construct(Loader $plugin){
        $this->plugin = $plugin;
        }
        
    public function getPlugin(){
        return $this->plugin;
        }
        
    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
     if(!UsersData::hasData($player)){
        
         UsersData::createData($player);
        
         $item = Item::get(274, 0, 1);
        
         $item->setCustomName(UsersData::getNamePickaxe($player));
         $item->setLore(array(UsersData::getLorePickaxe($player)));
      
         $player->getInventory()->addItem($item);
         
         $this->getPlugin()->statusBar[$player->getName()] = true;

        } 
     }
    
    public function onQuit(PlayerQuitEvent $event){
        
        $player = $event->getPlayer();
       if(isset($this->getPlugin()->doubleItem[$player->getName()])){
          unset($this->getPlugin()->doubleItem[$player->getName()]);
         } 
       if(isset($this->getPlugin()->haste[$player->getName()])){
           unset($this->getPlugin()->haste[$player->getName()]);
         } 
    }
    
    public function onDrop(PlayerDropItemEvent $event){
        
        $player = $event->getPlayer();
        $item = $event->getItem();
        $icn = explode(" ", $item->getCustomName());
  if($icn[0] == "§r§l§c⇀"){
    if(in_array($player->getName(), $icn)){
        $event->setCancelled(true);
         } else{
          $player->getInventory()->removeItem($item);
          $player->sendMessage("§l[§c!§f]§8 Loại bỏ§e LvPickaxe§8 trong hành trang bởi vì nó không phải của bạn.");
          $event->setCancelled(true);
            }
        }
    }
        
    public function onUsersDataSync($player){
        
        $int = UsersData::getLevel($player)*500;
        
    if(in_array(UsersData::getLevel($player), array(10, 20, 30, 40, 50, 60, 70, 80, 90, 100))){
        $this->getPlugin()->EconomyAPI->addMoney($player->getName(), UsersData::getLevel($player) * 4000);
        $player->sendPopup("§l[§e!§f] §6Cộng§b ".(UsersData::getLevel($player) * 4000)."§6 vào tài khoản chính!");
             } else {
        $this->getPlugin()->EconomyAPI->addMoney($player->getName(), $int);
        $player->sendPopup("§l[§e!§f] §6Cộng§b ".(UsersData::getLevel($player) * 500)."§6 vào tài khoản chính!");
      
           }
   
       $player->sendMessage("§l§a".UsersData::getLevel($player)."! §8Lvl Pickaxe vừa lên cấp.");

      if(UsersData::getLevel($player) > 99){
       if(!isset($this->getPlugin()->doubleItem[$player->getName()])){
          $this->getPlugin()->doubleItem[$player->getName()] = false;
       }       
      }
        
    }
        
    public function itemSync(Player $player, Item $item) {
        
        if(UsersData::getLevel($player) > 0){
           $item->setCustomName(UsersData::getNamePickaxe($player));
           $item->setLore(array(UsersData::getLorePickaxe($player)));
        }
        
        /** Level 2 */
        
        if(UsersData::getLevel($player) > 1){
            $item->setCustomName(UsersData::getNamePickaxe($player));
            $item->setLore(array(UsersData::getLorePickaxe($player)));
            $enc = Enchantment::getEnchantment(15);
            $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
            $item->addEnchantment($enchant);  
           } 
        
        /** Level 20 */
        
        if(UsersData::getLevel($player) > 19){
            $item->setCustomName(UsersData::getNamePickaxe($player));
            $item->setLore(array(UsersData::getLorePickaxe($player)));
            $enc = Enchantment::getEnchantment(17);
            $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
            $item->addEnchantment($enchant);
           } 
            
        /** Level 60 */ 
        
        if(UsersData::getLevel($player) > 59){
           $item->setCustomName(UsersData::getNamePickaxe($player));
           $item->setLore(array(UsersData::getLorePickaxe($player)));
           $enc = CustomEnchantManager::getEnchantmentByName("Jackpot");
           $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
           $item->addEnchantment($enchant);
           }

       $player->getInventory()->setItemInHand($item);
        
       }

    public function breakOres(Player $player, Block $block){

     $item = $player->getInventory()->getItemInHand();
          switch ($block->getId()){
            case 1:
              UsersData::addCurrent($player, 1);
              $this->despawnItem($player, $item);
            break;
            case 14:
              UsersData::addCurrent($player, 4);
              $this->despawnItem($player, $item);
            break;
            case 15:
              UsersData::addCurrent($player, 3);
              $this->despawnItem($player, $item);
            break;
            case 16:
              UsersData::addCurrent($player, 2);
              $this->despawnItem($player, $item);
            break;
            case 21:
              UsersData::addCurrent($player, 3);
              $this->despawnItem($player, $item);
            break;
            case 56:
              UsersData::addCurrent($player, 7);
              $this->despawnItem($player, $item);
            break;
            case 73:
            case 74:
              UsersData::addCurrent($player, 3);
              $this->despawnItem($player, $item);
            break;
            case 129:
              UsersData::addCurrent($player, 9);
              $this->despawnItem($player, $item);
            break;
            }
          
     }
     
    
    public function onHold(PlayerItemHeldEvent $event){
        
       $task = new TimerRun($this->getPlugin(), $event->getPlayer());
       $this->tasks[$event->getPlayer()->getId()] = $task;
       $this->getPlugin()->getScheduler()->scheduleRepeatingTask($task, 20);
 
        
        $player = $event->getPlayer();
        $item = $player->getInventory()->getItemInHand();
        
     if(isset($this->getPlugin()->active[$player->getName()])){
        $this->itemSync($player, $item);
        if(UsersData::getLevel($player) == 20){
           $player->sendMessage("§l[§e!§f]§8 Bây giờ bạn có thể sử dụng§e LvPickaxe§f Iron.");
           } elseif(UsersData::getLevel($player) == 40) {
           $player->sendMessage("§l[§e!§f]§8 Bây giờ bạn có thể sử dụng§e LvPickaxe§b Diamond.");
         }   
           unset($this->getPlugin()->active[$player->getName()]);
           }
        }

  
   public function despawnItem(Player $player, Item $item){
         $item->setCustomName(UsersData::getNamePickaxe($player));
         $item->setLore(array(UsersData::getLorePickaxe($player)));
         $player->getInventory()->setItemInHand($item);
    }

    public function initItem(Player $player, Item $item) {
       if(UsersData::getLevel($player) > 0){
           $item->setCustomName(UsersData::getNamePickaxe($player));
           $item->setLore(array(UsersData::getLorePickaxe($player)));
        }
        
        /** Level 2 */
        
        if(UsersData::getLevel($player) > 1){
            $item->setCustomName(UsersData::getNamePickaxe($player));
            $item->setLore(array(UsersData::getLorePickaxe($player)));
            $enc = Enchantment::getEnchantment(15);
            $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
            $item->addEnchantment($enchant);  
           } 
        
        /** Level 20 */
        
        if(UsersData::getLevel($player) > 19){
            $item->setCustomName(UsersData::getNamePickaxe($player));
            $item->setLore(array(UsersData::getLorePickaxe($player)));
            $enc = Enchantment::getEnchantment(17);
            $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
            $item->addEnchantment($enchant);
           } 
        
        /** Level 60 */ 
        
        
        if(UsersData::getLevel($player) > 59){
           $item->setCustomName(UsersData::getNamePickaxe($player));
           $item->setLore(array(UsersData::getLorePickaxe($player)));
           $enc = CustomEnchantManager::getEnchantmentByName("Jackpot");
           $enchant = new EnchantmentInstance($enc, UsersData::getLevel($player)/2);
           $item->addEnchantment($enchant);
           }

        $item->setCustomName(UsersData::getNamePickaxe($player));
        $item->setLore(array(UsersData::getLorePickaxe($player)));
     }
    
    
    public function onBreak(BlockBreakEvent $event){
        
        $block = $event->getBlock();
        $player = $event->getPlayer();
        
        $item = $player->getInventory()->getItemInHand();
        $icn = explode(" ", $item->getCustomName());
    
         if($icn[0] == "§r§l§c⇀"){
            if(!in_array($player->getName(), $icn)){
               $player->getInventory()->removeItem($item);
               $player->sendMessage("§l[§c!§f]§8 Loại bỏ§e LvPickaxe§8 trong hành trang bởi vì nó không phải của bạn.");
               $event->setCancelled(true);
               return true;
              }
            }
        if(!$event->isCancelled()){
          if($icn[0] == "§r§l§c⇀"){
            
              $this->breakOres($player, $block);
 
            
            if(UsersData::getCurrent($player) >= UsersData::getMax($player)){
                UsersData::setLevel($player, UsersData::getLevel($player) + 1);
                $this->onUsersDataSync($player);
    
                $this->getPlugin()->active[$player->getName()] = true;
                 }   
      
            
              }
           }

     if(isset($this->getPlugin()->doubleItem[$player->getName()])){
            
        if(isset($this->getPlugin()->doubleItem[$player->getName()])){
          if($icn[0] == "§r§l§c⇀"){
             $lvl = UsersData::getLevel($player);
            
        if($lvl > 99 && $lvl< 151){
           $value = 3;
           $it = 2;
          } elseif($lvl > 149 && $lvl < 201){
                   $value = 2;
                   $it = 4;
            } elseif($lvl > 200){
                   $value = 1;
                   $it = 8;
                   }
                
        $is = rand(1, $value);
                
        if($is == $value){
            $drop = array();
      
            $drop[] = Item::get($block->getId(), 0, (count($event->getDrops()) * $it));

            } else $drop[] = Item::get($block->getId(), 0, count($event->getDrops()));
             
            $event->setDrops($drop);


                  }
                
                }
            
            }
    
        }
        
   public function onChangeItem(InventoryTransactionEvent $event)
   {
      $inventory = $event->getTransaction()->getInventories();
      $transaction = $event->getTransaction()->getActions();
      $express = null;
      $press = null;

      foreach($transaction as $action){
         foreach($inventory as $invent) {
            if($invent instanceof PlayerInventory) {
                $press = true;
            }
           if($invent instanceof ChestInventory || $invent instanceof DoubleChestInventory || $invent instanceof AnvilInventory) {
               $express = true;
           }    
           if($express && $press) {
               $item = $action->getTargetItem();
               $icn = explode(" ", $item->getCustomName());
               if($icn[0] == "§r§l§c⇀") {
                   $event->setCancelled(true);
              }
           }
       } 
     }
    }
 }