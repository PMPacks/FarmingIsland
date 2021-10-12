<?php
declare(strict_types=1);

namespace NgLamVN\Example;

use NgLamVN\CustomBreakTimeAPI\BaseBreakTime;
use pocketmine\block\Block;
use pocketmine\block\BlockLegacyIds;
use pocketmine\item\Item;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class CustomShears extends BaseBreakTime
{
    public function getBreakTime(Block $block, Item $itemuse, Player $player) : int{
		return match ($block->getId()) {
			BlockLegacyIds::WOOL => 4,
			BlockLegacyIds::GLASS, BlockLegacyIds::LEAVES => 1,
			default => 999999,
		};
	}

	public function onBreak(Vector3 $pos, Item $item, Player $player, bool $createParticles = true){
		$item = VanillaItems::SHEARS(); // Make block drop like use shears !
		parent::onBreak($pos, $item, $player, $createParticles); // TODO: Change the autogenerated stub
	}
}