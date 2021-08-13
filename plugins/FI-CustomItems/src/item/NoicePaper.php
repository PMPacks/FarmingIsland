<?php
declare(strict_types=1);

namespace CustomItems\item;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class NoicePaper extends CustomItem{

	public function toItem() : Item{
		$item = ItemFactory::getInstance()->get(ItemIds::PAPER);
		$item = $this->setEnchantGlint($item);
		$nbt = $item->getNamedTag();
		$nbt->setInt("CustomItemID", $this->getId());
		$item->setCustomName($this->getName());
		$item->setLore([
			"NOICE !",
			RarityType::toString($this->getRarity())
		]);
		return $item;
	}
}