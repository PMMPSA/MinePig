<?php

namespace Heisenburger69\BurgerSpawners\Utilities;

use Heisenburger69\BurgerSpawners\Main;
use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use Heisenburger69\BurgerSpawners\libs\jojoe77777\FormAPI\CustomForm;
use Heisenburger69\BurgerSpawners\libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Player;
use pocketmine\utils\TextFormat as C;

class Forms
{

    /**
     * @var array
     */
    public static $usingSpawner = [];

    /**
     * @param MobSpawnerTile $spawner
     * @param Player $player
     */
    public static function sendSpawnerForm(MobSpawnerTile $spawner, Player $player): void
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return;
            }
            switch ($data) {
                case 0:
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    if ($spawner instanceof MobSpawnerTile) {
                        $spawner->sendAddSpawnersForm($player);
                    }
                    break;
                case 1:
                    $spawner = Forms::$usingSpawner[$player->getName()];
                    if ($spawner instanceof MobSpawnerTile) {
                        $spawner->sendRemoveSpawnersForm($player);
                    }
                    break;
                case 2:
                    break;
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();

        $form->setTitle(C::BOLD . C::DARK_GRAY . "CHỈNH SỬA LỒNG " . $spawnerName);
        $form->setContent(C::BOLD . C::AQUA . "§7§o➼ Số lượng lồng đã kết hợp: " . C::RESET . $count);
        $form->addButton(C::BOLD . C::GOLD . "§l§e• §cThêm lồng spawn vào §e•");
        $form->addButton(C::BOLD . C::GOLD . "§l§e• §cXóa lồng spawn §e•");
        $form->addButton(C::BOLD . C::RED . "§r§l【 §c✘ Đóng menu §r§l】");
        $player->sendForm($form);
    }

    public static function sendAddSpawnerForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {

                    $entityId = $spawner->getEntityId();
                    $count = (int)$response[1];

                    $item = $player->getInventory()->getItemInHand();
                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $max = $stackCount;
                    } else {
                        $message = ConfigManager::getMessage("no-available-spawners");
                        $player->sendMessage(Main::PREFIX . $message);
                        return;
                    }

                    if ($count > $max) {
                        $count = $max;
                        $message = ConfigManager::getMessage("all-spawners-stacked");
                        $player->sendMessage(Main::PREFIX . $message);
                    }

                    $item = $player->getInventory()->getItemInHand();
                    if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
                        $stackCount = $item->getCount();
                        $leftover = $stackCount - $count;
                        if($leftover > 0) {
                            $item->setCount($leftover);
                            $player->getInventory()->setItemInHand($item);
                        } else {
                            $player->getInventory()->setItemInHand(Item::get(Item::AIR));
                        }
                    }

                    $spawner->setCount($spawner->getCount() + $count);
                }
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();
        $entityId = $spawner->getEntityId();

        $max = 1;

        $item = $player->getInventory()->getItemInHand();
        if ($item->getNamedTag()->hasTag(MobSpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $entityId) {
            $stackCount = $item->getCount();
            $max = $stackCount;
        }

        $form->setTitle(C::BOLD . C::DARK_BLUE . "CHỈNH SỬA LỒNG " . $spawnerName);
        $form->addLabel(C::BOLD . C::AQUA . "§7§o➼ Số lồng đã thêm: " . C::RESET . $count);
        $form->addSlider(C::BOLD . C::GOLD . "§l§eSố lượng lồng muốn thêm" . C::YELLOW, 1, $max, 1);
		$player->sendForm($form);
	}

    public static function sendRemoveSpawnersForm(Player $player, MobSpawnerTile $spawner): void
    {
        $form = new CustomForm(function (Player $player, array $response = null) {
            if ($response === null) {
                return;
            }
            if (isset($response[1])) {
                $spawner = Forms::$usingSpawner[$player->getName()];
                if ($spawner instanceof MobSpawnerTile) {

                    $entityId = $spawner->getEntityId();
                    $count = (int)$response[1];
                    $max = $spawner->getCount();

                    if ($count > $max) {
                        $count = $max;
                        $message = ConfigManager::getMessage("all-spawners-removed");
                        if($message === "") {
                            $message = C::colorize("&aTất cả các lồng đã được hủy bỏ và thêm lại vào túi đồ của bạn.");
                        }
                        $player->sendMessage(Main::PREFIX . $message);
                    }

                    $entityName = Utils::getEntityNameFromID($entityId);
                    $spawnerItem = Main::$instance->getSpawner($entityName, $count);
                    $player->getInventory()->addItem($spawnerItem);

                    $spawner->setCount($spawner->getCount() - $count);
                    if($spawner->getCount() <= 0) {
                        $spawner->getLevel()->setBlock($spawner, Block::get(Block::AIR));
                        $spawner->close();
                    }
                }
            }
        });
        Forms::$usingSpawner[$player->getName()] = $spawner;

        $spawnerName = $spawner->getName();
        $count = $spawner->getCount();

        $form->setTitle(C::BOLD . C::DARK_BLUE . "CHỈNH SỬA LỒNG " . $spawnerName);
        $form->addLabel(C::BOLD . C::AQUA . "§7§o➼ Số lồng đã thêm: " . C::RESET . $count);
        if($count > 64) $count = 64;
        $form->addSlider(C::BOLD . C::GOLD . "§l§eSố lượng lồng muốn xóa" . C::YELLOW, 1, $count, 1);
        $player->sendForm($form);
    }
}