<?php

namespace LvPickaxe;

use LvPickaxe\Loader;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;

use LvPickaxe\UsersData;

class TimerRun extends Task
{

   public function __construct(Loader $plugin)
   {
      $this->plugin = $plugin;
   }

   public function getPlugin()
   {
      return $this->plugin;
   }

   public function onRun(int $currentTick)
   {
      foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player) 
      {
   
      /** Function */
      
        $icn = $player->getInventory()->getItemInHand();
        $pas = explode(" ", $icn->getCustomName());
           if(isset($this->getPlugin()->haste[$player->getName()])) {
              $effect = Effect::getEffect(3);
              $instance = new EffectInstance($effect);
              $player->addEffect($instance->setAmplifier(2)->setDuration(3*20));
           }
           if($pas[0] == "§r§l§c⇀") {
              if($icn->getDamage() > 30) {
                  $icn->setDamage(0);
                  $player->sendPopup("§l[§e!§f]§7 Lvl Pickaxe đã được sửa chữa.");
                  return;
              }
             
             if(isset($this->getPlugin()->statusBar[$player->getName()])) {
                 $player->sendPopup(UsersData::getNamePickaxe($player)."\n§b⇀§7 Tiến độ:§a ".round(UsersData:: getCurrent($player)*100/UsersData::getMax($player)));
                 }
           } else {
              $this->getPlugin()->getScheduler()->cancelTask($this->getTaskId());
           }
        }
   }
}