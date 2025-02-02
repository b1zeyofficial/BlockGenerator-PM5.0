<?php
declare(strict_types=1);

namespace BlockHorizons\BlockGenerator\populator;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\utils\Random;
use pocketmine\world\ChunkManager;
use pocketmine\world\generator\populator\Populator;

class BedrockPopulator implements Populator
{

	private Block $bedrock;

	public function __construct()
	{
		$this->bedrock = VanillaBlocks::BEDROCK();
	}

	public function populate(ChunkManager $world, int $chunkX, int $chunkZ, Random $random): void
	{
		$chunk = $world->getChunk($chunkX, $chunkZ);

		for ($x = 0; $x < 16; $x++) {
			for ($z = 0; $z < 16; $z++) {
				$chunk->setBlockStateId($x, 0, $z, $this->bedrock->getStateId());
				for ($i = 1; $i < 5; $i++) {
					if ($random->nextBoundedInt($i) == 0)
						$chunk->setBlockStateId($x, $i, $z, $this->bedrock->getStateId());
				}
			}
		}
	}
}
