<?php
declare(strict_types=1);

namespace BlockHorizons\BlockGenerator;

use BlockHorizons\BlockGenerator\generators\BlockGenerator;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\generator\GeneratorManager;
use pocketmine\world\WorldCreationOptions;

class Loader extends PluginBase implements Listener
{
	use SingletonTrait;

	public function onLoad(): void
	{
		self::setInstance($this);
		@rmdir($this->getServer()->getFilePath() . "worlds/world");

		GeneratorManager::getInstance()->addGenerator(BlockGenerator::class, "vanilla_overworld", fn() => null);

		$options = new WorldCreationOptions();
		$options->setSeed(mt_rand(PHP_INT_MIN, PHP_INT_MAX));
		$options->setGeneratorClass(BlockGenerator::class);

		$this->getServer()->getWorldManager()->generateWorld("world", $options, true);
		$this->getServer()->getWorldManager()->loadWorld("world");
	}

	public function onDisable(): void
	{
		@rmdir("worlds/world");
	}

}
