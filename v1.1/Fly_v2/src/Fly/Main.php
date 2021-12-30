<?php

namespace Fly;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\CommandExecutor;
use pocketmine\event\player\PlayerMoveEvent;

class Main extends PluginBase implements Listener {

    public $players = array();

     public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getLogger()->info(TextFormat::GREEN . "Plugin Fly v2 Chỉnh Sửa Và Việt Hóa Bởi Ghast Noob");
     }

     public function onDisable() {
        $this->getLogger()->info(TextFormat::RED . "Plugin Fly v2 Chỉnh Sửa Và Việt Hóa Bởi Ghast Noob");
     }
   
     public function onEntityDamage(EntityDamageEvent $event) {
        if($event instanceof EntityDamageByEntityEvent) {
        $damager = $event->getDamager();
           if($damager instanceof Player && $this->isPlayer($damager)) {
              $damager->sendTip(TextFormat::RED . "§e༺§l§bＦＬＹ§r§e༻§l§c Vui Lòng Tắt Fly Để PvP. Cám Ơn!");
              $event->setCancelled(true);
           }
        }
     }
     
    public function onCommand(CommandSender $sender, Command $cmd, $label,array $args) {
        if(strtolower($cmd->getName()) == "fly") {
            if($sender instanceof Player) {
                if($this->isPlayer($sender)) {
                    $this->removePlayer($sender);
                    $sender->setAllowFlight(false);
                    $sender->sendMessage(TextFormat::YELLOW . "§e༺§l§bＦＬＹ§r§e༻§c§l Chế Độ Fly Đã Vô Hiệu Hóa");
                    return true;
                }
                else{
                    $this->addPlayer($sender);
                    $sender->setAllowFlight(true);
                    $sender->sendMessage(TextFormat::YELLOW . "§e༺§l§bＦＬＹ§r§e༻§a§l Chế Độ Fly Đã Kích Hoạt");
                    return true;
                }
            }
            else{
                $sender->sendMessage(TextFormat::RED . "§e༺§l§bＦＬＹ§r§e༻§c§l Chế Độ Fly Đã Vô Hiệu Hóa");
                return true;
            }
        }
    }
    public function addPlayer(Player $player) {
        $this->players[$player->getName()] = $player->getName();
    }
    public function isPlayer(Player $player) {
        return in_array($player->getName(), $this->players);
    }
    public function removePlayer(Player $player) {
        unset($this->players[$player->getName()]);
    }
}
