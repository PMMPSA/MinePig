<?php

namespace Renation\Commands;

use pocketmine\Player;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\Command;
use pocketmine\item\Item;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;

use Renation\Loader;
use Renation\UsersData;

class SaleCommand extends Command implements PluginIdentifiableCommand
{
   
   public function __construct(Loader $plugin) 
   {
      parent::__construct("chuyensinhsell", "Sale Item with ChuyenSinh API", ("/chuyensinhsell"), []);
      
      $this->plugin = $plugin;
   }
   
   public function getPlugin() : Loader
   {
      return $this->plugin;
   }
   
   public function execute(CommandSender $sender, $commandLabel, array $args) 
   {
      $this->showForm($sender);
   }
   
   /** MY FUNCTION **/
   
   public function showForm(Player $player)
   {
      
      $show = new SimpleForm(function (Player $player, int $data = null)
      {
         if($data === null) return;
         switch($data) {
            case 0:
               $items = $player->getInventory()->getItemInHand();
               
               if($items->getId() === 0) {
                  $player->sendMessage("§l[§c!§f]§l§a Không thể bán được Air.");
                  return;
               }
               
               if(isset($this->getPlugin()->item[$items->getId().":".$items->getDamage()])) {
                  
                  $amount = $this->getPlugin()->item[$items->getId().":".$items->getDamage()];   
                  $status = (UsersData::getSaleItem($player) * ($items->getCount() * $amount));
                  $player->getInventory()->setItemInHand($items->setCount($items->getCount() - (int) $items->getCount()));
                  $this->getPlugin()->EconomyAPI->addMoney($player, $status);
                  $player->sendPopup("§l[§a+§f]§l§a Cộng§e ".$status."§a vào tài khoản chính!");
                  
               }elseif(isset($this->getPlugin()->item[$items->getId()])) {
                  
                  $amount = $this->getPlugin()->item[$items->getId()];
                  $status = (UsersData::getSaleItem($player) * ($items->getCount() * $amount));
                  
                  $player->getInventory()->setItemInHand($items->setCount($items->getCount() - (int) $items->getCount()));
                  $this->getPlugin()->EconomyAPI->addMoney($player, $status);
                  $player->sendPopup("§l[§a+§f]§l§a Cộng§e ".$status."§a vào tài khoản chính!"); 
            
               }else $player->sendMessage("[§c!§f]§l§c Không thể bán §e".$items->getCount()."§6 ".$items->getName());
            
            break;  
            case 1:           
               $slot = 0;
               
               foreach($player->getInventory()->getContents() as $items) {
                  
                  if(isset($this->getPlugin()->item[$items->getId().":".$items->getDamage()])) {
                     $slot += (($this->getPlugin()->item[$items->getId().":".$items->getDamage()] * $items->getCount()) * UsersData::getSaleItem($player));
                     $player->getInventory()->removeItem($items);
                     
                  } elseif(isset($this->getPlugin()->item[$items->getId()])) {
                    $slot += ($this->getPlugin()->item[$items->getId()] * $items->getCount());
                    $player->getInventory()->removeItem($items);
                  } 
                }
              if($slot < 1) {
                 $player->sendMessage("§l[§c!§f]§l§a Túi đồ của bạn không có vật phẩm nào để bán.");
                 return;
              }
              
              $this->getPlugin()->EconomyAPI->addMoney($player, $slot);
              $player->sendPopup("§l[§a+§f]§l§a Cộng§e ".$slot."§a vào tài khoản chính!");              
               
            break;
            case 2:
               $itemInHand = $player->getInventory()->getItemInHand();
               $slot = 0;
               foreach($player->getInventory()->getContents() as $items) {
                  if($itemInHand->getId() == $items->getId()) {
                    
                     if(isset($this->getPlugin()->item[$items->getId().":".$items->getDamage()])) {
                        $slot += (($this->getPlugin()->item[$items->getId().":".$items->getDamage()] * $items->getCount()) * UsersData::getSaleItem($player));
                        $player->getInventory()->removeItem($items);
                     } elseif(isset($this->getPlugin()->item[$items->getId()])) {
                        $slot += ($this->getPlugin()->item[$items->getId()] * $items->getCount());
                        $player->getInventory()->removeItem($items);
                     }                                        
                  }
               }
               
               if($slot < 1) {
                  $player->sendMessage("§l[§c!§f]§l§8 Túi đồ của bạn không có vật phẩm nào để bán.");
                 return;
               }
               
               $this->getPlugin()->EconomyAPI->addMoney($player, $slot);
              $player->sendPopup("§l[§a+§f]§l§a Cộng§e ".$slot."§a vào tài khoản chính!");           
              
            break;
         }
      });
      $show->setTitle("§l§e•§c Sale Items §e•");
      $show->addButton("§l§cBÁN VẬT PHẨM TRÊN TAY\n§7Click to sell", 0, "textures/items/sweet_berries");
      $show->addButton("§l§6BÁN TẤT CẢ VẬT PHẨM\n§7Click to sell", 0, "textures/items/apple");
      $show->addButton("§l§bBÁN VẬT PHẨM CÙNG ID\n§7Click to sell", 0, "textures/items/apple_golden");
      $show->sendToPlayer($player);
   }
   
} 