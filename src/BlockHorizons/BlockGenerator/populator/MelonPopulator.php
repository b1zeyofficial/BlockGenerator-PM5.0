<?php

namespace BlockHorizons\BlockGenerator\populator;

use BlockHorizons\BlockGenerator\populator\helper\EnsureCover;
use BlockHorizons\BlockGenerator\populator\helper\EnsureGrassBelow;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\utils\Random;

class MelonPopulator extends SurfaceBlockPopulator
{

	protected function canStay(int $x, int $y, int $z, ChunkManager $world): bool
	{
		return EnsureCover::ensureCover($x, $y, $z, $world) && EnsureGrassBelow::ensureGrassBelow($x, $y, $z, $world);
	}

	protected function getBlock(int $x, int $z, Random $random, ChunkManager $world): Block
	{
		return VanillaBlocks::MELON();
	}

}
