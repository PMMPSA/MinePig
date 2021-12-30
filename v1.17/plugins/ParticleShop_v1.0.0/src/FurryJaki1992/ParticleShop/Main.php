<?php

namespace FurryJaki1992\ParticleShop;

use pocketmine\plugin\{PluginBase,Plugin};
use pocketmine\event\Listener;
use pocketmine\{Player,Server};
use pocketmine\utils\Config;
use pocketmine\scheduler\Task;
use pocketmine\command\{PluginCommand,CommandSender};
use pocketmine\form\Form;
use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\level\particle\{
	
	AngryVillagerParticle,
	BubbleParticle,
	CriticalParticle,
	DustParticle,
	EnchantmentTableParticle,
	FlameParticle,
	HappyVillagerParticle,
	HeartParticle,
	InkParticle,
	PortalParticle,
	RedstoneParticle,
	SmokeParticle,
	SporeParticle
	
};


class main extends PluginBase implements Listener{
	
	public static $main;
	
	public function onEnable(){
		
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		
		$this->getServer()->getCommandMap()->register('ParticleShopCommand',new ShopCommand($this));
		
		$this->Setup = new Config($this->getDataFolder()."Setup.yaml",Config::YAML,[

		'SendInterval' => 2,
		'Price' => 500000

		]);
		$this->Config = new Config($this->getDataFolder()."DataBase.yaml",Config::YAML);
		$this->VectorX = new Config($this->getDataFolder()."X.yaml",Config::YAML);
		$this->VectorZ = new Config($this->getDataFolder()."Z.yaml",Config::YAML);
		
		$interval = $this->Setup->get('SendInterval');
		$vt = $interval+10;
		
		$this->getScheduler()->scheduleRepeatingTask(new addParticle($this),$interval);
		$this->getScheduler()->scheduleRepeatingTask(new Vector($this),$vt);
		
		$this->api = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		
		self::$main = $this;
		
		if($this->api == null){
			
			$this->getLogger()->info("Plugin EconomyAPI không được tìm thấy");
			$this->getServer()->shutdown();
			
		}

	}
	
	
	public function registerParticle($name,$particle){
		
		$this->Config->set($name,$particle);
		$this->Config->save();
		
	}
	
	
	public function removeParticle($name){
		
		if($this->Config->__isset($name)){
			
			$this->Config->__unset($name);
			$this->Config->save();
			
		}
		
	}
	
	
	public function pay($player,$amount){
		
		$money = $this->api->myMoney($player);
		
		if($money >= $amount){
	
			$this->api->reduceMoney($player,$amount);
			$payed = 'true';
			return $payed;
		
		}else{
			
			$player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Bạn không có đủ tiền");
			$payed = 'false';
			return $payed;
		}

	}
	
}



class ShopCommand extends PluginCommand{
	
    public function __construct(Plugin $plugin){
    	
        parent::__construct('buyeffect', $plugin);
        $this->setAliases(['buyeffect']);
        $this->setDescription('BUY Effect');
        $this->setUsage('/buyeffect');
        
    }
    


    public function execute(CommandSender $sender, string $commandLabel, array $args){
    	
        if($sender instanceof Player){
            $sender->sendForm(new MainForm());
           
        }
        
    }
    
}



class addParticle extends Task{
	
	public function __construct($plugin){
		
		$this->plugin = $plugin;
		
	}
	

	public function onRun(int $tick){
		
		foreach($this->plugin->getServer()->getInstance()->getOnlinePlayers() as $player){
			
			$main = main::$main;
			$name = $player->getName();
			
			
			if($main->Config->__isset($name)){
				
				$particle = $main->Config->get($name);
				
				$x1 = $main->VectorX->get($name);
				$z1 = $main->VectorZ->get($name);
				
				$x = $player->getX();
				$y = $player->getY();
				$z = $player->getZ();
				
				if($x !== $x1 && $z !== $z1){
			
					switch($particle){
					
						case "AngryVillagerParticle";
						
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new AngryVillagerParticle($pos));
						
						break;
					
						case "BubbleParticle";
						
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new BubbleParticle($pos));
						
						break;
					
						case "CriticalParticle";
						
							$y = $y+0.35;
						
							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new CriticalParticle($pos));
						
						break;
					
						case "RainbowParticle";
							
							$y = $y+0.35;
	
							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new DustParticle($pos,mt_rand(),mt_rand(),mt_rand()));
						
						break;
					
						case "EnchantmentTableParticle";
							
							$y = $y+0.35;
	
							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new EnchantmentTableParticle($pos));
						
						break;
							
						case "FlameParticle";
						
							$y = $y+0.35;
							
							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new FlameParticle($pos));
							
						break;
					
						case "HappyVillagerParticle";

							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new HappyVillagerParticle($pos));
						
						break;
					
						case "HeartParticle";
						
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new HeartParticle($pos));
							
						break;
					
						case "InkParticle";
				
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new InkParticle($pos));
						
						break;
					
						case "PortalParticle";
					
							$y = $y+0.35;
	
							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new PortalParticle($pos));
						
						break;
					
						case "RedstoneParticle";
						
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new RedstoneParticle($pos));
						
						break;
					
						case "SmokeParticle";
						
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new SmokeParticle($pos));
						
						break;
					
						case "SporeParticle";
	
							$y = $y+0.35;

							$pos = new Vector3($x,$y,$z);
							$player->getLevel()->addParticle(new SporeParticle($pos));
						
						break;
					
					}
					
				}
				
			}
			
		}
		
	}
	
}

class Vector extends Task{
	
	public function __construct($plugin){
		
		$this->plugin = $plugin;
		
	}
	

	public function onRun(int $tick){
	
		foreach($this->plugin->getServer()->getInstance()->getOnlinePlayers() as $player){
	

			$x1 = $player->getX();
			$z1 = $player->getZ();
			$name = $player->getName();

			main::$main->VectorX->set($name,$x1);
			main::$main->VectorX->save();
	
			main::$main->VectorZ->set($name,$z1);
			main::$main->VectorZ->save();
			
		}
	}

}

class MainForm implements Form{
	
	public function handleResponse(Player $player, $data): void{
		
        if($data === null){
        	
            return;
            
        }


        $buttons = ['1', '2', '3'];
        $data = $buttons[$data];
        
        
        switch($data){
        	
        	case '1';
        	
        		$player->sendForm(new setParticle());
        		
        	break;
        	
        	
        	case '2';
        	
        		$player->sendForm(new removeParticle());
        		
        	break;
        	
    	}
        
    }


    public function jsonSerialize(){
    	
        return [
        
            'type' => 'form',
            'title' => '§l§e•§a BUY Effect',
            'content' => '§l§c↣§e Hãy Chọn 1 Nút Để Tiếp Tục',
            'buttons' => [
            
                [
                    'text' => '§l§e•§c BUY Effect§e •',
                    'image' => [
                        'type' => '',
                        'data' => ''
                    ]
                ],
                
                [
                    'text' => '§l§e•§c Remove Effect§e •',
                    'image' => [
                        'type' => '',
                        'data' => ''
                    ]
                ],
                
                [
                    'text' => '§l§e•§c Thoát §e•',
                    'image' => [
                    	'type' => '',
                    	'data' => ''
                    
                    ]
                    
                ]
                
            ]
            
        ];
        
    }
    
	
}



class setParticle implements Form{
	
    public function handleResponse(Player $player, $data): void{
    	
        if ($data === null) {
            return;
        }

        $select = ["AngryVillagerParticle","BubbleParticle","CriticalParticle","RainbowParticle","EnchantmentTableParticle","FlameParticle","HappyVillagerParticle","HeartParticle","InkParticle","PortalParticle","RedstoneParticle","SmokeParticle","SporeParticle"];
        $bool = $data[1] ? "ON" : "OFF";
       
       
       if($bool == "OFF"){
	   	
	   	$name = $player->getName();
	   	$particle = $select[$data[0]];
	   	
	   	$main = main::$main;
	   	$amount = $main->Setup->get('Price');
	   	$pay = $main->pay($player,$amount);
	   	
	   	
	   	if($pay == 'true'){
   	
	   		$main->registerParticle($name,$particle);
	   		$player->sendMessage("§l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§a Đã mua thành công §e{$particle}§e!");
	   	
           }
           
        }else{
        	
        	$player->sendForm(new MainForm());
        }
        
    }
    

    public function jsonSerialize(){
    	
    	$main = main::$main;
	    $amount = $main->Setup->get('Price');
	    
        return [
            'type' => 'custom_form',
            'title' => "ParticleShop",
            'content' => [
                [
                    'type' => 'dropdown',
                    'text' => "§l§c↣§e Vui lòng chọn các hiệu ứng bạn muốn mua\n§l§6§e Giá của các hiệu ứng là§b §l{$amount}§a Xu§r",
                    'options' => [
                        "AngryVillagerParticle",
                        "BubbleParticle",
						 "CriticalParticle",
						 "RainbowParticle",
					 	"EnchantmentTableParticle",
						 "FlameParticle",
						 "HappyVillagerParticle",
						 "HeartParticle",
						 "InkParticle",
						 "PortalParticle",
						 "RedstoneParticle",
						 "SmokeParticle",
						 "SporeParticle"
                    ],
                    
                    'default' => 0
                ],
                
                [
                'type' => 'toggle',
                    'text' => '§l§eĐể hủy hãy bật nút§7,§e Tắt nút để tiếp tục mua.',
                    'default' => false
                ]
 
            ]
            
        ];
        
    }
	
}




class removeParticle implements Form{
	
	
    public function handleResponse(Player $player, $data): void{
    	
        

        $bool = $data ?'はい':'いいえ';
        
        if($bool == 'はい'){
        	
        	$name = $player->getName();
        	
        	$main = main::$main;
        	$main->removeParticle($name);
        	
        	$player->sendMessage(" §l§c【 §fvιcтoʀʏ §eoғ §6ʟᴇԍᴇɴᴅ §c】§c Hiệu Ứng Đã Xóa Thành Công");
        	
        }else{
        	
        	$player->sendForm(new MainForm());
        	
        }
        
    }

    public function jsonSerialize(){


        return [
            'type' => 'modal',
            'title' => '§l§e•§a Confirm §e•',
            'content' => "§l§c↣§e Bạn có chắc muốn xoá hiệu ứng đang sử dụng ?\n§l§c↣§6 Hiệu ứng bị xoá cần được mua lại để sử dụng tiếp.",
            'button1' => '§l§e•§a Xác Nhận §e•',
            'button2' => '§l§e•§c Hủy §e•'
        ];
    }
}