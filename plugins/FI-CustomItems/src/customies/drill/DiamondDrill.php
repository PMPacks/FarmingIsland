<?php
declare(strict_types=1);

namespace CustomItems\customies\drill;

use pocketmine\item\ToolTier;

class DiamondDrill extends Drill{
	public function getBaseMiningSpeed() : int{
		return 30;
	}

	public function getTexture() : string{
		return "fici_diamond_drill";
	}

	public function getBlockToolHarvestLevel() : int{
		return ToolTier::DIAMOND()->getHarvestLevel();
	}
}