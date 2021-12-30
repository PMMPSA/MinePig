<?php


namespace Anvil;

use Anvil\EventListener;

use pocketmine\plugin\PluginBase;

use pocketmine\event\Listener;

use onebone\economyapi\EconomyAPI;

use pocketmine\utils\TextFormat;

use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\Form;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\FormAPI;

use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacketV2;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\PopSound;

use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\item\Armor;

use pocketmine\Server;
use pocketmine\Player;

class Main extends PluginBase implements Listener
{

    public function onEnable(){
        if (is_null($this->getServer()->getPluginManager()->getPlugin("EconomyAPI"))) {
            $this->getLogger()->error("Máy Chủ Không Có EconomyAPI vui lòng cài đăti");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        if (is_null($this->getServer()->getPluginManager()->getPlugin("FormAPI"))) {
            $this->getLogger()->error("Máy Chủ Không Có FormAPI vui lòng cài đăti");
            $this->getServer()->getPluginManager()->disablePlugin($this);
            return;
        }
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        
        $this->getLogger()->info("Plugin By PIG");
        
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->cfg = $this->getConfig()->getAll();
    }
    
    public function onDisable(){
        $this->getLogger()->info("Plugin Off");
    }
    
    public function openForm(Player $player) {


        $form = new SimpleForm(function (Player $player, $data){
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $this->openRepair($player);
                    break;
                case 1:
                    $this->openRename($player);
                    break;


            }
        }
        );
        $form->setTitle($this->getConfig()->getNested("Menu.Title"));
        $form->setContent($this->getConfig()->getNested("Menu.Content"));
        $form->addButton($this->getConfig()->getNested("Menu.Repair-Btn"), 0, "textures/ui/hammer_r");
        $form->addButton($this->getConfig()->getNested("Menu.Rename-Btn"), 0, "textures/ui/mashup_PaintBrush");
        $form->addButton($this->getConfig()->getNested("Menu.Exit-Btn"), 0, "textures/other/axit");
        $form->sendToPlayer($player);
    }

    public function openRepair(Player $player) {
      $player->getlevel()->addSound(new PopSound($player));
        $form = new SimpleForm(function (Player $player, $data){
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:

                    if (\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($player) >= $this->getConfig()->getNested("Repair.Cost")) {
                        $item = $player->getInventory()->getItemInHand();
                        if ($item instanceof Armor or $item instanceof Tool) {
                            $id = $item->getId();
                            $meta = $item->getDamage();
                            $player->getInventory()->removeItem(Item::get($id, $meta, 1));
                            $newitem = Item::get($id, 0, 1);
                            if ($item->hasCustomName()) {
                                $newitem->setCustomName($item->getCustomName());
                            }
                            if ($item->hasEnchantments()) {
                                foreach ($item->getEnchantments() as $enchants) {
                                    $newitem->addEnchantment($enchants);
                                }
                            }
                            $player->getInventory()->addItem($newitem);
                            $player->sendMessage($this->getConfig()->getNested("Repair-Message.succesfully"));
                            $player->getlevel()->addSound(new AnvilUseSound($player));
                            EconomyAPI::getInstance()->reduceMoney($player, $this->getConfig()->getNested("Repair.Cost"));
                            return true;
                        } else {
                            $player->sendMessage($this->getConfig()->getNested("Repair-Message.HoldItem"));
                            return false;
                        }
                        return true;
                    } else {
                        $player->sendMessage($this->getConfig()->getNested("Repair-Message.NoMoney").$this->getConfig()->getNested("Repair.Cost")." money");
                    }
                    break;
                case 1:
                    $this->openForm($player);
                    break;
            }



        }
        );
        $mymoney1 = EconomyAPI::getInstance()->myMoney($player);
        $form->setTitle($this->getConfig()->getNested("Repair.Title"));
        $form->setContent($this->getConfig()->getNested("Repair.Content")."\n".$this->getConfig()->getNested("Repair.ShowMyMoney").$mymoney1);
        $form->addButton($this->getConfig()->getNested("Repair.Button"), 0, "textures/ui/hammer_r");
        $form->addButton($this->getConfig()->getNested("Repair.Back"), 0, "textures/blocks/barrier");
        $form->sendToPlayer($player);
    }

    public function openRename(Player $sender){
      $sender->getlevel()->addSound(new PopSound($sender));
        $form = new CustomForm(function(Player $sender, ?array $data){
            if(!isset($data)) return;
            $item = $sender->getInventory()->getItemInHand();
            if ($item->getId() == 0) {
                $sender->sendMessage($this->getConfig()->getNested("Rename-Message.HoldItem"));
                return;
            }
            if (\pocketmine\Server::getInstance()->getPluginManager()->getPlugin("EconomyAPI")->myMoney($sender) >= $this->getConfig()->getNested("Rename.Cost")) {
                EconomyAPI::getInstance()->reduceMoney($sender, $this->getConfig()->getNested("Rename.Cost"));
                $item->setCustomName($data[1]);
                $sender->getInventory()->setItemInHand($item);
                $sender->sendMessage($this->getConfig()->getNested("Rename-Message.succesfully")." ".$data[1]);
                $sender->getlevel()->addSound(new AnvilUseSound($sender));
            } else {
                $sender->sendMessage($this->getConfig()->getNested("Rename-Message.NoMoney")." ".$this->getConfig()->getNested("Rename.Cost"));
            }


        });
        $mymoney = EconomyAPI::getInstance()->myMoney($sender);
        $form->setTitle("§l§e•§c Đổi Tên §e•");
        $form->addLabel($this->getConfig()->getNested("Rename.ShowMyMoney").$mymoney."\n".$this->getConfig()->getNested("Rename.Content"));
        $form->addInput("§l§c↣§a Nhập tên:");
        $form->sendToPlayer($sender);
    }
}
