<?php

namespace QVip;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("QVip Đã Bật");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->checkDepends();
    }

    public function checkDepends(){
        $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        if(is_null($this->formapi)){
            $this->getLogger()->info("§4Hãy Cài Plugin FormAPI Để Được Trải Nghiệm");
            $this->getPluginLoader()->disablePlugin($this);
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "vip":
        if(!($sender instanceof Player)){
                return true;
        }
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 0:
                        break;
                    case 1:
					$command = "§l§l§f§f";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);                       
					    break;
                    case 2:
					$command = "§l§c§l§c";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);                       
					    break;
					case 3:
					$command = "§l§d§l§d";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
					 case 4:
					$command = "§l§l§l§l§l";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
					 case 5:
					$command = "§l§b§l§b";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
            }
        });
        $form->setTitle("§l§a⚫§d V§bI§6P §a⚫");
        $form->addButton("§l§9⚫§c Thoát §9⚫", 0);
		$form->addButton("§l§9⚫§d VIP §eI §9⚫", 1);
		$form->addButton("§l§9⚫§d VIP§e II §9⚫", 2);
		$form->addButton("§l§9⚫§d VIP §eIII §9⚫", 3); 
		$form->addButton("§l§9⚫§d VIP§e IV §9⚫", 4); 
		$form->addButton("§l§9⚫§d VIP §eV §9⚫", 5);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("QVip Đã Tắt");
    }
}
