<?php

declare(strict_types = 1);

namespace SCoinAPI;

use SCoinAPI\Loader;
use pocketmine\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\plugin\Plugin;

class Commands extends Command implements PluginIdentifiableCommand
{

   /** Loader Main Class */

   public $plugin;

   public function __construct(Loader $plugin)
   {
      parent::__construct("scoin", "SCoin", ("/scoin"), []);

      $this->plugin = $plugin;
   }

   public function getPlugin() : Plugin
   {
       return $this->plugin;
   }

   public function execute(CommandSender $sender, $commandLabel, array $args)
   {
      if($sender instanceof Player) {
          $this->plugin->showMain($sender);
      } else $sender->sendMessage("You can't use this Command in here!");
   }

}