<?php

namespace openShopGUI\XISOQ\Sound;

use pocketmine\level\sound\Sound;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;

class SoundSuccess extends Sound{

    public function encode() : PlaySoundPacket{
        $pk = new PlaySoundPacket();
        $pk->soundName = "note.bell";
        $pk->x = $this->x;
        $pk->y = $this->y;
        $pk->z = $this->z;
        $pk->volume = 400;
        $pk->pitch = 1;
        return $pk;
    }
}