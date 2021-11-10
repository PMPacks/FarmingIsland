<?php
declare(strict_types=1);

namespace CustomItems\listener;

use CustomItems\item\CustomItemFactory;
use pocketmine\entity\Human;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerEntityInteractEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemUseEvent;

class CustomItemListener implements Listener{
	/**
	 * @param BlockPlaceEvent $event
	 *
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onPlace(BlockPlaceEvent $event) : void{
		$item = $event->getItem();
		if($item->getNamedTag()->getTag("CustomItemID") !== null){
			$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
			if($citem == null) return;
			$citem->onPlace($event);
		}
	}

	/**
	 * @param PlayerInteractEvent $event
	 *
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onInteract(PlayerInteractEvent $event): void{
		$item = $event->getItem();
		if($item->getNamedTag()->getTag("CustomItemID") !== null){
			$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
			if($citem == null) return;
			$citem->onInteractBlock($event);
		}
	}

	/**
	 * @param PlayerItemUseEvent $event
	 *
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onItemUse(PlayerItemUseEvent $event): void{
		$item = $event->getItem();
		if($item->getNamedTag()->getTag("CustomItemID") !== null){
			$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
			if($citem == null) return;
			$citem->onClickAir($event);
		}
	}

	/**
	 * @param BlockBreakEvent $event
	 *
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onBreak(BlockBreakEvent $event): void{
		$item = $event->getPlayer()->getInventory()->getItemInHand();
		if($item->getNamedTag()->getTag("CustomItemID") !== null){
			$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
			if($citem == null) return;
			$citem->onDestroyBlock($event);
		}
	}

	/**
	 * @param EntityDamageByEntityEvent $event
	 *
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onDamage(EntityDamageByEntityEvent $event): void{
		$damager = $event->getDamager();
		if ($damager instanceof Human){
			$item = $damager->getInventory()->getItemInHand();
			if($item->getNamedTag()->getTag("CustomItemID") !== null){
				$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
				if($citem == null) return;
				$citem->onAttackEntity($event);
			}
		}
	}

	/**
	 * @param PlayerEntityInteractEvent $event
	 * @priority HIGHEST
	 * @handleCancelled FALSE
	 */
	public function onEntityInteract(PlayerEntityInteractEvent $event): void{
		$player = $event->getPlayer();
		$item = $player->getInventory()->getItemInHand();
		if($item->getNamedTag()->getTag("CustomItemID") !== null){
			$citem = CustomItemFactory::getInstance()->get((int) $item->getNamedTag()->getTag("CustomItemID")->getValue());
			if($citem == null) return;
			$citem->onInteractEntity($event);
		}
	}

}