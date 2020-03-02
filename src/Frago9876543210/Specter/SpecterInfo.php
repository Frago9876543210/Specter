<?php

declare(strict_types=1);

namespace Frago9876543210\Specter;

use pocketmine\entity\Skin;
use pocketmine\player\PlayerInfo;
use pocketmine\utils\UUID;
use function str_repeat;

class SpecterInfo extends PlayerInfo{

	public function __construct(string $username, ?UUID $uuid = null, ?Skin $skin = null, ?string $locale = null, ?string $xuid = null, ?array $extraData = []){
		parent::__construct(
			$username,
			$uuid ?? UUID::fromRandom(),
			$skin ?? new Skin("Standard_Custom", str_repeat("\x80", 8192)),
			$locale ?? "en_US",
			$xuid ?? "",
			$extraData ?? []
		);
	}
}