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
        $this->getLogger()->info(TextFormat::GREEN . "Plugin Fly v1 by PIG");
     }

     public function onDisable() {
        $this->getLogger()->info(TextFormat::RED . "Plugin Fly v1 by PIG");
     }
   
     public function onEntityDamage(EntityDamageEvent $event) {
        if($event instanceof EntityDamageByEntityEvent) {
        $damager = $event->getDamager();
           if($damager instanceof Player && $this->isPlayer($damager)) {
              $damager->sendTip("§l§cHãy Tắt Fly Khi PvP");
              $event->setCancelled(true);
           }
        }
     }
     
    public function onCommand(CommandSender $sender, Command $cmd, $label,array $args) : bool {
        if(strtolower($cmd->getName()) == "fly") {
            if($sender instanceof Player) {
                if($this->isPlayer($sender)) {
                    $this->removePlayer($sender);
                    $sender->setAllowFlight(false);
                    $sender->sendMessage(" §l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã tắt chế độ bay");
                    return true;
                }
                else{
                    $this->addPlayer($sender);
                    $sender->setAllowFlight(true);
                    $sender->sendMessage(" §l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Bạn đã bật chế độ bay");
                    return true;
                }
            }
            else{
                $sender->sendMessage(" §l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn đã tắt chế độ bay");
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
