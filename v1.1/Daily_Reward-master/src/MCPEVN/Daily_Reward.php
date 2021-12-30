<?php
namespace MCPEVN;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;
use pocketmine\event\Listener;
use pocketmine\item\Item;

class Daily_Reward extends PluginBase implements Listener
{


   public function onEnable()
   {
        if(!is_dir($this->getDataFolder()))	
	{
        mkdir($this->getDataFolder());
        }
        $this->user = new Config($this->getDataFolder() ."user.yml", Config::YAML, []);//t?o config.yml
    }


    public function onCommand(CommandSender $sender, Command $cmd, $label, array $args)
    {
    	$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if($cmd->getName()=="nhanqua" or $cmd->getName() == "diemdanh")
		{
       		$ten = $sender->getName();
        	$player = $sender->getServer()->getPlayer($ten);	
        	$date = date('Y-m-d');
        	if($this->user->exists($ten)){
        		if($this->user->get($ten) !== $date)
			{
        			$this->eco->addMoney($sender->getName(), 5000);
              $sender->sendMessage("§a✔§b Nhận quà trực tuyến thành công!");         
        			$this->user->set($ten,$date);
        			$this->user->save();
					return;
        		}
        		else
				{
        			$sender->sendMessage('§c✖§6 Hôm nay bạn nhận quà rồi!');
					return;
        	   	}
        	}
			else
			{
				$this->eco->addMoney($sender->getName(), 20000);
				$sender->sendMessage("§e✔§6 Chào mừng bạn§b ".$sender->getName().".§6 Bạn đã nhận quà trực tuyến đầu tiên!");
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
                    $this->eco->addMoney($sender->getName(), 20000);
                    break;
                    }
				$this->user->set($ten,$date);
				$this->user->save();
				return;
     		}
		}
		return true;
    }
}
