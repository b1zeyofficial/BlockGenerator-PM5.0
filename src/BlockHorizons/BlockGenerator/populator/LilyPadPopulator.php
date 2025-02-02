<?php

namespace BlockHorizons\BlockGenerator\populator;

use BlockHorizons\BlockGenerator\populator\helper\EnsureBelow;
use BlockHorizons\BlockGenerator\populator\helper\EnsureCover;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\utils\Random;

class LilyPadPopulator extends SurfaceBlockPopulator
{

	protected function canStay(int $x, int $y, int $z, ChunkManager $world): bool
	{
		return EnsureCover::ensureCover($x, $y, $z, $world) && EnsureBelow::ensureBelow($x, $y, $z, VanillaBlocks::WATER(), $world);
	}

	protected function getBlock(int $x, int $z, Random $random, ChunkManager $world): Block
	{
		return VanillaBlocks::LILY_PAD();
	}

}