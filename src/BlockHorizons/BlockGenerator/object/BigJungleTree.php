<?php

namespace BlockHorizons\BlockGenerator\object;

use pocketmine\block\Block;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class BigJungleTree extends HugeTree
{

	public function __construct(int $baseHeightIn, int $extraRandomHeight, Block $woodMetadata, Block $leavesMetadata)
	{
		parent::__construct($baseHeightIn, $extraRandomHeight, $woodMetadata, $leavesMetadata);
	}

	public function generate(ChunkManager $world, Random $rand, Vector3 $position): bool
	{
		$height = $this->getHeight($rand);

		if (!$this->ensureGrowable($world, $rand, $position, $height)) {
			return false;
		} else {
			$this->createCrown($world, $position->up($height), 2);

			for ($j = (int)$position->getY() + $height - 2 - $rand->nextBoundedInt(4); $j > $position->getY() + $height / 2; $j -= 2 + $rand->nextBoundedInt(4)) {
				$f = $rand->nextFloat() * ((float)M_PI * 2.0);
				$k = (int)($position->getX() + (0.5 + cos($f) * 4.0));
				$l = (int)($position->getZ() + (0.5 + sin($f) * 4.0));

				for ($i1 = 0; $i1 < 5; ++$i1) {
					$k = (int)($position->getX() + (1.5 + cos($f) * (float)$i1));
					$l = (int)($position->getZ() + (1.5 + sin($f) * (float)$i1));
					$this->setBlockAndNotifyAdequately($world, new Vector3($k, $j - 3 + $i1 / 2, $l), $this->woodMetadata);
				}

				$j2 = 1 + $rand->nextBoundedInt(2);
				$j1 = $j;

				for ($k1 = $j - $j2; $k1 <= $j1; ++$k1) {
					$l1 = $k1 - $j1;
					$this->growLeavesLayer($world, new Vector3($k, $k1, $l), 1 - $l1);
				}
			}

			for ($i2 = 0; $i2 < $height; ++$i2) {
				$blockpos = $position->up($i2);

				if ($this->canOverride($world->getBlockAt($blockpos->x, $blockpos->y, $blockpos->z))) {
					$this->setBlockAndNotifyAdequately($world, $blockpos, $this->woodMetadata);

					if ($i2 > 0) {
						$this->placeVine($world, $rand, $blockpos->west(), 8);
						$this->placeVine($world, $rand, $blockpos->north(), 1);
					}
				}

				if ($i2 < $height - 1) {
					$blockpos1 = $blockpos->east();

					if ($this->canOverride($world->getBlockAt((int)$blockpos1->x, (int)$blockpos1->y, (int)$blockpos1->z))) {
						$this->setBlockAndNotifyAdequately($world, $blockpos1, $this->woodMetadata);

						if ($i2 > 0) {
							$this->placeVine($world, $rand, $blockpos1->east(), 2);
							$this->placeVine($world, $rand, $blockpos1->north(), 1);
						}
					}

					$blockpos2 = $blockpos->south()->east();

					if ($this->canOverride($world->getBlockAt((int)$blockpos2->x, (int)$blockpos2->y, (int)$blockpos2->z))) {
						$this->setBlockAndNotifyAdequately($world, $blockpos2, $this->woodMetadata);

						if ($i2 > 0) {
							$this->placeVine($world, $rand, $blockpos2->east(), 2);
							$this->placeVine($world, $rand, $blockpos2->south(), 4);
						}
					}

					$blockpos3 = $blockpos->south();

					if ($this->canOverride($world->getBlockAt((int)$blockpos3->x, (int)$blockpos3->y, (int)$blockpos3->z))) {
						$this->setBlockAndNotifyAdequately($world, $blockpos3, $this->woodMetadata);

						if ($i2 > 0) {
							$this->placeVine($world, $rand, $blockpos3->west(), 8);
							$this->placeVine($world, $rand, $blockpos3->south(), 4);
						}
					}
				}
			}

			return true;
		}
	}

	private function createCrown(ChunkManager $world, Vector3 $pos, int $i1): void
	{
		for ($j = -2; $j <= 0; ++$j) {
			$this->growLeavesLayerStrict($world, $pos->up($j), $i1 + 1 - $j);
		}
	}

	private function placeVine(ChunkManager $world, Random $random, Vector3 $pos): void
	{
		if ($random->nextBoundedInt(3) > 0 && $world->getBlockAt((int)$pos->x, (int)$pos->y, (int)$pos->z) === 0) {
            $this->setBlockAndNotifyAdequately($world, $pos, VanillaBlocks::VINES());
		}
	}

}
