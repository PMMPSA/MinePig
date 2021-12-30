<?php

namespace Bank;

use pocketmine\Server;
use pocketmine\scheduler\Task;
use pocketmine\level\Level;
use pocketmine\event\Listener;
use Bank\Main;

class InterestRate extends Task implements Listener{
    
    public $time = 0;
    
    public $login = 0;
    
    public function __construct(Main $plugin)
    {
       $this->plugin = $plugin;
    }
    
    public function getPlugin()
    {
       return $this->plugin;
    }
    
    public function onRun(int $currentTick)
    {
       foreach($this->getPlugin()->getServer()->getOnlinePlayers() as $player) {
          if(!$this->getPlugin()->onATM($player)) {
             return;
          }
          
          if(isset($this->getPlugin()->logan[$player->getName()])){
             $this->login++;
             
             if($this->login == 180){
                $this->login = 0;
                unset($this->getPlugin()->logan[$player->getName()]);    
             }
          }
             
            $equal = $this->getPlugin()->getArray($player, "money")/1000000;
            
            if($this->time == 60){
             
                  $player->sendPopup("§l§b⇀§e +".$equal." Ngân Hàng!");
                
                $this->getPlugin()->addInt($player, $equal);
                $this->time = 0;
             }
                      $this->time++;
          }

       }
    
    }