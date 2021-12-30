<?php 

namespace pet;

use pocketmine\Server;
use pocketmine\Player;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;

use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;

use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\block\Block;
use pocketmine\item\Item;

use pocketmine\utils\{Config, TextFormat};
use onebone\economyapi\EconomyAPI;
use onebone\coinapi\CoinAPI;
use jojoe77777\FormAPI;

Class Main extends PluginBase implements Listener{
	
	public $playerList = [];
	
	public function onEnable(){
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		//vui lòng tôn trọng tác giả không đổi author và không xóa 1 dòng này
		$this->getServer()->getLogger()->info("Plugin được làm bởi PIG");
		
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->coin = $this->getServer()->getPluginManager()->getPlugin("CoinAPI");
		$this->SCoinAPI = $this->getServer()->getPluginManager()->getPlugin("SCoinAPI"); 
		}
	
	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		if($cmd->getName() == "pets"){
			$this->PIGMenuForm($sender);
	} return true; }
	
	public function PIGMenuForm(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				return;
			}
			switch($result){
				case 0:
				$this->PIGQuanLi($sender);
				break;
				case 1:
				$this->PIGShopPetForm($sender);
				break;
				case 2:
				$this->PIGUpgradeForm($sender);
				break;
			}
		});
		$form->setTitle("§l§f•§9 Giao Diện Pets §f•");
		$form->setContent("§l§c↣ §aCoin của bạn: §e".$this->coin->myCoin($sender)."\n§l§c↣§a SCoin của bạn: §e".$this->SCoinAPI->mySCoin($sender));
		$form->addButton("§l§c•§9 Quản Lí Pet §c•",0,"textures/other/quanli");
		$form->addButton("§l§c•§9 Shop Pet §c•",0,"textures/other/shoppet");
		$form->addButton("§l§c•§9 Nâng Cấp Pet §c•",0,"textures/other/up");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function PIGQuanLi(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGMenuForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGTogglePet($sender);
				break;
				case 1:
				$this->PIGChangeNamePet($sender);
				break;
			}
		});
		$form->setTitle("§l§f•§9 Quản Lí Pet §f•");
		$form->addButton("§l§c•§9 Bật/Tắt Pet §c•",0,"textures/other/onoff");
		$form->addButton("§l§c•§9 Đổi Tên Pet §c•",0,"textures/other/namepet");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function PIGTogglePet($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            $result = $data;
            if($data === null){
                return $this->PIGMenuForm($sender);
           }
           $command = "togglepet $data[0]";
           $this->getServer()->getCommandMap()->dispatch($sender, $command);
        });
        $form->setTitle("§l§f•§9 Bật/Tắt Pet §f•");
        $form->addInput("§l§c•§e Lưu Ý:§a Không Được Ghi Có Khoảng Trống Và Kí Tự Đặc Biệt\n§l§c•§e Nhập Tên Pet:");
        $form->sendToPlayer($sender);
        return $form;
    }
    
    public function PIGChangeNamePet($sender){
        $formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $formapi->createCustomForm(function(Player $sender, $data){
            $result = $data;
            if($data === null){
                return $this->PIGMenuForm($sender);
            }
            $command = "changepetname $data[1] $data[2]";
            $this->getServer()->getCommandMap()->dispatch($sender, $command);
        });
        $form->setTitle("§l§f•§9 Đổi Tên Pet §f•");
        $form->addLabel("§l§c•§e Lưu Ý:§a Không Được Ghi Có Khoảng Trống Và Kí Tự Đặc Biệt");
        $form->addInput("§l§c•§e Nhập Tên Pet Cũ");
        $form->addInput("§l§c•§e Nhập Tên Pet Mới");
        $form->sendToPlayer($sender);
        return $form;
   }
	
	public function PIGShopPetForm(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGMenuForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGBuyPetForm($sender);
				break;
				case 1:
				$this->PIGPhuKienForm($sender);
				break;
			}
		});
		$form->setTitle("§l§f•§9 Shop Pet §f•");
		$form->addButton("§l§c•§9 Mua Pet §c•",0,"textures/other/pet");
		$form->addButton("§l§c•§9 Mua Phụ Kiện Pet §c•",0,"textures/other/tui");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function PIGBuyPetForm(Player $sender){
        $form = $this->getServer()->getPluginManager()->getPlugin("FormAPI")->createCustomForm(function (Player $sender, $data){
            if ($data == null){
                return $this->PIGShopPetForm($sender);
            }
            $re = $data[0];
            if($re == 0){
                     if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet wolf {$data[2]}  0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Wolf");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
            $re = $data[0];
            if($re == 1){
                 if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet pig {$data[2]} 0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Pig");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
            $re = $data[0];
            if($re == 2){
                 if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet vex {$data[2]}  0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Vex");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
            $re = $data[0];
            if($re == 3){
                 if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet chicken {$data[2]}  0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Chicken");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
            $re = $data[0];
            if($re == 4){
                $gem = $this->point->getGem($sender);
                    if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet slime {$data[2]}  0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Slime");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
            $re = $data[0];
            if($re == 5){
                if($this->SCoinAPI->mySCoin($sender) >= 50) {
    $this->SCoinAPI->reduceSCoin($sender, 50);
                         $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm ".$sender->getName()." blockpets.command.togglepet");
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "spawnpet polarbear {$data[2]}  0.5 baby ".$sender->getName());
                        $this->getServer()->broadcastMessage("§l§e• §aNgười chơi §b{$sender->getName()} §ađã mua §fPet Polar Bear");
                        $this->PIGYes($sender);
                        return true;
            }else{
                     $this->PIGNo($sender);
                }
            }
         });
            $isim = $sender->getName();
            $name = $sender->getName();
            $coin = $this->coin->myCoin($sender);
            $scoin = $this->SCoinAPI->mySCoin($sender);
            $form->setTitle("§l§f•§9 Mua Pet §f•");
            $form->addLabel("§l§c↣ §aSCoin của bạn: §e".$this->SCoinAPI->mySCoin($sender)."\n§l§c•§e Tất cả pet có giá 50 SCoin");
            $form->addDropdown("§l§c•§e Loại Pet:", [
                "§l§cWolf",
                "§l§cPig",
                "§l§cVex",
                "§l§cChicken",
                "§l§cSlime",
                "§l§cPolar Bear"
                            ]);
            $form->addInput("§l§c•§e Lưu Ý:§a Không Được Ghi Có Khoảng Trống Và Kí Tự Đặc Biệt\n§l§c•§e Nhập Tên Pet:");
            $form->sendToPlayer($sender);
            return $form;
    }
    
    public function PIGYes(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGShopPetForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGShopPetForm($sender);
				break;
			}
		});
		$form->setTitle("");
		$form->setContent("§l§e•§a Bạn đã mua pet thành công!");
		$form->addButton("§l§c•§9 Trở Lại §c•");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function PIGNo(Player $sender){
		$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGShopPetForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGShopPetForm($sender);
				break;
			}
		});
		$form->setTitle("");
		$form->setContent("§l§e•§c Bạn không đủ SCoin để mua pet!!");
		$form->addButton("§l§c•§9 Trở Lại §c•");
		$form->sendToPlayer($sender);
		return $form;
	}
	
	public function PIGPhuKienForm(Player $sender){
	$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGShopPetForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGYenCuoiForm($sender);
				break;
				case 1:
				$command = "§l§c§f§a§a§a mine";
            $this->getServer()->getCommandMap()->dispatch($sender, $command);
				break;
			}
		});
          $coin = $this->coin->myCoin($sender);
          $money = $this->eco->myMoney($sender);
          $form->setTitle("§l§f•§9 Mua Phụ Kiện Pet §f•");
          $form->setContent("§l§c•§a Tiền của bạn: §e" . $money."\n§l§c•§a Coin của bạn:§e ".$coin);
          $form->addButton("§l§c•§9 Yên Cưỡi §c•",0,"textures/other/yencuoi");
          $form->addButton("§l§c•§9 Thức Ăn §c•",0,"textures/other/food");
          $form->sendToPlayer($sender);
        return $form;
        }
    
    public function PIGYenCuoiForm($sender){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createModalForm(function (Player $sender, $data){
            $result = $data;
            if ($result == null) {
            }
            switch ($result) {
            	case 1:
            $coin = $this->coin->myCoin($sender);
            $cost = 10;
            if($coin >= $cost){
            	$this->coin->reduceCoin($sender, $cost);	
                $this->getServer()->dispatchCommand(new ConsoleCommandSender(), "give " .  $sender->getName() . " 329 1");
                $this->FormYes($sender);       
              return true;
            }else{
            	$this->FormFail($sender);
            }
                break;
                case 2:
                $this->PIGPhuKienForm($sender);
                break;
            }
        });
        $form->setTitle("§l§f•§9 Yên Cưỡi §f•");
        $form->setContent("§l§e•§a Yên Cưỡi §d↣§a Giá:§e 10 Coin §bBạn có đồng ý mua không?"); 
        $form->setButton1("§l§e•§a Đồng Ý §e•", 1);
        $form->setButton2("§l§e•§c Trở Lại §e•", 2);
        $form->sendToPlayer($sender);
        }
    
    public function FormYes($sender){
    	$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGPhuKienForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGPhuKienForm($sender);
				break;
            }
        });
          $form->setTitle("");
          $form->setContent("§l§e•§a Bạn đã mua thành công yên cưỡi!");
          $form->addButton("§l§c•§9 Trở Lại §c•");
          $form->sendToPlayer($sender);
        }
    
    public function FormFail($sender){
    	$formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
		$form = $formapi->createSimpleForm(function (Player $sender, ?int $data = null){
			$result = $data;
			if($result === null){
				$this->PIGPhuKienForm($sender);
				return;
			}
			switch($result){
				case 0:
				$this->PIGPhuKienForm($sender);
				break;
            }
        });
          $form->setTitle("");
          $form->setContent("§l§e•§c Bạn không đủ tiền để mua yên cưỡi!");
          $form->addButton("§l§c•§9 Trở Lại §c•");
          $form->sendToPlayer($sender);
        }
 }