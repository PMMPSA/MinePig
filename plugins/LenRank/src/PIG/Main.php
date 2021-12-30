<?php

namespace PIG;

use pocketmine\{Player, Server};
use pocketmine\command\{Command, CommandSender};
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {
	
	public $playerList = [];
	
	public function onEnable() {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->eco = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
		$this->formapi = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
	}
	
	public function onCommand(CommandSender $player, Command $cmd, string $label, array $args) : bool {
		if ($cmd->getName() === "rank") {
			$form = $this->formapi->createSimpleForm(function (Player $player, int $data = null) {
			$result = $data;if ($result === null) { return true; }
			switch ($result) {
				case 0:
				$command = "§b§a§l§k";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;case 1:
				$command = "§a§a§a";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;case 2:
				$command = "§a§a§b";
					           $this->getServer()->getCommandMap()->dispatch($player, $command);
				break;
			}
		});
		$form->setTitle("§l§f• §6Hệ Thống Rank §f•");
		$form->setContent("§l§f• §aYour Money: §e".$this->eco->myMoney($player));
		$form->addButton("§l§f• §cLên Rank §f•");
		$form->addButton("§l§f• §cThông Tin §f•");
		$form->addButton("§l§f• §cQuyền Rank §f•");
		$form->sendToPlayer($player);
		}
		return true;
	}
}