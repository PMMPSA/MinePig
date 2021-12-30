<?php

namespace Vip4;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("Enable djdk  V1.0 dd đ ...");
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
        case "§l§l§l§l§l":
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
                    $command = "vip";
					           $this->getServer()->getCommandMap()->dispatch($sender, $command);
                        break;
            }
        });
        $form->setTitle("§l§9⚫§d VIP IV §9⚫");
        $form->setContent("§l§c↣§b/fly: §aBay\n§l§c↣§b/feed: §aHồi thanh thức ăn\n§l§c↣§b/heal:§a Thanh hồi máu\n§l§c↣§b/tp: §aDịch chuyển\n§l§c↣§b/spider: §aLeo tường\n§l§c↣§b/vanish:§a Tàn hình\n§l§c↣§b/nick: §aĐổi tên\n§l§c↣§b/size: §aĐổi kích thước\n§l§c↣§e Chat nổi bật hơn các rank khác");
        $form->addButton("§l§e•§c Thoát §e•", 0);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("xx xx by PIG  dds!");
    }
}