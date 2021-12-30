<?php
declare(strict_types = 1);

namespace SCoinAPIScore\Listener;

use SCoinAPIScore\Main;
use SCoinAPI\Loader;
use Ifera\ScoreHud\event\TagsResolveEvent;
use pocketmine\event\Listener;
use function count;
use function explode;
use function strval;

class TagResolveListener implements Listener
{
    
    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onTagResolve(TagsResolveEvent $event){
        $player = $event->getPlayer();
        $tag = $event->getTag();
        $tags = explode('.', $tag->getName(), 2);
        $value = "";
         
         if($tags[0] !== 'scoinapiscore' || count($tags) < 2){
            return;
            }
             switch($tags[1]){
            case "scoin":
                $value = Loader::getInstance()->mySCoin($player);
                   return true;
                
            break;
        }

        $tag->setValue(strval($value));
         }
}