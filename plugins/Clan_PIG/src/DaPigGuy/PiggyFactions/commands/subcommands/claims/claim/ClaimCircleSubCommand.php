<?php

declare(strict_types=1);

namespace DaPigGuy\PiggyFactions\commands\subcommands\claims\claim;

use DaPigGuy\PiggyFactions\libs\CortexPE\Commando\args\IntegerArgument;
use pocketmine\Player;

class ClaimCircleSubCommand extends ClaimMultipleSubCommand
{
    public function getChunks(Player $player, array $args): array
    {
        if (($radius = (int)$args["radius"]) < 1) {
            $this->plugin->getLanguageManager()->sendMessage($player, "commands.claim.radius-less-than-one");
            return [];
        }
        $radius--;

        $center = $player->getLevel()->getChunkAtPosition($player);
        $chunks = [];
        for ($dx = -$radius; $dx <= $radius; $dx++) {
            for ($dz = -$radius; $dz <= $radius; $dz++) {
                if ($dx * $dx + $dz * $dz > $radius * $radius) continue;
                $chunks[] = [$center->getX() + $dx, $center->getZ() + $dz];
            }
        }
        return $chunks;
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new IntegerArgument("radius"));
    }
}