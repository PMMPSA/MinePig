<?php
#Code Bởi PIG
namespace BuyKey;

use pocketmine\plugin\PluginBase;
use pocketmine\command\{Command,CommandSender, CommandExecutor, ConsoleCommandSender};
use pocketmine\event\Listener;
use jojoe77777\FormAPI;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\item\Item;
use pocketmine\inventory\Inventory;

class Main extends PluginBase implements Listener {
    
    public function onEnable(){
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args):bool
    {
        switch($cmd->getName()){
        case "buykey":
        if(!($sender instanceof Player)){
        $sender->sendMessage("§cDont use here");
		return true;
		}
		$this->sendMainForm($sender);
		return true;
		}
	}
	public function sendMainForm($sender){
		$api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
            	    case 0:
					break;
                    case 1:
                    $this->MyThic($sender);
                    break;
                    case 2:
                    $this->Common($sender);
                    break;
                    case 3:
                    $this->Lucky($sender);
					
			}       
        });
     $money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender);
      $form->setTitle("§l§e•§a BUY Key §e•");
      $form->setContent("§l§e•§a Xin chào: §c".$sender->getName()."\n§l§e•§a Your Money:§b ".$money."");
	  $form->addButton("§l§f•§c Thoát§f •",0,"textures/other/axit");
      $form->addButton("§l§f•§9 Block§c ↣§6 50.000 §eXu§f •",0,"textures/ui/accessibility_glyph_color");
      $form->addButton("§l§f•§9 VTL§c ↣§6 100.000 §eXu§f •",0,"textures/ui/accessibility_glyph_color");
      $form->addButton("§l§f•§9 Lucky§c ↣§6 150.000 §eXu§f •",0,"textures/ui/accessibility_glyph_color");
      $form->sendToPlayer($sender);
	}
    public function Mythic($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
			case 0:
			break;
            case 1:
            $p = $sender->getName();
            $money = $this->money->myMoney($sender);
            if($money >= 50000){
            $this->money->reduceMoney($sender, 50000);
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "key Block 1 ".$sender->getName());
            $this->getServer()->broadcastMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Người chơi $p đã mua §l§9Key Block");
            return true;
            }else{
            $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ §e$§c để mua Key Block");
            }
            }
        });
        $form->setTitle("§l§e•§a Block §e•");
		$form->setContent("§l§e↣§c Key:§l§a Block§l§e Giá:§c 50.000§dXu§b Bạn có đồng ý mua không?");
        $form->addButton("§l§e•§c Không §e•",0,"textures/ui/cancel");
        $form->addButton("§l§e•§a Đồng Ý §e•",0,"textures/ui/confirm");
        $form->sendToPlayer($sender);
    }
	public function Common($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
			case 0:
			break;
            case 1:
            $p = $sender->getName();
            $money = $this->money->myMoney($sender);
            if($money >= 100000){
            $this->money->reduceMoney($sender, 100000);
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "key VTL 1 ".$sender->getName());
            $this->getServer()->broadcastMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Người chơi $p đã mua §l§9Key VTL");
            return true;
            }else{
            $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ §e$§c để mua Key VTL");
            }
            }
        });
        $form->setTitle("§l§e•§a VTL §e•");
		$form->setContent("§l§e↣§c Key:§l§a VTL§l§e Giá:§c 100.000§dXu§b Bạn có đồng ý mua không?");
        $form->addButton("§l§e•§c Không §e•",0,"textures/ui/cancel");
        $form->addButton("§l§e•§a Đồng Ý §e•",0,"textures/ui/confirm");
        $form->sendToPlayer($sender);
	}
	 public function Lucky($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
			case 0:
			break;
            case 1:
            $p = $sender->getName();
            $money = $this->money->myMoney($sender);
            if($money >= 150000){
            $this->money->reduceMoney($sender, 150000);
			$this->getServer()->dispatchCommand(new ConsoleCommandSender(), "addkey ".$sender->getName()." 1");
            $this->getServer()->broadcastMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Người chơi $p đã mua §l§9Key Lucky");
            return true;
            }else{
            $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không đủ §e$§c để mua Key Lucky");
            }
            }
        });
        $form->setTitle("§l§e•§a Lucky §e•");
		$form->setContent("§l§e↣§c Key:§l§a Lucky§l§e Giá:§c 150.000§dXu§b Bạn có đồng ý mua không?");
        $form->addButton("§l§e•§c Không §e•",0,"textures/ui/cancel");
        $form->addButton("§l§e•§a Đồng Ý §e•",0,"textures/ui/confirm");
        $form->sendToPlayer($sender);
    }
	public function nomoneyUI($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
			case 0:
			$this->sendMainForm($sender);
			break;
			case 1:
			break;
			}
		});
		$form->setTitle("§l§cError");
		$form->setContent("§l§cBạn không đủ tiền để mua key!");
		$form->addButton("§l§e•§a Quay Lại §e•", 0);
		$form->addButton("§l§e•§c Thoát §e•", 1);
	}
}
			
		