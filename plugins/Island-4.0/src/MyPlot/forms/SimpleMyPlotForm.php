<?php
declare(strict_types=1);

namespace MyPlot\forms;

use Closure;
use dktapps\pmforms\MenuForm;
use MyPlot\MyPlot;
use MyPlot\Plot;
use pocketmine\player\Player;

abstract class SimpleMyPlotForm extends MenuForm implements MyPlotForm{
	/** @var Plot|null $plot */
	protected ?Plot $plot;

	public function __construct(string $title, string $text, array $options, Closure $onSubmit, ?Closure $onClose = null){
		parent::__construct($title, $text, $options, $onSubmit,
			$onClose ?? function(Player $player) : void{
				$player->getServer()->dispatchCommand($player, MyPlot::getInstance()->getLanguage()->get("command.name"), true);
			}
		);
	}

	public function getPlot() : ?Plot{
		return $this->plot;
	}

	public function setPlot(?Plot $plot) : void{
		$this->plot = $plot;
	}
}