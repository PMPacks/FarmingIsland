<?php
declare(strict_types=1);

namespace Bazaar\ui;

use Bazaar\event\PlayerCreateOrderEvent;
use Bazaar\provider\SqliteProvider;
use Bazaar\utils\OrderDataHelper;
use JinodkDevTeam\utils\ItemUtils;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

class SellOrderUI extends BaseUI{

	private string $itemid;

	public function __construct(Player $player, string $itemid){
		$this->itemid = $itemid;
		parent::__construct($player);
	}

	public function execute(Player $player) : void{
		Await::f2c(function() use ($player){
			//GETTING TOP ORDER INFO
			$data = yield from $this->getBazaar()->getProvider()->selectSellItem($this->itemid, true);
			if(empty($data)){
				$top_sell = "";
			}else{
				$order = OrderDataHelper::formData($data[0], OrderDataHelper::SELL);
				$top_sell = $order->getPrice();
			}
			$max = ItemUtils::getItemCount($player->getInventory(), ItemUtils::toItem($this->itemid));

			$form = new CustomForm(function(Player $player, ?array $data) use ($max){
				if(!isset($data)) return;
				if((!isset($data[2])) or (!isset($data[3]))) return;
				$amount = $data[2];
				$price = $data[3];
				if(!(is_numeric($amount) and is_numeric($price))){
					$player->sendMessage("Amount or Price must be numeric !");
					return;
				}
				if(is_int($amount)){
					$player->sendMessage("Amount must be a integer number !");
					return;
				}
				if((int) $amount <= 0){
					$player->sendMessage("Amount must be > 0 !");
					return;
				}
				if((float) $price <= 0){
					$player->sendMessage("Price must be > 0 !");
					return;
				}
				if($amount > $max){
					$player->sendMessage("You dont have enough item to create sell order");
					return;
				}

				$this->confirm($player, (int) $amount, (float) $price);
			});

			$form->setTitle("Create sell order");
			$form->addLabel("Item: " . ItemUtils::toName($this->itemid));
			$form->addLabel("Current top sell order: " . $top_sell);
			$form->addInput("Amount:", "Max: " . $max);
			if($top_sell == ""){
				$form->addInput("Price per item:", "123456789");
			}else{
				$form->addInput("Price per item:", (string) ($top_sell - 0.1));
			}

			$player->sendForm($form);
		});
	}

	public function confirm(Player $player, int $amount, float $price) : void{
		$form = new ModalForm(function(Player $player, $data) use ($amount, $price){
			if(!isset($data)) return;
			if(!$data) return;
			$this->createSellOrder($player, $amount, $price);
		});
		$form->setTitle("Confirm");
		$form->setContent("Create sell order for: \nItem: " . ItemUtils::toName($this->itemid) . "\nAmount: " . $amount . "\nPrice per item: " . $price . "\nWorth: " . $price * $amount . " coin");
		$form->setButton1("YES");
		$form->setButton2("NO");

		$player->sendForm($form);
	}

	public function createSellOrder(Player $player, int $amount, float $price){
		Await::f2c(function() use ($player, $amount, $price){
			$args = [
				"player" => $player->getName(),
				"price" => $price,
				"amount" => $amount,
				"filled" => 0,
				"itemID" => $this->itemid,
				"time" => time(),
				"isfilled" => false
			];
			$order = OrderDataHelper::fromSqlQueryData($args, OrderDataHelper::SELL);
			$ev = new PlayerCreateOrderEvent($player, $order);
			$ev->call();
			if($ev->isCancelled()) return;
			yield $this->getBazaar()->getProvider()->registerSell($order);
			$item = ItemUtils::toItem($this->itemid);
			$item->setCount($amount);
			ItemUtils::removeItem($player->getInventory(), $item);
			$player->sendMessage("Sell order created !");
		});
	}
}