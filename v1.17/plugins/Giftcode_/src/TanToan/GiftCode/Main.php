<?php

namespace TanToan\GiftCode;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
//use jojoe77777\FormAPI\FormAPI as FAPI;
class Main extends PluginBase implements Listener
{
    /**
     * @var Config
     */
    private $code, $type, $form;

    public function onEnable()
    {
        if (!file_exists($this->getDataFolder())) {
            mkdir($this->getDataFolder());
        }
        $this->saveResource("type.yml");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->form = $this->getServer()->getPluginManager()->getPlugin('FormAPI');
        $this->code = new Config($this->getDataFolder() . "code.yml", Config::YAML);
        $this->type = new Config($this->getDataFolder() . "type.yml", Config::YAML);
        //check code lỗi
        $this->getScheduler()->scheduleTask(new Checkallcode($this));
    }

    public function onjoin(PlayerJoinEvent $event)
    {
        $t = $this->code->getAll();
        foreach (array_keys($this->code->getAll()) as $code) {
            if ($t[$code]['Player'] === strtolower($event->getPlayer()->getName())) {
                $event->getPlayer()->sendMessage("§l§f•§a GiftCode Nhẫn Giả chưa sử dụng:§c ".$code." §7(§aLoại:§c ".$t[$code]['Type']."§7)");
                
            }
        }
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        switch (strtolower($command->getName())) {
            case "giftcode":
                if (!isset($args[0])) {
                    $sender->sendMessage("§l§f•§c Vui lòng sử dụng lệnh §a/mycode");
                    return true;
                }
                switch (strtolower($args[0])) {
                    case "help":
                        //$sender->sendMessage("§f• §6GiftCode Help");
                        //$sender->sendMessage("§f• §c/giftcode type :để xem loại code");
                        //$sender->sendMessage("§f• §c/giftcode give <player> <type> : Đưa code cho người chơi");
                        $sender->sendMessage("§l§f• §c/mycode§a: để dùng và kiểm tra code của bạn");
                        //$sender->sendMessage("§f• §c/giftcode get <code> §a: để sử dụng code");
                        //$sender->sendMessage("§f• §c/giftcode all <page>§a : để xem tất cả code chưa sử dụng của member");
                        break;
                    case "give":
                        if (count($args) !== 3) {
                            $sender->sendMessage("§l§f• §c /giftcode give <player> <type> : Đưa code cho người chơi");
                            return true;
                        }
                        if (!$sender->isOp()) {
                            $sender->sendMessage("§l§4♤ Bạn không đủ quyền dùng lệnh này !");
                            return true;
                        }
                        //check type code
                        $type = $this->type->getAll();
                        if (!isset($type[$args[2]])) {
                            $sender->sendMessage("§l§f• §cLoại code không hợp lệ, bấm §a/giftcode type §cđể xem");
                            return true;
                        }
                        $code = $this->createcode();
                        $t = $this->code->getAll();
                        $t[$code]["Player"] = strtolower($args[1]);
                        $t[$code]["Type"] = $args[2];
                        $this->code->setAll($t);
                        $this->code->save();
                        $sender->sendMessage("§l§f• §cTạo code thành công $code - $args[1] - $args[2]");
                        $player = Server::getInstance()->getPlayer($args[1]);
                        if ($player !== null) {
                            $player->sendMessage("§l§f• §eBạn nhận được code§c $code §eloại§c $args[2] §e. Bấm §c/mycode <code> §eđể dùng");
                        }
                        break;
                    case "all":
                        if (!$sender->isOp()) {
                            $sender->sendMessage("§l§a♤§c Bạn không đủ quyền dùng lệnh này !");
                            return true;
                        }
                        $sender->sendMessage("Code Member chưa sử dụng");
                        $t = $this->code->getAll();
                        foreach (array_keys($this->code->getAll()) as $code) {
                            $sender->sendMessage("- Code " . $code . " - Player: " . $t[$code]['Player'] . "- Type: " . $t[$code]['Type']);
                        }
                        break;
                    case "type":
                        $sender->sendMessage("Các loại code");
                        foreach (array_keys($this->type->getAll()) as $code) {
                            $sender->sendMessage("- Code " . $code);
                        }
                        break;
                    case "get":
                        if (!$sender instanceof Player) {
                            $sender->sendMessage("§l§f• §aSử dụng lệnh ingame ! ");
                            return true;
                        }
						if(!isset($args[1])){
							return true;
						}
                        $code = strtolower($args[1]);
                        $t = $this->code->getAll();
                        if (isset($t[$code])) {
                            //check chủ code
                            if ($t[$code]["Player"] !== strtolower($sender->getName())) {
                                $sender->sendMessage("§l§f•§c Đây không phải là GiftCode của Nhẫn Giả! Vui lòng thử lại.");
                                return true;
                            }
                            //check slot item
                            $inv = $sender->getInventory();
                            /*if (count($inv->getContents()) !== 0) {
                                $sender->sendMessage("§f• §cBạn cần cất hết đồ hiện có ở túi đồ ra chỗ khác để nhận code!");
                                return true;
                            }*/
                            //give reward va xoa data code
                            foreach ($this->type->get($t[$code]["Type"]) as $command) {
                                Server::getInstance()->dispatchCommand($sender, str_replace("{player}", $sender->getName(), $command));
                            }
                            $this->code->remove($code);
                            $this->code->save();
                        } else {
                            $sender->sendMessage("§l§f•§c GiftCode không hợp lệ! Vui lòng thử lại.");
                        }
                        break;
                    default:
                        $sender->sendMessage("§l§f•§c Cú pháp không hợp lệ! Vui lòng thử lại.");
                        return true;
                }
                break;
            case "mycode":
              $form = $this->form->createCustomForm(function(Player $player, $data){
              	if($data == null){
					
              	}
			  if(isset($data[0])){
                 $this->getServer()->getCommandMap()->dispatch($player, "giftcode get ".$data[0]);
			  }
              	});
                $form->setTitle("§l§1HỆ THỐNG GIFTCODE");
                $form->addInput("§l§f•§a Vui lòng nhập mã GiftCode");
              
                //$sender->sendMessage("Đang kiểm tra");
                $t = $this->code->getAll();
                $mess = "";
                foreach (array_keys($this->code->getAll()) as $code) {
                    if ($t[$code]['Player'] === strtolower($sender->getName())) {
						$mess = "True";
						$form->addLabel("§l§f•§a GiftCode Nhẫn Giả chưa sử dụng:§c ".$code." §7(§aLoại:§c ".$t[$code]['Type']."§7)");
                       // $mess = $mess. ("§l§f• §aCode của bạn chưa sử dụng§e " . $code ."§a Type:§e " . $t[$code]['Type']);
                    }
                }
               if ($mess === "") {
                    $form->addLabel("§l§cNhẫn Giả hiện không có GiftCode để hiển thị");
                }
                $form->sendToPlayer($sender);
                break;
        }
        return true;
    }

    private function createcode()
    {
        $t = $this->code->getAll();
        $code = strtolower("NGSC" . substr(md5(uniqid(mt_rand(), true)), 0, 3));
        if (isset($t[$code])) {
            // code trùng tạo lại
            $this->createcode();
        }
        return $code;
    }
}