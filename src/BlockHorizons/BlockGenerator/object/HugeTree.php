<?php

namespace BlockHorizons\BlockGenerator\object;

use pocketmine\block\Block;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

abstract class HugeTree extends CustomTree
{

	/**
	 * Sets the metadata for the wood blocks used
	 */
	protected $woodMetadata;

	/**
	 * Sets the metadata for the leaves used in huge trees
	 */
	protected $leavesMetadata;
	protected $extraRandomHeight;

	public function __construct(int $treeHeightIn, int $extraRandomHeightIn, Block $woodMetadataIn, Block $leavesMetadataIn)
	{
		$this->treeHeight = $treeHeightIn;
		$this->extraRandomHeight = $extraRandomHeightIn;
		$this->woodMetadata = $woodMetadataIn;
		$this->leavesMetadata = $leavesMetadataIn;
	}

	/*
	 * Calculates the height based on this trees base height and its extra random height
	 */
	protected function getHeight(Random $rand)
	{
		$i = $rand->nextBoundedInt(3) + $this->treeHeight;

		if ($this->extraRandomHeight > 1) {
			$i += $rand->nextBoundedInt($this->extraRandomHeight);
		}

		return $i;
	}

	/*
	 * returns whether or not there is space for a tree to grow at a certain position
	 */

	protected function ensureGrowable(ChunkManager $worldIn, Random $rand, Vector3 $treePos, int $p_175929_4_): bool
	{
		return $this->isSpaceAt($worldIn, $treePos, $p_175929_4_) && $this->ensureDirtsUnderneath($treePos, $worldIn);
	}

	/*
	 * returns whether or not there is dirt underneath the block where the tree will be grown.
	 * It also generates dirt around the block in a 2x2 square if there is dirt underneath the blockpos.
	 */

	private function isSpaceAt(ChunkManager $worldIn, Vector3 $leavesPos, int $height): bool
	{
		$flag = true;

		if ($leavesPos->getY() >= 1 && $leavesPos->getY() + $height + 1 <= 256) {
			for ($i = 0; $i <= 1 + $height; ++$i) {
				$j = 2;

				if ($i == 0) {
					$j = 1;
				} elseif ($i >= 1 + $height - 2) {
					$j = 2;
				}

				for ($k = -$j; $k <= $j && $flag; ++$k) {
					for ($l = -$j; $l <= $j && $flag; ++$l) {
						$blockPos = $leavesPos->add($k, $i, $l);
						if ($leavesPos->getY() + $i < 0 || $leavesPos->getY() + $i >= 256 || !$this->canOverride($worldIn->getBlockAt((int)$blockPos->x, (int)$blockPos->y, (int)$blockPos->z))) {
							$flag = false;
						}
					}
				}
			}

			return $flag;
		} else {
			return false;
		}
	}

	protected function ensureDirtsUnderneath(Vector3 $pos, ChunkManager $worldIn): bool
	{
		$blockpos = $pos->down();
		$block = $worldIn->getBlockAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

		if (($block === BlockTypeIds::GRASS || $block === BlockTypeIds::DIRT) && $pos->getY() >= 2) {
			$this->setDirtAt($worldIn, $blockpos);
			$this->setDirtAt($worldIn, $blockpos->east());
			$this->setDirtAt($worldIn, $blockpos->south());
			$this->setDirtAt($worldIn, $blockpos->south()->east());
			return true;
		} else {
			return false;
		}
	}

	/*
	 * returns whether or not a tree can grow at a specific position.
	 * If it can, it generates surrounding dirt underneath.
	 */

	public function setDirtAt(ChunkManager $level, Vector3 $pos): void
	{
		$level->setBlockAt($pos->x, $pos->y, $pos->z, VanillaBlocks::DIRT());
	}

	/*
	 * grow leaves in a circle with the outsides being within the circle
	 */

	protected function growLeavesLayerStrict(ChunkManager $worldIn, Vector3 $layerCenter, int $width): void
	{
		$i = $width * $width;

		for ($j = -$width; $j <= $width + 1; ++$j) {
			for ($k = -$width; $k <= $width + 1; ++$k) {
				$l = $j - 1;
				$i1 = $k - 1;

				if ($j * $j + $k * $k <= $i || $l * $l + $i1 * $i1 <= $i || $j * $j + $i1 * $i1 <= $i || $l * $l + $k * $k <= $i) {
					$blockpos = $layerCenter->add($j, 0, $k);
					$id = $worldIn->getBlockAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

                    if ($id === BlockTypeIds::AIR ||
                        $id === VanillaBlocks::OAK_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::BIRCH_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::SPRUCE_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::JUNGLE_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::ACACIA_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::DARK_OAK_LEAVES()->getTypeId()) {

                        $this->setBlockAndNotifyAdequately($worldIn, $blockpos, $this->leavesMetadata);
                    }
				}
			}
		}
	}

	/*
	 * grow leaves in a circle
	 */

    public function setBlockAndNotifyAdequately(ChunkManager $level, Vector3 $pos, Block $block): void
    {
        $level->setBlockAt($pos->x, $pos->y, $pos->z, $block);
        $level->setBlockAt($pos->x, $pos->y, $pos->z, $block);
    }

	protected function growLeavesLayer(ChunkManager $worldIn, Vector3 $layerCenter, int $width)
	{
		$i = $width * $width;

		for ($j = -$width; $j <= $width; ++$j) {
			for ($k = -$width; $k <= $width; ++$k) {
				if ($j * $j + $k * $k <= $i) {
					$blockpos = $layerCenter->add($j, 0, $k);
					$id = $worldIn->getBlockAt((int)$blockpos->x, (int)$blockpos->y, (int)$blockpos->z);

					if ($id === BlockTypeIds::AIR ||
                        $id === VanillaBlocks::OAK_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::BIRCH_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::SPRUCE_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::JUNGLE_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::ACACIA_LEAVES()->getTypeId() ||
                        $id === VanillaBlocks::DARK_OAK_LEAVES()->getTypeId()) {
						$this->setBlockAndNotifyAdequately($worldIn, $blockpos, $this->leavesMetadata);
					}
				}
			}
		}
	}

}

