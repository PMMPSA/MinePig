<?php

namespace ThongTinA;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("ConCac quyenvip  V1.0 by PIG ...");
                $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->checkDepends();
    }

    public function checkDepends(){
        $this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        if(is_null($this->formapi)){
            $this->getLogger()->info("§4Please install FormAPI Plugin, disabling plugin...");
            $this->getPluginLoader()->disablePlugin($this);
        }
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "§a§a§a":
        if(!($sender instanceof Player)){
                $sender->sendMessage("§7Please use this command In-Game");
                return true;
        }
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
                    case 0: 
                    $command = "rank";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
            }
        });
        $form->setTitle("§l§e•§a Thông Tin §e•");
        $form->setContent("§l§c↣§e Rank:\n§l§6•§a Coal\n§l§6•§a Iron\n§l§6•§a Gold\n§l§6•§a Diamond\n§l§6•§a Emerald\n§l§6•§a Legend\n§l§6•§a Master\n\n§l§c↣§e Giá Rank:\n§l§6•§a Coal: 3500000 \n§l§6•§a Iron: 4500000 \n§l§6•§a Gold: 5500000 \n§l§6•§a Diamond: 6500000 \n§l§6•§a Emerald: 7500000 \n§l§6•§a Legend: 8500000 \n§l§6•§a Master: 9500000 ");
        $form->addButton("Submit", 0);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("Cks V1.0 by đj  Disabled!");
    }
}