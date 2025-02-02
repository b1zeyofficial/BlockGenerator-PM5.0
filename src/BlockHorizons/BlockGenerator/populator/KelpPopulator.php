<?php

namespace BlockHorizons\BlockGenerator\populator;

use BlockHorizons\BlockGenerator\generators\BlockGenerator;
use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\world\format\Chunk;
use pocketmine\utils\Random;

// BIG TODO
class KelpPopulator extends SurfaceBlockPopulator
{

	protected function placeBlock(int $x, int $y, int $z, int $id, Chunk $world, Random $random): void
	{
		$age = $random->nextBoundedInt(24);
		for ($yy = $y; $yy < BlockGenerator::SEA_HEIGHT && $age > 0; $yy++) {
			///////////////////////////////////
			//$chunk->setBlock($x, $y, $z, ) //
			///////////////////////////////////
			/// Kelp block id is unknown :/
		}
	}

    protected function getHighestWorkableBlock(ChunkManager $world, int $x, int $z): int
    {
        $y = 0;
        //start at 254 because we add one afterwards
        for ($y = 254; $y >= 0; --$y) {
            $id = $world->getBlockAt($x, $y, $z);
            if (!PopulatorHelpers::isNonSolid($id) && !$id == VanillaBlocks::WATER()) {
                break;
            }
        }

        return $y === 0 ? -1 : ++$y;
    }

	protected function canStay(int $x, int $y, int $z, ChunkManager $world): bool
	{
		// TODO: Implement canStay() method.
	}

	protected function getBlock(int $x, int $z, Random $random, ChunkManager $world): Block
	{
		// TODO: Implement getBlock() method.
	}
}