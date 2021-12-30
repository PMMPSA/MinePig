<?php
declare(strict_types = 1);

namespace SCoinAPIScore;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\Player;

use SCoinAPIScore\Listener\EventListener;
use SCoinAPIScore\Listener\TagResolveListener;

class Main extends PluginBase
{
    
    public function onEnable()
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);
    }

   
    
}
        
    
    
