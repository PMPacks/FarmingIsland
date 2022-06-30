<?php
declare(strict_types=1);

namespace CustomItems\quality;

use pocketmine\item\Item;

class ItemQuality{
	public static function getQuality(Item $item) : ?Quality{
		$nbt = $item->getNamedTag();
		$tag = $nbt->getTag("quality");
		if(!is_null($tag)){
			return Quality::fromId($tag->getValue());
		}
		return null;
	}

	public static function setQuality(Item $item, Quality $quality) : Item{
		$nbt = $item->getNamedTag();
		$nbt->setInt("quality", $quality->getId());
		$item->setNamedTag($nbt);
		if($quality->getId() != Quality::NORMAL()->getId()){
			if(!$item->hasCustomName()){
				$item->setCustomName("§r" . $quality->getColor() . "★§r§f§l" . $item->getName() . "§r" . $quality->getColor() . "★");
			}else{
				$item->setCustomName("§r" . $quality->getColor() . "★§r§f§l" . $item->getCustomName() . "§r" . $quality->getColor() . "★");
			}

		}
		return $item;
	}
}