<?php

namespace dichchuyen;

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
        $this->getLogger()->info("Dịch Chuyển Đã Bật");
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
        case "dichchuyen":
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
					$command = "warp pvp";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);                       
					    break;
                    case 2:
					$command = "warp farmquai";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);                       
					    break;
					case 3:
					$command = "warp crates";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
					case 4:
					$command = "warp parkour";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
					case 5:
					$command = "warp boss";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
					    break;
            }
        });
        $form->setTitle("§l§f•§a Dịch Chuyển §f•");
        $form->setContent("§l§c↣§e Các Khu Dịch Chuyển");
        $form->addButton("§l§c•§9 Thoát §c•", 0);
		$form->addButton("§l§c•§9 PVP §c•", 1);
		$form->addButton("§l§c•§9 Farm Quái §c•", 2);
		$form->addButton("§l§c•§9 Crates §c•", 3);
		$form->addButton("§l§c•§9 Parkour §c•", 4);
		$form->addButton("§l§c•§9 Boss §c•", 1);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("Dịch Chuyển Đã Tắt");
    }
}
