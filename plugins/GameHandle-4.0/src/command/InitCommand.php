<?php

declare(strict_types=1);

namespace NgLamVN\GameHandle\command;

use NgLamVN\GameHandle\Core;

class InitCommand{
	public function __construct(Core $plugin){
		$cmd = $plugin->getServer()->getCommandMap();

		//Legacy Command Format
		$cmd->register("heal", new Heal($plugin));
		$cmd->register("sell", new Sell($plugin));
		$cmd->register("tpall", new TpAll($plugin));
		$cmd->register("sudo", new Sudo($plugin));
		$cmd->register("smartmine", new SmartMine($plugin));
		$cmd->register("tutorial", new Tutorial($plugin));
		$cmd->register("servercheck", new ServerCheck($plugin));
		$cmd->register("mute", new Mute($plugin));
		$cmd->register("unmute", new UnMute($plugin));
		$cmd->register("unfreeze", new UnFreeze($plugin));
		$cmd->register("notp", new NoTP($plugin));
		$cmd->register("icgive", new IcGive($plugin)); //TODO: Slove NBT problem
		$cmd->register("pos", new PlayerInfo($plugin));

		//New Command Format
		$cmd->register("cgive", new CGive($plugin, "cgive"));
		$cmd->register("crash", new Crash($plugin, "crash"));
		$cmd->register("dupe", new Dupe($plugin, "dupe"));
		$cmd->register("feed", new Feed($plugin, "feed"));
		$cmd->register("fiversion", new FiVersion($plugin, "fiversion"));
		$cmd->register("fly", new Fly($plugin, "fly"));
		$cmd->register("freeze", new Freeze($plugin, "freeze"));
		$cmd->register("gm0", new Gm0($plugin, "gm0"));
		$cmd->register("gm1", new Gm1($plugin, "gm1"));
		$cmd->register("gm2", new Gm2($plugin, "gm2"));
		$cmd->register("gm3", new Gm3($plugin, "gm3"));
	}
}
