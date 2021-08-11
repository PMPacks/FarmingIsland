<?php
declare(strict_types=1);

namespace CustomItems\item;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class EnchantedCoalBlock extends CustomItem{

	public function toItem() : Item{
		$item = ItemFactory::getInstance()->get(ItemIds::COAL_BLOCK);
		$item->setCustomName($this->getName());
		return $item;
	}
}
