<?php

namespace FurryJaki1992\MuaTuNhanUI;

use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\{command\ConsoleCommandSender, Player, utils\TextFormat};

use jojoe77777\FormAPI;

class Main extends PluginBase implements Listener{

	public function onEnable(): void{
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->Info("§cMuaTuNhan §eCode By §aFurryJaki1992");
		$this->coinapi = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch($cmd->getName()) {
			case "buyosin":
			if(!($sender instanceof Player)){
                return true;
			}
		$this->Fu1($sender);
		}
		return true;
	}

	
	public function Fu1($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
        $result = $data;
        if ($result == null) {
             }
             switch ($result) {
                 case 1:
				 $coin = $this->coinapi->myCoin($sender);
				 $cost = 500;
				 if($coin >= $cost){
        $id = $sender->getInventory()->getItemInHand()->getId();
     if($id == 0){
					 $this->coinapi->reduceCoin($sender, $cost);
					 $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "osin " . $sender->getName());
					 $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Bạn đã mua thành công osin!");
     }else{
         $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Vui lòng tay bạn không được cầm vật phẩm nào! để nhận osin");
     }
					 return true;
            }else{
				$sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ coin để mua osin");
			}
			break;
                    case 2:
                        break;
            }
        });
		$form->setTitle("§l§e•§a Buy Osin §e•");
        $form->setContent("§l§c↣ §aBạn có đồng ý mua §bOsin§c giá §6500 §eCoin");
        $form->setButton1("§l§e•§a Đồng Ý §e•");
        $form->setButton2("§l§e•§c Không §e•");
        $form->sendToPlayer($sender);
	}
}