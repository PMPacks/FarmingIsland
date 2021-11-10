<?php
declare(strict_types=1);

namespace CustomItems;

use CustomItems\custombreaktime\TitaniumDrillBreakTime;
use CustomItems\enchantment\CustomEnchantGlint;
use CustomItems\item\CustomItemFactory;
use CustomItems\listener\CustomItemListener;
use NgLamVN\CustomBreakTimeAPI\CustomBreakTimeAPI;
use pocketmine\data\bedrock\EnchantmentIdMap;
use pocketmine\plugin\PluginBase;

class Loader extends PluginBase{

	public function onEnable() : void{
		$this->getServer()->getPluginManager()->registerEvents(new CustomItemListener(), $this);
		new CustomItemFactory();
		EnchantmentIdMap::getInstance()->register(100, new CustomEnchantGlint());

		CustomBreakTimeAPI::register(new TitaniumDrillBreakTime("TitaniumDrill"));
	}
}