<?php

namespace BlockHorizons\BlockGenerator\generators;

use BlockHorizons\BlockGenerator\biomes\CustomBiomeSelector;
use pocketmine\block\BlockTypeIds;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;

/**
 * BlockGenerator is improved default generator
 */
class UnoxGenerator extends CustomGenerator
{
    public CustomBiomeSelector $selector;

    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    public function generateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
        $baseX = $chunkX << 4;
        $baseZ = $chunkZ << 4;

        $chunk = $world->getChunk($chunkX, $chunkZ);

        for ($x = 0; $x < 16; $x++) {
            for ($z = 0; $z < 16; $z++) {
                $chunk->setBlockStateId($baseX + $x, 32, $baseZ + $z, BlockTypeIds::STONE);
            }
        }
    }

    public function populateChunk(ChunkManager $world, int $chunkX, int $chunkZ): void
    {
        // Unnecessary for now, but should accept $world
        // You can add code to populate chunks with structures or resources here
    }

    public function getName(): string
    {
        return "UnoxGenerator";
    }

    public function getSpawn(): Vector3
    {
        return new Vector3(0.5, 256, 0.5);
    }

    public function getSelector(): CustomBiomeSelector
    {
        return $this->selector;
    }
}

	