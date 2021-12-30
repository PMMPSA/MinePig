<?php

namespace Renation;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use Renation\Commands\SaleCommand;
use Renation\Commands\RenationCommand;
use Renation\EventListener;
use Renation\TagResolveListener;
use Renation\UsersData;


class Loader extends PluginBase
{
   
   /** 
   ░█─▄▀ █▀█ ▀▀▀█ 
  ░█▀▄─ ─▄▀ ──█─ 
 ░█─░█ █▄▄ ─▐▌─ */
   
   /** Do not use float **/
   
   public $item = [
   "1:0" => 5,
   "1:1" => 10,
   "1:3" => 20,
   "1:5" => 30,
   "4:0" => 50,
   "87:0" => 60,
   "17:0" => 70,
   "388:0" => 80,
   "264:0" => 90,
   "14:0" => 100,
   "15:0" => 110,
   "331:0" => 120,
   "351:4" => 130,
   "263:0" => 140,
   "338:0" => 150,
   "296:0" => 160,
   "360:0" => 170,
   "391:0" => 180,
   "392:0" => 190,
   "81:0" => 200,
   "86:0" => 210];
   
   
   
   const CURRENT_VERSION = "MinePIG";
   
   public function onLoad()
   {
      if(!is_dir($this->getDataFolder())) {
         mkdir($this->getDataFolder());
      }
   }
   
   public function onEnable()
   {
      $this->database = new Config($this->getDataFolder()."config.yml", Config::YAML);
      $this->registerRenation();
   }
   
   public function registerRenation()
   {
      if(self::CURRENT_VERSION == "MinePIG" or $this->getDescription()->getAuthors() == "K27") {       
         if(is_null($this->getServer()->getPluginManager()->getPlugin("EconomyAPI"))) {
            $this->getLogger()->emergency("Hệ thống Plugin được thiết lập sẽ không hoạt động nếu máy chủ chưa được cài Plugin EconomyAPI!");
         } else $this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
         
         if(is_null($this->getServer()->getPluginManager()->getPlugin("PurePerms"))) {
            $this->getLogger()->emergency("Hệ thống Plugin được thiết lập sẽ không hoạt động nếu máy chủ chưa cài đặt và thiết đặt sẵn PurePerms.");
            } else $this->PurePerms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");  

         if(is_null($this->getServer()->getPluginManager()->getPlugin("SCoinAPI"))) {
            $this->getLogger()->emergency("[!] Download SCoinAPI!");
            $this->getServer()->getPluginManager()->disblePlugin($this);
         } else $this->SCoinAPI = $this->getServer()->getPluginManager()->getPlugin("SCoinAPI");             
         
         $this->getServer()->getCommandMap()->register("chuyensinhsell", new SaleCommand($this));
         $this->getServer()->getCommandMap()->register("chuyensinh", new RenationCommand($this));        
         $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
         
         $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
         
         UsersData::init($this);
         
         $this->getLogger()->info("§2Renation version MinePIG Enable!\n
██╗░░██╗██████╗░███████╗
██║░██╔╝╚════██╗╚════██║
█████═╝░░░███╔═╝░░░░██╔╝
██╔═██╗░██╔══╝░░░░░██╔╝░
██║░╚██╗███████╗░░██╔╝░░
╚═╝░░╚═╝╚══════╝░░╚═╝░░░\n[!] Remove all sales add-ons to avoid system duplication."); 
                 
      }else {
         $this->getLogger()->emergency("Phiên bản Plugin đã được thay đổi nghiêm trọng.");    
         $this->getServer()->getPluginManager()->disablePlugin($this);
      }
      
   }
   
   /** Renation API */
   
   public function syncData(Player $player)
   {
      $max = (UsersData::getSaleItem($player) + 20);
      $player->setMaxHealth($max);
   }
   
   public function syncDatd(Player $player) {
   }
   
}