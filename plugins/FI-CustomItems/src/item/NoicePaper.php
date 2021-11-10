<?php
declare(strict_types=1);

namespace CustomItems\item;

use CustomItems\item\utils\RarityHelper;
use pocketmine\block\VanillaBlocks;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;

class NoicePaper extends CustomItem{

	public function toItem() : Item{
		$item = ItemFactory::getInstance()->get(ItemIds::PAPER);
		$item = $this->setEnchantGlint($item);
		$nbt = $item->getNamedTag();
		$nbt->setInt("CustomItemID", $this->getId());
		$item->setCustomName(RarityHelper::toColor($this->getRarity()) . $this->getName());
		$item->setLore([
			"NOICE !",
			"Turn every clicked block into Diamond Block",
			RarityHelper::toString($this->getRarity())
		]);
		return $item;
	}

	public function onInteractBlock(PlayerInteractEvent $event) : void{
		$blockClicked =  $event->getBlock();
		$blockClicked->getPosition()->getWorld()->setBlock($blockClicked->getPosition()->asVector3(), VanillaBlocks::DIAMOND());
	}
}
