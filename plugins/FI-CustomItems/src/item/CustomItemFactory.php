<?php
declare(strict_types=1);

namespace CustomItems\item;

use pocketmine\utils\SingletonTrait;
use RuntimeException;

class CustomItemFactory{
	use SingletonTrait;

	/** @var CustomItem[] */
	private array $list = [];

	/**
	 * @param CustomItem $item
	 * @param bool       $overwrite
	 * @throws RuntimeException
	 */
	public function register(CustomItem $item, bool $overwrite = false): void{
		$id = $item->getId();
		if (isset($this->list[$id]) && (!$overwrite)){
			throw new RuntimeException("Trying to overwrite an already registered item !");
		}
		$this->list[$id] = clone $item;
	}

	public function isRegistered(int $id): bool{
		if (isset($this->list[$id])){
			return true;
		}
		return false;
	}

	public function get(int $id): CustomItem{
		return $this->list[$id];
	}

	public function __construct(){

		$this->register(new EnchantedCobblestone(new CustomItemIdentifier(CustomItemIds::ENCHANTED_COBBLESTONE), "Enchanted Cobblestone"));
		$this->register(new EnchantedCoal(new CustomItemIdentifier(CustomItemIds::ENCHANTED_COAL), "Enchanted Coal"));
		$this->register(new EnchantedCoal(new CustomItemIdentifier(CustomItemIds::ENCHANTED_COAL_BLOCK), "Enchanted Coal Block"));
		$this->register(new EnchantedIronIngot(new CustomItemIdentifier(CustomItemIds::ENCHANTED_IRON_INGOT), "Enchanted Iron Ingot"));
		$this->register(new EnchantedIronBlock(new CustomItemIdentifier(CustomItemIds::ENCHANTED_IRON_BLOCK), "Enchanted Iron Block"));
		$this->register(new EnchantedGoldIngot(new CustomItemIdentifier(CustomItemIds::ENCHANTED_GOLD_INGOT), "Enchanted Gold Ingot"));
		$this->register(new EnchantedGoldBlock(new CustomItemIdentifier(CustomItemIds::ENCHANTED_GOLD_BLOCK), "Enchanted Gold Block"));
	}
}