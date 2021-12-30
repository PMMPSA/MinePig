<?php

namespace Renation;

use Renation\Loader;
use Renation\UsersData;

use Ifera\ScoreHud\event\TagsResolveEvent;
use Ifera\EcoAPIScore\Main;

use pocketmine\Player;
use pocketmine\event\Listener;

use function count;
use function explode;
use function strval;


class TagResolveListener implements Listener
{
   private $plugin;
   
   public function __construct(Loader $plugin)
   {
      $this->plugin = $plugin;
   }
   
   public function getPlugin() : Loader
   {
      return $this->plugin;
   }
   
   public function onTag(TagsResolveEvent $event)
   {
        $tag = $event->getTag();
        $tags = explode('.', $tag->getName(), 2);
        $value = "";

        if($tags[0] !== 'chuyensinh' || count($tags) < 2){
            return;
        }
        switch($tags[1]) {
           case "level":
              $value = UsersData::getLevel($event->getPlayer());
           break;
        }
        
        $tag->setValue(strval($value));
   }
}