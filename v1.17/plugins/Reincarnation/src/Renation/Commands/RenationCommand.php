<?php

namespace Renation\Commands;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\ModalForm;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;

use Renation\Loader;
use Renation\UsersData;

class RenationCommand extends Command implements PluginIdentifiableCommand
{
   
   public function __construct(Loader $plugin)
   {
      parent::__construct("chuyensinh", "show ChuyenSinh Form", ("/chuyensinb"), []);
      $this->plugin = $plugin;
   }
   
   public function getPlugin() : Loader
   {
      return $this->plugin;
   }
   
   public function getPerms(Player $player) : string
   {
      return $this->getPlugin()->PurePerms->getUserDataMgr()->getData($player)["group"];
   }
   
   public function execute(CommandSender $sender, $commandLabel, array $args)
   {
      if(!$sender instanceof Player) {
         $this->getPlugin()->getLogger()->emergency("[!] Do not use this command here Senpai!.");
         return;
      }
      
      $this->showForm($sender);
   }
   
   public function showForm(Player $player)
   {
      $show = new SimpleForm(function (Player $player, int $data = null)
      {
         switch($data) {
            case 0:
               $amount = ((UsersData::getLevel($player) + 1) * 50);
               $uni = new ModalForm(function (Player $player, $data) use ($amount) {
                  switch($data) {
                     case 0:
                     break;
                     case 1:
                        if($this->getPlugin()->SCoinAPI->mySCoin($player) > ($amount - 1)) {
                           $this->getPlugin()->SCoinAPI->reduceSCoin($player, $amount);
                           UsersData::setLevel($player, (UsersData::getLevel($player) + 1));
                           $player->setMaxHealth(20 + (int) UsersData::getSaleItem($player));
                           $this->getPlugin()->syncData($player);
                           $this->getPlugin()->syncDatd($player);
                           $player->sendMessage("§l[§a*§f]§l§a Chuyển sinh thành công!");
                        }
                     break;
                     case 2:
                     break;  
                  }
               });
               $uni->setTitle("§l§e•§c Chuyển Sinh §e•");
               $uni->setContent("§l[§a*§f]§l§e Bạn có muốn nâng cấp chuyển sinh trình độ§c ".(UsersData::getLevel($player) + 1)."§e với giá§c ".$amount." SCoin?.");
               $uni->setButton1("§l§e•§a Đồng Ý §e•");
               $uni->setButton2("§l§e•§c Hủy §e•");
               $uni->sendToPlayer($player);
               
            break;
         }
         
      });
      
      $show->setTitle("§l§e•§c Chuyển Sinh §e•");
      $show->setContent("§l§e⇀§c Trình độ Chuyển Sinh: §a".(UsersData::getLevel($player))."\n§l§e⇀§c Giá trị Item và số lượng Item Spawn tăng thêm:§a ".(UsersData::getSaleItem($player)));
      $show->addButton("§l§c•§9 NÂNG CẤP §c•", 0, "textures/other/up");
      $show->sendToPlayer($player);
   }
}