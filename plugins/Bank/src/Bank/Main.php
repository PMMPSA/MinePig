<?php

namespace Bank;

/* Bank of Terrial version! */
/* @Coder: K27, Kaido Joestar */

use pocketmine\Player;
use pocketmine\Server;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\Config;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\Task;

use Bank\InterestRate;

use jojoe77777\FormAPI\FormAPI;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;

class Main extends PluginBase implements Listener
{
    
    public $value;
    
    public function onLoad()
    {
       if(!is_dir($this->getDataFolder())) {
          mkdir($this->getDataFolder());
       }

      if(!is_dir($this->getDataFolder()."History/")) {
         mkdir($this->getDataFolder()."History/");
      }
    }
    
    public function onEnable()
    {
          
      $this->EconomyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
      
      $this->bank = new Config($this->getDataFolder()."bank.yml", Config::YAML);
      
      
      $this->getServer()->getPluginManager()->registerEvents($this, $this);
      
      $this->getServer()->getPluginManager()->registerEvents(new InterestRate($this), $this);
       
      $this->getScheduler()->scheduleRepeatingTask(new InterestRate($this), 20);
      
    }
    
    public function onATM(Player $player) : bool
    {
        return $this->bank->exists($player->getName());
    }
    
    public function getArray(Player $player, $array)
    { 
    $msg = false;
    
        switch($array) {
           case "money":
              $msg = $this->bank->get($player->getName())["money"];
              break;
           case "InterestRate":
              $msg = $this->bank->get($player->getName())["InterestRate"];
              break;
           case "ID":
             $msg = $this->bank->get($player->getName())["ID"];
              break;
           case "PIN";
             $msg = $this->bank->get($player->getName())["PIN"];            
          }
          
        return $msg;
        }
        
    public function addMoney(Player $player, Int $int) 
    {
       $this->bank->set($player->getName(), ["money" => ($this->getArray($player, "money") + $int), "InterestRate" => $this->getArray($player, "InterestRate"), "ID" => $this->getArray($player, "ID"), "PIN" => $this->getArray($player, "PIN")]);
       $this->bank->save();
    }
    
    public function takeMoney(Player $player, Int $int) 
    {
       $this->bank->set($player->getName(), ["money" => ($this->getArray($player, "money") - $int), "InterestRate" => $this->getArray($player, "InterestRate"), "ID" => $this->getArray($player, "ID"), "PIN" => $this->getArray($player, "PIN")]);
       $this->bank->save();
    }
    
    public function addInt(Player $player, Int $int) 
    {
       $this->bank->set($player->getName(), ["money" => $this->getArray($player, "money"), "InterestRate" => ($this->getArray($player, "InterestRate") + $int), "ID" => $this->getArray($player, "ID"), "PIN" => $this->getArray($player, "PIN")]);
       $this->bank->save();
    }
    
    public function takeInt(Player $player, Int $int) 
    {
       $this->bank->set($player->getName(), ["money" => $this->getArray($player, "money"), "InterestRate" => ($this->getArray($player, "InterestRate") - $int), "ID" => $this->getArray($player, "ID"), "PIN" => $this->getArray($player, "PIN")]);
       $this->bank->save();
    }
    
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool
    {
       
       if($command->getName() == "nganhang") {

         if(!$sender instanceof Player) {
              $this->getLogger()->info("
██╗░░██╗██████╗░███████╗
██║░██╔╝╚════██╗╚════██║
█████═╝░░░███╔═╝░░░░██╔╝
██╔═██╗░██╔══╝░░░░░██╔╝░
██║░╚██╗███████╗░░██╔╝░░
╚═╝░░╚═╝╚══════╝░░╚═╝░░░\n- NganHang version Fresh 1.17\n- Youtube: Kaido Joestar");
             return false;
         }
          
          if(!isset($args[0])) {
             $sender->sendMessage("[§c*§f]§8 Sử dụng lệnh§a /nganhang open§8 để mở giao diện Ngân Hàng.");
             return true;
          }
          
          if($args[0] == "dangki") {
             
             if(!is_numeric($args[1])) {
                $sender->sendMessage("§c§lVui lòng nhập mã bảo vệ!");        
                return true;
             }
           
             if($args[1] < 1000 or $args[1] > 999999) {
                $sender->sendMessage("§l§cChỉ có thể đặt mã bảo vệ từ 4 - 6 số!");
                return true;
             }
              
           if($this->onATM($sender)) {
              $sender->sendMessage("§c§lBạn đã có tài khoản ngân hàng từ trước!");
              return true;
           }
           
         /** Player create Account */
    
         $id = $this->createPosition();
    
         $this->bank->set($sender->getName(), ["money" => 0, "InterestRate" => 0, "ID" => $id, "PIN" => $args[1]]);
         $this->bank->save();
         
         $this->addInfo($sender, $sender->getName().", Khách hàng thanh toán, sử dụng dịch vụ ngân hàng số.");
         
         /** 
         * PIN: $args[1]
         * Status: Active
         **/
         
         $show = new CustomForm(function (Player $player, array $data = null)
         {
            if($data == null) {
               return true;
            }
           
         });
         
         $show->setTitle("§8Status Account");
         $show->addLabel("[§a!§f]§8 Ngân Hàng đã được tạo thành công!\n");
         $show->addLabel("§l§7TRẠNG THÁI HIỆN TẠI\n");
         $show->addLabel("§b⇀§8 Mã bảo vệ:§l§a ".$args[1]."\n");
         $show->addLabel("§b⇀§8 ID Thẻ xác nhận:§l§e ".$this->getArray($sender, "ID")."\n");
         $show->addLabel("§b⇀§8 Trạng thái:§a Kích hoạt.");
         $show->sendToPlayer($sender); 

       
         
      } # Kich hoat

        if($args[0] == "open") {
           
           if(!$this->onATM($sender)){
              $sender->sendMessage("[§c*§f]§8 Bạn chưa đăng kí sử dụng ngân hàng. Sử dụng lệnh §b/nganhang dangki <PIN CODE>§8 để đăng kí sử dụng.");
            return true;
           }   
           
           if(isset($this->logan[$sender->getName()])) {
              $this->onForm($sender);
              return true;
           }               
              $this->onPin($sender);            
           } # OPEN
           
        
         } # Command

       return false;
       }
       
     /** Ask PIN when open */ 
       
     public function onPin(Player $player) {
        $form = new CustomForm(function (Player $player, $data = null) 
        {
          
           if($data === null){
              return;
           }
          
           if($this->getArray($player, "PIN") == $data[1]) {
              $this->logan[$player->getName()] = true;            
              $this->onForm($player);
           } else {
              $player->sendMessage("§l§c".$data[1]."? Mã PIN Chưa chính xác.");
          }   
 
      });
      
      $form->setTitle("§8Login Account");
      $form->addLabel("§b⇀§6 Nhập chính xác mã PIN đã tạo sẵn.");
      $form->addInput("§b⇀§e PIN CODE:");
      $form->sendToPlayer($player);
      
     return $form;
     }
     
       
     public function onForm(Player $player) 
     {
        $form = new SimpleForm(function (Player $player, int $data = null)
        {
  #    var_dump($data);
       if($data === null ) return;
    
          switch($data) {
             case 0:
                if($this->bank->get($player->getName())["money"] < 1) {
                   $player->sendMessage("[§c!§f]§8 Không thể! Nạp thêm mới có thể mở giao diện.");
                   return;  
                }
              
               $this->onTakeMoney($player);
           break;    
           case 1:
           
              if($this->getArray($player, "InterestRate") < 1){
                 $player->sendMessage("[§c!§f]§8 Bạn chưa có lợi nhuận lãi suất từ tài khoản ngân hàng.");
                 return;  
              } 
              $this->onTakeInt($player);  
           break;
           case 2:
              $this->onSendMoney($player);
           break;
           case 3:
              $this->sendMoneyPlayer($player);
           break;
           case 4:
               $this->showChart($player);
           break;
           case 5:
              $this->onChangePin($player);
           break; 
           case 6:
              $this->showHistory($player);
           break;
        }
    
      });
      $form->setTitle("§8Status");
      
      /**
      * @var Contents Account
      *
      * InterestRate: $this->getArray($player, "InterestRate")
      * InterestRate/s: ($this->getArray($player, "money")/1000000)
      * ID: $this->getArray($player, "ID");
      * Status: Lock without exists config
      **/     
           
      $form->setContent("§b⇀§8 Khách Hàng:§a ".$player->getName()."\n§b⇀§8 ID:§9 ".$this->getArray($player, "ID")."\n§b⇀§8 Trạng thái:§b Kích hoạt.\n\n§l§7TRẠNG THÁI HIỆN TẠI\n\n§r§b⇀§8 Tổng giá trị:§l§e ".$this->getArray($player, "money")."\n§r§b⇀§8 Lãi suất hiện có:§l§6 ".$this->getArray($player, "InterestRate")."\n§r§b⇀§8 Lãi suất mỗi phút:§l§c ".round($this->getArray($player, "money")/1000000)."/m");
      
      $form->addButton("§l§8RÚT TIỀN", 0, "textures/ui/icon_blackfriday"); // 0 
      $form->addButton("§l§8RÚT LÃI", 0, "textures/ui/purtle"); // 1
      $form->addButton("§l§8NẠP TIỀN", 0, "textures/ui/promo_gift_big"); // 2
      $form->addButton("§l§8CHUYỂN KHOẢNG", 0, "textures/ui/promo_gift_small_green"); // 3
      $form->addButton("§l§bBẢNG XẾP HẠNG", 0, "textures/ui/poison_effect"); // 4
      $form->addButton("§l§cĐỔI MÃ PIN", 0, "textures/ui/invite_base"); // 5
      $form->addButton("§l§7LỊCH SỬ GIAO DỊCH", 0, "textures/items/banner_pattern"); // 6
      $form->sendToPlayer($player);
     return $form;
    }
    
  public function onTakeMoney(Player $player)
  {
    $form = new CustomForm(function (Player $player, array $data = null)
    {
        
       if($data == null) {
          return;       
       } 
       
       $this->addInfo($player, $player->getName().", Sử dụng tài khoản rút ".$data[1]." giá trị tiền mặt.");
       $this->takeMoney($player, $data[1]);
       $this->EconomyAPI->addMoney($player->getName(), $data[1]);
       $player->sendPopup("§l§b⇀§e Cộng§6 ".$data[1]."d§e vào tài khoản chính!");
    });
    
    $form->setTitle("§8Withdrawal");
    $form->addLabel("§b⇀§8 Tổng giá trị:§a ".$this->getArray($player, "money"));
    $form->addSlider("§b⇀§8 Chọn giá trị muốn rút", 0, $this->getArray($player, "money"));
    $form->sendToPlayer($player);
    
    return $form;
    } 

   public function onTakeInt(Player $player) 
   {
     
     $form = new CustomForm(function (Player $player, array $data = null)
     {
        if($data == null){
           return;
        }
        
     $this->addInfo($player, $player->getName().", Sử dụng tài khoản rút ".$data[1]." từ tổng lợi nhuận nhận được từ tài khoản.");
        
     $this->EconomyAPI->addMoney($player->getName(), $data[1]);
     $this->takeInt($player, $data[1]);
     $player->sendPopup("§l§b⇀§e Cộng§6 ".$data[1]."d§e vào tài khoản chính!");      

     });
     $form->setTitle("§8Withdraw profit");
     $form->addLabel("§b⇀§8 Lãi suất hiện có:§e ".$this->getArray($player, "InterestRate"));
     $form->addSlider("§b⇀§8 Chọn số lãi suất", 0, $this->getArray($player, "InterestRate"));
     $form->sendToPlayer($player);
     
    return $form;
    } 
    
   public function onSendMoney(Player $player)
   {
     $form = new CustomForm(function (Player $player, array $data = null)
     {
         
        if($data == null){
           return;
        }
      
        if(!is_numeric($data[1])) {
           $player->sendMessage("[§c!§f]§8 Nhập số tiền muốn gửi vào tài khoản.");         
           return;  
        }
      if($this->EconomyAPI->myMoney($player->getName()) > ($data[1] - 1)){
         $this->addInfo($player, $player->getName().", Sử dụng tài khoản, nạp giá trị và tổng giá trị tăng thêm ".$data[1]);
         $this->EconomyAPI->reduceMoney($player->getName(), $data[1]);
         $this->addMoney($player, $data[1]);
         $player->addTitle("§l§c-".$data[1], "§l§eCám ơn bạn đã tin dùng Ngân Hàng.");
         $player->sendPopup("§l§b⇀§e Cộng§6 ".$data[1]."d§e vào tài khoản ngân hàng.");
      }else {
         $player->sendMessage("[§c!§f]§8 Khoảng giá trị gửi đi để không được chấp nhận.");    
      }
    });
    
     $form->setTitle("§bBank depositors");   
     $form->addLabel("[§6*§f]§8 Bạn có thể nhận lợi nhuận, tổng số giá trị càng lớn thì tổng lãi suất nhận được càng cao.");
     $form->addInput("§b⇀§8 Nhập giá trị muốn gửi", "1000");
     $form->sendToPlayer($player);
    return $form;
   }
   
   public function onChangePin($player)
   {
     $form = new CustomForm(function (Player $player, array $data = null){
       
       if($data === null){
          return true;
          }
 
       
      
          
       if($data[1] < 1000 || $data[1] > 999999) {
              $sender->sendMessage("[§cNgân Hàng§f] §8Chỉ có thể đặt mã bảo vệ từ 4 - 6 số!");
              return;            
              }
          
       if($this->getArray($player, "PIN") !== $data[0]) {
          $player->sendMessage("[§cNgân Hàng§f] §8Sai mã PIN! Nhập chính xác mã PIN cũ mới có thể đổi.");
          return;
          }

      if($data[1] !== $data[2]){
          $player->sendMessage("§l[§c*§f]§c Mã PIN Nhập lại sai. Xin thử lại!");
          return;
          }
          $this->addInfo($player, "Thay đổi mã PIN của tài khoản!");
          
          unset($this->logan[$player->getName()]);
           
          $this->bank->set($player->getName(), ["money" => $this->getArray($player, "money"), "InterestRate" => $this->getArray($player, "InterestRate"), "ID" => $this->getArray($player, "ID"), "PIN" => $data[1]]);
          $this->bank->save();
          $player->sendMessage("[§6*§f] §7Mã bảo vệ đã được đổi thành§b ".$data[1]);    
       });
     $form->setTitle("§8Change PIN");
     $form->addInput("§b⇀§8 NHẬP MÃ PIN CŨ");
     $form->addInput("§b⇀§8 NHẬP MÃ PIN MỚI");
     $form->addInput("§b⇀§8 NHẬP LẠI MÃ PIN MỚI");
     $form->sendToPlayer($player);
    return $form;       
   }
   
   public function createPosition() : string
   {
        $characters = '012345abcdeABCDEZI'; // OK :))
        $charactersLength = strlen($characters);
        $length = 3;
        $randomString = 'BRT';
    for ($i = 0; $i < $length; $i++) {
         $randomString .= $characters[rand(0, $charactersLength - 1)];
         }
        return $randomString;
     }
    
   public function sendMoneyPlayer($player)
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
         if($data == null) {
            $this->onForm($player);
            return;
         }
         
      if(!is_numeric($data[2])) {
         $player->sendMessage("[§c*§f]§8 Nhập giá trị cần chuyển khoảng!");
         return;
      }
         
      if($this->getArray($player, "money") > ($data[2] - 1)) {
         foreach($this->getServer()->getOnlinePlayers() as $earn) {
            if($earn->getName() == $data[1]) {
               $i = $earn;
            }
          }
          $this->addInfo($i, "Nhận giá trị tiền tệ từ người chơi ".$player->getName().", tổng giá trị tài khoản tăng thêm ".$data[2]);
          $this->addInfo($player, $player->getName().", Sử dụng tài khoản và gửi ".$data[2]." cho người chơi ".$i->getName());
          
          $this->addMoney($i, $data[2]);
          $this->takeMoney($player, $data[2]);
          
          $i->sendMessage("[§eNgân Hàng§e]§8 Bạn nhận được§c ".$data[2]."§8 từ người chơi§a ".$player->getName());
          $player->sendMessage("[§eNgân Hàng§f]§a Giao dịch thành công!");
      } else{
          $player->sendMessage("[§c!§f]§8 Thất bại!"); 
      }         
      });
      $show->setTitle("§8Transfer");
      $show->addLabel("§b⇀§8 Tổng giá trị hiện tại:§e ".$this->getArray($player, "money"));
      $this->showPlayer = array();
      $i = 0;
        foreach($this->getServer()->getOnlinePlayers() as $n) {

           if($this->bank->exists($n->getName())) {
               $this->showPlayer[] = $n->getName();
               $i++;
              }
        }

     if($i < 1) $this->showPlayer[] = "Không có người chơi";

     $show->addDropdown("§b⇀§8 Chọn người chơi", $this->showPlayer);
     $show->addInput("§b⇀§8 Nhập giá trị cần chuyển", "1000");
     $show->sendToPlayer($player);
   
   return $show;
   }

   public function showChart(Player $player)
   {
       $new = new CustomForm(function (Player $player, array $data = null)
       {
       });

       $new->setTitle("§8Charts");

       $frysal = $this->bank->getAll();
       arsort($frysal);
       $i = 0;

       foreach($frysal as $user => $nu) {
          $i++;
    
        $new->addLabel("§l§b⇁§6 TOP§c ".$i."§e ".$user.":§b ".$nu["money"]);

        if($i  > 9) break;

       }

       $new->sendToPlayer($player);

   }
   
   public function showHistory(Player $player) 
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
      });
      $show->setTitle("§8Histories");
      
      $this->histories = new Config($this->getDataFolder()."History/".$player->getName().".yml", Config::YAML);
      $dea = $this->histories->getAll();
      $i = 0;
      
      foreach($dea as $nu) {
         $i++;
         $show->addLabel("[§b".$nu["Date"]."§f] [§c".$nu["Time"]."§f]§l§7 ".$nu["Info"]);
         
         if($i > 9) break;
      }
      
     $show->sendToPlayer($player);
   }
   
   /** History */
   
   /** DataFolder: History/ */
    
   
   public function addInfo(Player $player, string $info) : void
   {

      $this->histories = new Config($this->getDataFolder()."History/".$player->getName().".yml", Config::YAML);

      date_default_timezone_set('Asia/Ho_Chi_Minh');
      
      $hour = date("H:m");
      $date = date("D-m-y");
      $count = count($this->histories->getAll());
      
      $this->histories->set("Transfer ".($count + 1), ["Date" => $date, "Time" => $hour, "Info" => $info]);
      $this->histories->save();
   }

   /** Hehe */

   public function clearAll(Player $user) : void
   {

          $this->histories = new Config($this->getDataFolder()."History/".$user->getName().".yml", Config::YAML);
          $this->histories->remove($this->histories->getAll());

   }
   
  }