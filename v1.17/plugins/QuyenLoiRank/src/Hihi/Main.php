<?php

namespace Hihi;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("m Quyền Lợi Rank  nmfjf by PIG ...");
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
        case "§a§a§b":
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
        $form->setTitle("§l§e•§a Quyền Rank §e•");
        $form->setContent("§l§c↣§a Quyền Coal:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§c↣§a Quyền Iron:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§c↣§a Quyền Gold:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§f- §eHeal §a(§6 /heal§a ):§c Hồi Thanh Máu\n§l§f- §eNick §a(§6 /nick§a ):§c Thay Đổi Tên\n§l§c↣§a Quyền Diamond:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§f- §eHeal §a(§6 /heal§a ):§c Hồi Thanh Máu\n§l§f- §eNick §a(§6 /nick§a ):§c Thay Đổi Tên\n§l§f- §eSize §a(§6 /size§a ):§c Thay Đổi Kích Thước\n§l§f- §eSpider §a(§6 /spider§a ):§c Leo Tường\n§l§c↣§a Quyền Emerald:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§f- §eHeal §a(§6 /heal§a ):§c Hồi Thanh Máu\n§l§f- §eNick §a(§6 /nick§a ):§c Thay Đổi Tên\n§l§f- §eSize §a(§6 /size§a ):§c Thay Đổi Kích Thước\n§l§f- §eSpider §a(§6 /spider§a ):§c Leo Tường\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eVanish §a(§6 /vanish§a ):§c Tàn Hình\n§l§c↣§a Quyền Legend:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§f- §eHeal §a(§6 /heal§a ):§c Hồi Thanh Máu\n§l§f- §eNick §a(§6 /nick§a ):§c Thay Đổi Tên\n§l§f- §eSize §a(§6 /size§a ):§c Thay Đổi Kích Thước\n§l§f- §eSpider §a(§6 /spider§a ):§c Leo Tường\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eVanish §a(§6 /vanish§a ):§c Tàn Hình\n§l§f- §eTeleport §a(§6 /tp§a ):§c Dịch Chuyển\n§l§c↣§a Quyền Master:\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eFeed §a(§6 /feed§a ):§c Hồi Thanh Thức Ăn\n§l§f- §eHeal §a(§6 /heal§a ):§c Hồi Thanh Máu\n§l§f- §eNick §a(§6 /nick§a ):§c Thay Đổi Tên\n§l§f- §eSize §a(§6 /size§a ):§c Thay Đổi Kích Thước\n§l§f- §eSpider §a(§6 /spider§a ):§c Leo Tường\n§l§f- §eFly §a(§6 /fly§a ):§c Bay trên không\n§l§f- §eVanish §a(§6 /vanish§a ):§c Tàn Hình\n§l§f- §eTeleport §a(§6 /tp§a ):§c Dịch Chuyển");
        $form->addButton("Submit", 0);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("n se n n  Disabled!");
    }
}