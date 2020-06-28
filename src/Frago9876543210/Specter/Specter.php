<?php

declare(strict_types=1);

namespace Frago9876543210\Specter;

use pocketmine\event\Listener;
use pocketmine\event\server\NetworkInterfaceRegisterEvent;
use pocketmine\network\mcpe\compression\ZlibCompressor;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\raklib\RakLibInterface;
use pocketmine\network\mcpe\raklib\RakLibPacketSender;
use pocketmine\player\Player;
use pocketmine\player\PlayerInfo;
use pocketmine\plugin\PluginBase;
use UnexpectedValueException;

class Specter extends PluginBase implements Listener{
	/** @var Specter */
	private static $instance;
	/** @var RakLibInterface|null */
	private $interface;
	/** @var int */
	private $offset = 0;

	protected function onEnable() : void{
		self::$instance = $this;
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public static function getInstance() : self{
		return self::$instance;
	}

	public function onRegisterInterface(NetworkInterfaceRegisterEvent $e) : void{
		$interface = $e->getInterface();
		if($interface instanceof RakLibInterface){
			$this->interface = $interface;
		}
	}

	/**
	 * @noinspection PhpUndefinedMethodInspection
	 * @noinspection PhpUndefinedFieldInspection
	 */
	public function createPlayer(PlayerInfo $playerInfo) : Player{
		if($this->interface === null){
			throw new UnexpectedValueException("RakLibInterface not found!");
		}

		$server = $this->getServer();
		$sessionManager = $server->getNetwork()->getSessionManager();
		$sessionId = 0x7fffffff + ++$this->offset;

		//Session is not created directly (This means that packets will not arrive at the specified address)
		$session = new NetworkSession(
			$server,
			$sessionManager,
			PacketPool::getInstance(),
			new RakLibPacketSender($sessionId, $this->interface),
			ZlibCompressor::getInstance(),
			"specter",
			0x7fff + $this->offset
		);

		$hackFn = function() use ($playerInfo) : Player{
			$this->onLoginSuccess();
			$this->info = $playerInfo;

			$this->onResourcePacksDone();
			$player = $this->getPlayer();
			$player->setViewDistance(32);

			$this->onSpawn();

			return $player;
		};

		return $hackFn->call($session);
	}
}