<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle;

use muqsit\invmenu\InvMenuHandler;
use NgLamVN\GameHandle\ChatThin\CT_PacketHandler;
use NgLamVN\GameHandle\command\InitCommand;
use NgLamVN\GameHandle\InvCrashFix\IC_PacketHandler;
use NgLamVN\GameHandle\Sell\SellHandler;
use NgLamVN\GameHandle\task\InitTask;
use pocketmine\entity\Skin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use NgLamVN\GameHandle\PlayerStat\PlayerStatManager;
use Exception;

class Core extends PluginBase
{
	public const VERSION = "0.1-indev";
	public const BUILD_NUMBER = "1";
	public const INDEV = true;

	use SingletonTrait;
    /** @var int[] */
    public array $afktime = [];
    /** @var PlayerStatManager $pstatmanager */
    private PlayerStatManager $pstatmanager;
    /** @var Skin[] */
    public array $skin = [];
    /** @var SellHandler $sell */
    public SellHandler $sell;

    public function onEnable(): void
    {
    	try
		{
			if(!InvMenuHandler::isRegistered())
			{
				InvMenuHandler::register($this);
			}

			$plmanager = $this->getServer()->getPluginManager();
			$plmanager->registerEvents(new EventListener($this), $this);
			$plmanager->registerEvents(new IC_PacketHandler(), $this);
			$plmanager->registerEvents(new CT_PacketHandler(), $this);
			new InitCommand($this);
			new InitTask($this);
			$this->pstatmanager = new PlayerStatManager();
			$this->sell = new SellHandler($this);
		}
        catch(Exception $e)
		{
			$this->getLogger()->logException($e);
			$this->getLogger()->error("An error caused by GameHandle, force disable this plugin...");
			$this->getServer()->getPluginManager()->disablePlugin($this);
		}
    }
    public function onDisable(): void
    {
    	//NOOPPPEEEE
    }

    /*public function CreateIsland (Player $player)
    {
        Server::getInstance()->dispatchCommand($player, "is auto");
        Server::getInstance()->dispatchCommand($player, "is claim");
        $player->sendMessage("Lest Play !");
    }*/

    public function getPlayerStatManager(): PlayerStatManager
    {
        return $this->pstatmanager;
    }

    public function getSellHandler(): SellHandler
	{
		return $this->sell;
	}
}
