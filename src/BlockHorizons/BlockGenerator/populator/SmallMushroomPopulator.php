<?php

namespace BlockHorizons\BlockGenerator\populator;

use BlockHorizons\BlockGenerator\populator\helper\EnsureCover;
use BlockHorizons\BlockGenerator\populator\helper\EnsureGrassBelow;
use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\utils\Random;

class SmallMushroomPopulator extends SurfaceBlockPopulator
{

    protected function canStay(int $x, int $y, int $z, ChunkManager $world): bool
    {
        return EnsureCover::ensureCover($x, $y, $z, $world) && EnsureGrassBelow::ensureGrassBelow($x, $y, $z, $world);
    }

    protected function getBlock(int $x, int $z, Random $random, ChunkManager $world): Block
    {
        return VanillaBlocks::BROWN_MUSHROOM();
    }
}
