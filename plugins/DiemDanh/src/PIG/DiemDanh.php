<?php
namespace PIG;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class DiemDanh extends PluginBase implements Listener
{


   public function onEnable()
   {
        if(!is_dir($this->getDataFolder()))	
	{
        mkdir($this->getDataFolder());
        }
        $this->user = new Config($this->getDataFolder() ."user.yml", Config::YAML, []);//t?o config.yml
    }


    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
    {
    	$this->money = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($cmd->getName() == "diemdanh")
		{
       		$ten = $sender->getName();
        	$player = $sender->getServer()->getPlayer($ten);	
        	$date = date('Y-m-d');
        	if($this->user->exists($ten)){
        		if($this->user->get($ten) !== $date)
			{
        			$this->money->addMoney($sender->getName(), 5000);
                    $sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Bạn đã nhận quà thành công..!");         
        			$this->user->set($ten,$date);
        			$this->user->save();
        		}
        		else
				{
        			$sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Hôm nay bạn đã nhận quà rồi..!");
					
        	   	}
        	}
			else
			{
				$this->money->addMoney($sender->getName(), 20000);
				$sender->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§b Xin chào§e ".$sender->getName()."§b bạn đã nhận quà thành công..!");
				$rand = mt_rand(1, 20);
				switch($rand){
					case "1":
					$sender->getInventory()->addItem(Item::get(257, 0, 10));
                    break;
                    case "5":
                    $sender->getInventory()->addItem(Item::get(322, 0, 5));
                    break;
                    case "10":
                    $sender->getInventory()->addItem(Item::get(57, 0, 2));
                    break;
                    case "15":
                    $sender->getInventory()->addItem(Item::get(17, 0, 32));
                    $sender->getInventory()->addItem(Item::get(4, 0, 64));
                    break;
                    case "20":
                    $this->money->addMoney($sender->getName(), 20000);
                    break;
                    }
				$this->user->set($ten,$date);
				$this->user->save(); 
				return true;
     		} 
		}
    }
}
}