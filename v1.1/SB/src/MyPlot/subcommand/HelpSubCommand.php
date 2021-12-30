<?php
namespace MyPlot\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\TextFormat;

class HelpSubCommand extends SubCommand
{
    public function canUse(CommandSender $sender) {
        return $sender->hasPermission("myplot.command.help");
    }

    /**
     * @return \MyPlot\Commands
     */
    private function getCommandHandler()
    {
        return $this->getPlugin()->getCommand($this->translateString("command.name"));
    }

    public function execute(CommandSender $sender, array $args) {
        if (count($args) === 0) {
            $pageNumber = 1;
        } elseif (is_numeric($args[0])) {
            $pageNumber = (int) array_shift($args);
            if ($pageNumber <= 0) {
                $pageNumber = 1;
            }
        } else {
            return false;
        }

        if ($sender instanceof ConsoleCommandSender) {
            $pageHeight = PHP_INT_MAX;
        } else {
            $pageHeight = 5;
        }

        $commands = [];
        foreach ($this->getCommandHandler()->getCommands() as $command) {
            if ($command->canUse($sender)) {
                $commands[$command->getName()] = $command;
            }
        }
        ksort($commands, SORT_NATURAL | SORT_FLAG_CASE);
        $commands = array_chunk($commands, $pageHeight);
        /** @var SubCommand[][] $commands */

							//////
            $sender->sendMessage("§e༺§a BẢNG LỆNH§6 SKY§bBLOCK§e ༻");
			$sender->sendMessage("§e❖§a /sb auto§7 -§6 Di chuyển đến hòn đảo không có chủ sỡ hữu");
			$sender->sendMessage("§e❖§a /sb claim§7 -§6 Nhận ngay hòn đảo bạn đang đứng");
			$sender->sendMessage("§e❖§a /sb addhelper <người chơi>§7 - §6Thêm người vào đảo của bạn");
			$sender->sendMessage("§e❖§a /sb removehelper §e<người chơi>§7 - §7Xóa người chơi trong đảo của bạn");
			$sender->sendMessage("§e❖§a /sb homes§7 - §6Danh sách đảo của bạn");
			$sender->sendMessage("§e❖§a /sb home §e<Số> §6 - §6Dịch chuyển về đảo của bạn");
			$sender->sendMessage("§e❖§a /sb info§7 - §6Xem thông tin hòn đảo");
			$sender->sendMessage("§e❖§a /sb give §e<Tên người chơi> §7 - §6Cho người khác hòn đảo của bạn");
			$sender->sendMessage("§e❖§a /sb warp §e<X;Y> §7 - §6Di chuyển đến hòn đảo nào đó");
                        $sender->sendMessage("§e❖§a /sb name§7 - §6Đặt tên cho đảo của bạn.");
        return true;
    }
}
