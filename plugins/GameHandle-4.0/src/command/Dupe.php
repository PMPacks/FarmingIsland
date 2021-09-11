<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class Dupe extends BaseCommand{
	public function __construct(Core $core){
		parent::__construct($core, "dupe");
		$this->setDescription("Duplicate item in hand");
		$this->setPermission("gh.dupe");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$sender->hasPermission("gh.dupe")){
			$sender->sendMessage("You dont have permission to use this command");
			return;
		}
		if($sender instanceof Player){
			$item = $sender->getInventory()->getItemInHand();
			if($item->getId() == 0){
				$sender->sendMessage("Why you want to duplicate nothing ?");
				return;
			}
			if($sender->getInventory()->canAddItem($item)){
				$sender->getInventory()->addItem($item);
				$sender->sendMessage("Item duplicate successfully !");
			}else{
				$sender->sendMessage("Failed to duplicate this item, maybe your inventory is full.");
			}
		}else{
			$sender->sendMessage("Please use this command in-game !");
		}
	}
}