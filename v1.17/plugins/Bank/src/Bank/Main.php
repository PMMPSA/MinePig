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
????????????????????????????????????????????????????????????????????????
????????????????????????????????????????????????????????????????????????
????????????????????????????????????????????????????????????????????????
????????????????????????????????????????????????????????????????????????
????????????????????????????????????????????????????????????????????????
????????????????????????????????????????????????????????????????????????\n- NganHang version Fresh 1.17\n- Youtube: Kaido Joestar");
             return false;
         }
          
          if(!isset($args[0])) {
             $sender->sendMessage("[??c*??f]??8 S??? d???ng l???nh??a /nganhang open??8 ????? m??? giao di???n Ng??n H??ng.");
             return true;
          }
          
          if($args[0] == "dangki") {
             
             if(!is_numeric($args[1])) {
                $sender->sendMessage("??c??lVui l??ng nh???p m?? b???o v???!");        
                return true;
             }
           
             if($args[1] < 1000 or $args[1] > 999999) {
                $sender->sendMessage("??l??cCh??? c?? th??? ?????t m?? b???o v??? t??? 4 - 6 s???!");
                return true;
             }
              
           if($this->onATM($sender)) {
              $sender->sendMessage("??c??lB???n ???? c?? t??i kho???n ng??n h??ng t??? tr?????c!");
              return true;
           }
           
         /** Player create Account */
    
         $id = $this->createPosition();
    
         $this->bank->set($sender->getName(), ["money" => 0, "InterestRate" => 0, "ID" => $id, "PIN" => $args[1]]);
         $this->bank->save();
         
         $this->addInfo($sender, $sender->getName().", Kh??ch h??ng thanh to??n, s??? d???ng d???ch v??? ng??n h??ng s???.");
         
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
         
         $show->setTitle("??8Status Account");
         $show->addLabel("[??a!??f]??8 Ng??n H??ng ???? ???????c t???o th??nh c??ng!\n");
         $show->addLabel("??l??7TR???NG TH??I HI???N T???I\n");
         $show->addLabel("??b?????8 M?? b???o v???:??l??a ".$args[1]."\n");
         $show->addLabel("??b?????8 ID Th??? x??c nh???n:??l??e ".$this->getArray($sender, "ID")."\n");
         $show->addLabel("??b?????8 Tr???ng th??i:??a K??ch ho???t.");
         $show->sendToPlayer($sender); 

       
         
      } # Kich hoat

        if($args[0] == "open") {
           
           if(!$this->onATM($sender)){
              $sender->sendMessage("[??c*??f]??8 B???n ch??a ????ng k?? s??? d???ng ng??n h??ng. S??? d???ng l???nh ??b/nganhang dangki <PIN CODE>??8 ????? ????ng k?? s??? d???ng.");
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
              $player->sendMessage("??l??c".$data[1]."? M?? PIN Ch??a ch??nh x??c.");
          }   
 
      });
      
      $form->setTitle("??8Login Account");
      $form->addLabel("??b?????6 Nh???p ch??nh x??c m?? PIN ???? t???o s???n.");
      $form->addInput("??b?????e PIN CODE:");
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
                   $player->sendMessage("[??c!??f]??8 Kh??ng th???! N???p th??m m???i c?? th??? m??? giao di???n.");
                   return;  
                }
              
               $this->onTakeMoney($player);
           break;    
           case 1:
           
              if($this->getArray($player, "InterestRate") < 1){
                 $player->sendMessage("[??c!??f]??8 B???n ch??a c?? l???i nhu???n l??i su???t t??? t??i kho???n ng??n h??ng.");
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
      $form->setTitle("??8Status");
      
      /**
      * @var Contents Account
      *
      * InterestRate: $this->getArray($player, "InterestRate")
      * InterestRate/s: ($this->getArray($player, "money")/1000000)
      * ID: $this->getArray($player, "ID");
      * Status: Lock without exists config
      **/     
           
      $form->setContent("??b?????8 Kh??ch H??ng:??a ".$player->getName()."\n??b?????8 ID:??9 ".$this->getArray($player, "ID")."\n??b?????8 Tr???ng th??i:??b K??ch ho???t.\n\n??l??7TR???NG TH??I HI???N T???I\n\n??r??b?????8 T???ng gi?? tr???:??l??e ".$this->getArray($player, "money")."\n??r??b?????8 L??i su???t hi???n c??:??l??6 ".$this->getArray($player, "InterestRate")."\n??r??b?????8 L??i su???t m???i ph??t:??l??c ".round($this->getArray($player, "money")/1000000)."/m");
      
      $form->addButton("??l??8R??T TI???N", 0, "textures/ui/icon_blackfriday"); // 0 
      $form->addButton("??l??8R??T L??I", 0, "textures/ui/purtle"); // 1
      $form->addButton("??l??8N???P TI???N", 0, "textures/ui/promo_gift_big"); // 2
      $form->addButton("??l??8CHUY???N KHO???NG", 0, "textures/ui/promo_gift_small_green"); // 3
      $form->addButton("??l??bB???NG X???P H???NG", 0, "textures/ui/poison_effect"); // 4
      $form->addButton("??l??c?????I M?? PIN", 0, "textures/ui/invite_base"); // 5
      $form->addButton("??l??7L???CH S??? GIAO D???CH", 0, "textures/items/banner_pattern"); // 6
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
       
       $this->addInfo($player, $player->getName().", S??? d???ng t??i kho???n r??t ".$data[1]." gi?? tr??? ti???n m???t.");
       $this->takeMoney($player, $data[1]);
       $this->EconomyAPI->addMoney($player->getName(), $data[1]);
       $player->sendPopup("??l??b?????e C???ng??6 ".$data[1]."d??e v??o t??i kho???n ch??nh!");
    });
    
    $form->setTitle("??8Withdrawal");
    $form->addLabel("??b?????8 T???ng gi?? tr???:??a ".$this->getArray($player, "money"));
    $form->addSlider("??b?????8 Ch???n gi?? tr??? mu???n r??t", 0, $this->getArray($player, "money"));
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
        
     $this->addInfo($player, $player->getName().", S??? d???ng t??i kho???n r??t ".$data[1]." t??? t???ng l???i nhu???n nh???n ???????c t??? t??i kho???n.");
        
     $this->EconomyAPI->addMoney($player->getName(), $data[1]);
     $this->takeInt($player, $data[1]);
     $player->sendPopup("??l??b?????e C???ng??6 ".$data[1]."d??e v??o t??i kho???n ch??nh!");      

     });
     $form->setTitle("??8Withdraw profit");
     $form->addLabel("??b?????8 L??i su???t hi???n c??:??e ".$this->getArray($player, "InterestRate"));
     $form->addSlider("??b?????8 Ch???n s??? l??i su???t", 0, $this->getArray($player, "InterestRate"));
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
           $player->sendMessage("[??c!??f]??8 Nh???p s??? ti???n mu???n g???i v??o t??i kho???n.");         
           return;  
        }
      if($this->EconomyAPI->myMoney($player->getName()) > ($data[1] - 1)){
         $this->addInfo($player, $player->getName().", S??? d???ng t??i kho???n, n???p gi?? tr??? v?? t???ng gi?? tr??? t??ng th??m ".$data[1]);
         $this->EconomyAPI->reduceMoney($player->getName(), $data[1]);
         $this->addMoney($player, $data[1]);
         $player->addTitle("??l??c-".$data[1], "??l??eC??m ??n b???n ???? tin d??ng Ng??n H??ng.");
         $player->sendPopup("??l??b?????e C???ng??6 ".$data[1]."d??e v??o t??i kho???n ng??n h??ng.");
      }else {
         $player->sendMessage("[??c!??f]??8 Kho???ng gi?? tr??? g???i ??i ????? kh??ng ???????c ch???p nh???n.");    
      }
    });
    
     $form->setTitle("??bBank depositors");   
     $form->addLabel("[??6*??f]??8 B???n c?? th??? nh???n l???i nhu???n, t???ng s??? gi?? tr??? c??ng l???n th?? t???ng l??i su???t nh???n ???????c c??ng cao.");
     $form->addInput("??b?????8 Nh???p gi?? tr??? mu???n g???i", "1000");
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
              $sender->sendMessage("[??cNg??n H??ng??f] ??8Ch??? c?? th??? ?????t m?? b???o v??? t??? 4 - 6 s???!");
              return;            
              }
          
       if($this->getArray($player, "PIN") !== $data[0]) {
          $player->sendMessage("[??cNg??n H??ng??f] ??8Sai m?? PIN! Nh???p ch??nh x??c m?? PIN c?? m???i c?? th??? ?????i.");
          return;
          }

      if($data[1] !== $data[2]){
          $player->sendMessage("??l[??c*??f]??c M?? PIN Nh???p l???i sai. Xin th??? l???i!");
          return;
          }
          $this->addInfo($player, "Thay ?????i m?? PIN c???a t??i kho???n!");
          
          unset($this->logan[$player->getName()]);
           
          $this->bank->set($player->getName(), ["money" => $this->getArray($player, "money"), "InterestRate" => $this->getArray($player, "InterestRate"), "ID" => $this->getArray($player, "ID"), "PIN" => $data[1]]);
          $this->bank->save();
          $player->sendMessage("[??6*??f] ??7M?? b???o v??? ???? ???????c ?????i th??nh??b ".$data[1]);    
       });
     $form->setTitle("??8Change PIN");
     $form->addInput("??b?????8 NH???P M?? PIN C??");
     $form->addInput("??b?????8 NH???P M?? PIN M???I");
     $form->addInput("??b?????8 NH???P L???I M?? PIN M???I");
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
         $player->sendMessage("[??c*??f]??8 Nh???p gi?? tr??? c???n chuy???n kho???ng!");
         return;
      }
         
      if($this->getArray($player, "money") > ($data[2] - 1)) {
         foreach($this->getServer()->getOnlinePlayers() as $earn) {
            if($earn->getName() == $data[1]) {
               $i = $earn;
            }
          }
          $this->addInfo($i, "Nh???n gi?? tr??? ti???n t??? t??? ng?????i ch??i ".$player->getName().", t???ng gi?? tr??? t??i kho???n t??ng th??m ".$data[2]);
          $this->addInfo($player, $player->getName().", S??? d???ng t??i kho???n v?? g???i ".$data[2]." cho ng?????i ch??i ".$i->getName());
          
          $this->addMoney($i, $data[2]);
          $this->takeMoney($player, $data[2]);
          
          $i->sendMessage("[??eNg??n H??ng??e]??8 B???n nh???n ???????c??c ".$data[2]."??8 t??? ng?????i ch??i??a ".$player->getName());
          $player->sendMessage("[??eNg??n H??ng??f]??a Giao d???ch th??nh c??ng!");
      } else{
          $player->sendMessage("[??c!??f]??8 Th???t b???i!"); 
      }         
      });
      $show->setTitle("??8Transfer");
      $show->addLabel("??b?????8 T???ng gi?? tr??? hi???n t???i:??e ".$this->getArray($player, "money"));
      $this->showPlayer = array();
      $i = 0;
        foreach($this->getServer()->getOnlinePlayers() as $n) {

           if($this->bank->exists($n->getName())) {
               $this->showPlayer[] = $n->getName();
               $i++;
              }
        }

     if($i < 1) $this->showPlayer[] = "Kh??ng c?? ng?????i ch??i";

     $show->addDropdown("??b?????8 Ch???n ng?????i ch??i", $this->showPlayer);
     $show->addInput("??b?????8 Nh???p gi?? tr??? c???n chuy???n", "1000");
     $show->sendToPlayer($player);
   
   return $show;
   }

   public function showChart(Player $player)
   {
       $new = new CustomForm(function (Player $player, array $data = null)
       {
       });

       $new->setTitle("??8Charts");

       $frysal = $this->bank->getAll();
       arsort($frysal);
       $i = 0;

       foreach($frysal as $user => $nu) {
          $i++;
    
        $new->addLabel("??l??b?????6 TOP??c ".$i."??e ".$user.":??b ".$nu["money"]);

        if($i  > 9) break;

       }

       $new->sendToPlayer($player);

   }
   
   public function showHistory(Player $player) 
   {
      $show = new CustomForm(function (Player $player, array $data = null)
      {
      });
      $show->setTitle("??8Histories");
      
      $this->histories = new Config($this->getDataFolder()."History/".$player->getName().".yml", Config::YAML);
      $dea = $this->histories->getAll();
      $i = 0;
      
      foreach($dea as $nu) {
         $i++;
         $show->addLabel("[??b".$nu["Date"]."??f] [??c".$nu["Time"]."??f]??l??7 ".$nu["Info"]);
         
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