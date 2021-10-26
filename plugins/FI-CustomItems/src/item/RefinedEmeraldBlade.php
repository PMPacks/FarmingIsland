<?php
declare(strict_types=1);

namespace CustomItems\item;

use CustomItems\item\utils\RarityHelper;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;

class RefinedEmeraldBlade extends CustomItem{
	public function toItem() : Item{
		$item = VanillaItems::EMERALD();
		$item = $this->setEnchantGlint($item);
		$nbt = $item->getNamedTag();
		$item->setCustomName(RarityHelper::toColor($this->getRarity()) . $this->getName());
		$item->setLore([
			"",
			"§r§7A powerful blade made from pure §aEmeralds.",
			"",
			"§r§bAbility: §eThe §aEmerald §eBuff",
			"§r§7This blade become stronger as you \ncarry more §6coins §7in your purse.",
			"",
			"§r§6Refined §ebonus",
			"§r§7Every kills give you §6coins \n§7depend on your §cDamage",
			RarityHelper::toString($this->getRarity())
		]);
		return $item;
	}
}
