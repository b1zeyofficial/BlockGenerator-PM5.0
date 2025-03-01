<?php
declare(strict_types=1);

namespace BlockHorizons\BlockGenerator\biomes\impl\iceplains;

use BlockHorizons\BlockGenerator\biomes\type\SnowyBiome;
use BlockHorizons\BlockGenerator\populator\TreePopulator;
use pocketmine\world\generator\object\TreeType;

class IcePlainsBiome extends SnowyBiome
{

	public function __construct()
	{
		parent::__construct();

		$tree = new TreePopulator(TreeType::SPRUCE());
		$tree->setBaseAmount(0);
		$tree->setRandomAmount(1);
		$this->addPopulator($tree);

		$this->setBaseHeight(0.125);
		$this->setHeightVariation(0.05);
	}

	public function getName(): string
	{
		return "Ice Plains";
	}

	 public function isFreezing() : bool {
	     return true;
	 }

}
