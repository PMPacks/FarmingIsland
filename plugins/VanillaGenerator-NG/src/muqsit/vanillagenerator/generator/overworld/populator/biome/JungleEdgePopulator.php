<?php

declare(strict_types=1);

namespace muqsit\vanillagenerator\generator\overworld\populator\biome;

use muqsit\vanillagenerator\generator\object\tree\BigOakTree;
use muqsit\vanillagenerator\generator\object\tree\CocoaTree;
use muqsit\vanillagenerator\generator\overworld\biome\BiomeIds;
use muqsit\vanillagenerator\generator\overworld\decorator\types\TreeDecoration;

class JungleEdgePopulator extends JunglePopulator{

	/** @var TreeDecoration[] */
	protected static array $TREES;

	protected static function initTrees() : void{
		self::$TREES = [
			new TreeDecoration(BigOakTree::class, 10),
			new TreeDecoration(CocoaTree::class, 45)
		];
	}

	public function getBiomes() : ?array{
		return [BiomeIds::JUNGLE_EDGE, BiomeIds::MUTATED_JUNGLE_EDGE];
	}

	protected function initPopulators() : void{
		$this->tree_decorator->setAmount(2);
		$this->tree_decorator->setTrees(...self::$TREES);
	}
}

JungleEdgePopulator::init();