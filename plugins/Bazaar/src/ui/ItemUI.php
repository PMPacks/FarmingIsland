<?php
declare(strict_types=1);

namespace Bazaar\ui;

use Bazaar\utils\OrderDataHelper;
use Bazaar\provider\SqliteProvider;
use jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use SOFe\AwaitGenerator\Await;

class ItemUI extends BaseUI{

	private int $itemid;

	public function __construct(Player $player, int $itemid){
		$this->itemid = $itemid;
		parent::__construct($player);
	}

	public function execute(Player $player) : void{
		Await::f2c(function() use ($player){
			$data = yield $this->getBazaar()->getProvider()->asyncSelect(SqliteProvider::SELECT_BUY_ITEMID_SORT_PRICE, ["itemid" => $this->itemid]);
			if (empty($data)){
				$top_buy_price = 0;
			} else {
				$order = OrderDataHelper::formData($data[0], OrderDataHelper::BUY);
				$top_buy_price = $order->getPrice();
			}

			$data = yield $this->getBazaar()->getProvider()->asyncSelect(SqliteProvider::SELECT_SELL_ITEMID_SORT_PRICE, ["itemid" => $this->itemid]);
			if (empty($data)){
				$top_sell_price = 0;
			} else {
				$order = OrderDataHelper::formData($data[0], OrderDataHelper::SELL);
				$top_sell_price = $order->getPrice();
			}

			$form = new SimpleForm(function(Player $player, ?int $data){
				if (!isset($data)) return;
				switch($data){
					case 0:
						//TODO: Instance buy
						break;
					case 1:
						//TODO: Instance sell
						break;
					case 2:
						new BuyOrderUI($player, $this->itemid);
						break;
					case 3:
						new SellOrderUI($player, $this->itemid);
				}
			});
			$form->setTitle("ItemUI");
			$form->addButton("Instance buy" . "\n" . "Price: " . $top_sell_price);
			$form->addButton("Instance sell" . "\n" . "Price: " . $top_buy_price);
			$form->addButton("Create buy order");
			$form->addButton("Create sell order");

			$player->sendForm($form);
		});
	}
}