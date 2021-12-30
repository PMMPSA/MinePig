<?php

namespace YT;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\event\Listener;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getLogger()->info("Youtube By PIG");
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
        case "youtube":
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
                        break;
            }
        });
        $form->setTitle("⨬§l§c Y§6o§eu§at§bu§fb§de§r ⨬");
        $form->setContent("§l§c•§a Hướng dẫn lấy danh hiệu Youtuber:\n§l§e↣§a Bước 1: Kênh Phải Đủ Các Điều Kiện Ở Dưới\n§l§e↣§a Bước 2:§c Báo Admin & Owner\n§l§e↣§a Bước 3:§c Gửi Tên Kênh Youtube Của Bạn Để Admin & Owner Check Để Nhận Danh Hiệu Youtuber\n§l§c•§a Điều Kiện:\n§l§e↣§a Kênh Youtube của bạn phải đạt 100 Subscriber\n§l§e↣§a Kênh bạn phải làm 1 video quảng cáo về máy chủ\n§l§e↣§a Quyền Rank Youtube:\n§l§f-§a /fly:§6 Bay Trên Trời\n§l§f-§a /feed:§6 Hồi Thanh Thức Ăn\n§l§f-§a /heal:§6 Hồi Thanh Máu\n§l§f-§a /tp:§6 Dịch Chuyển Qua Người Khác");
        $form->addButton("§l§e•§c Thoát §e•", 0);
        $form->sendToPlayer($sender);
        }
        return true;
    }

    public function onDisable(){
        $this->getLogger()->info("n V1.0 n n  Disabled!");
    }
}