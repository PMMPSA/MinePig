<?php
namespace Anvil;

use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\Item;
use pocketmine\block\Anvil;
use Anvil\Main;
Class EventListener implements Listener{

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $ev){
        if($ev->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) return;
        if($ev->getBlock() instanceof Anvil){
            $ev->setCancelled();
            $this->plugin->openForm($ev->getPlayer());
        }
    }
}