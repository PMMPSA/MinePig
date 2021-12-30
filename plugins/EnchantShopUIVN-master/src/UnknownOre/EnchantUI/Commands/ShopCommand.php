<?php
namespace UnknownOre\EnchantUI\Commands;
use pocketmine\command\{
    Command,
    PluginCommand,
    CommandSender
};
use pocketmine\Player;
use UnknownOre\EnchantUI\Main;

/**
 * Class ShopCommand
 * @package UnknownOre\EnchantUI\Commands
 */
class ShopCommand extends PluginCommand{
    
    /**
    * ShopCommand constructor.
    * @param Main $plugin
    */
    public function __construct(Main $plugin){
        parent::__construct('buyenchant', $plugin);
        $this->setAliases(['buyenchant','buyenchant']);
        $this->setDescription('Buy Enchant');
        $this->setPermission("eshop.command");
        $this->plugin = $plugin;
    }
    
   /**
    * @param CommandSender $sender
    * @param string $commandLabel
    * @param array $args
    *
    * @return bool
    */
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool{
        if(!$sender->hasPermission("eshop.command")){
            $sender->sendMessage($this->plugin->shop->getNested('messages.no-perm'));
            return true;
        }
        if(!$sender instanceof Player){
            $sender->sendMessage("Vui lòng sử dụng lệnh này trong GAME!");
            return true;
        }   
        $this->plugin->listForm($sender);
        return true;
	}
   
}
