<?php

namespace BlockHorizons\BlockGenerator\object\mushroom;

use BlockHorizons\BlockGenerator\object\BasicGenerator;
use pocketmine\block\BlockTypeIds;
use pocketmine\block\VanillaBlocks;
use pocketmine\world\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class BigMushroom extends BasicGenerator
{

	const NORTH_WEST = 1;
	const NORTH = 2;
	const NORTH_EAST = 3;
	const WEST = 4;
	const CENTER = 5;
	const EAST = 6;
	const SOUTH_WEST = 7;
	const SOUTH = 8;
	const SOUTH_EAST = 9;
	const STEM = 10;
	const ALL_INSIDE = 0;
	const ALL_OUTSIDE = 14;
	const ALL_STEM = 15;

	const BROWN = 0;
	const RED = 1;

	private $mushroomType;

	public function __construct(?int $mushroomType = null)
	{
		$this->mushroomType = $mushroomType ?? -1;
	}

	public function generate(ChunkManager $level, Random $rand, Vector3 $position): bool
	{
		$block = $this->mushroomType;
		if ($block < 0) {
			$block = $rand->nextBoolean() ? self::RED : self::BROWN;
		}

        $mushroom = $block === 0 ? VanillaBlocks::BROWN_MUSHROOM() : VanillaBlocks::RED_MUSHROOM();

        $level->setBlockAt($position->getX(), $position->getY(), $position->getZ(), $mushroom);

		$i = $rand->nextBoundedInt(3) + 4;

		if ($rand->nextBoundedInt(12) === 0) {
			$i *= 2;
		}

		$flag = true;

		if ($position->getY() >= 1 && $position->getY() + $i + 1 < 256) {
			for ($j = $position->getFloorY(); $j <= $position->getY() + 1 + $i; ++$j) {
				$k = 3;

				if ($j <= $position->getY() + 3) {
					$k = 0;
				}

                for ($l = $position->getFloorX() - $k; $l <= $position->getX() + $k && $flag; ++$l) {
                    for ($i1 = $position->getFloorZ() - $k; $i1 <= $position->getZ() + $k && $flag; ++$i1) {
                        if ($j >= 0 && $j < 256) {
                            $pos = new Vector3($l, $j, $i1);

							$material = $level->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());

                            if ($material !== BlockTypeIds::AIR &&
                                $material !== VanillaBlocks::ACACIA_LEAVES() &&
                                $material !== VanillaBlocks::BIRCH_LEAVES() &&
                                $material !== VanillaBlocks::DARK_OAK_LEAVES() &&
                                $material !== VanillaBlocks::JUNGLE_LEAVES() &&
                                $material !== VanillaBlocks::OAK_LEAVES() &&
                                $material !== VanillaBlocks::SPRUCE_LEAVES()) {
                                $flag = false;
                            }
						} else {
							$flag = false;
						}
					}
				}
			}

			if (!$flag) {
				return false;
			} else {
				$pos2 = $position->down();
				$block1 = $level->getBlockAt($pos2->getFloorX(), $pos2->getFloorY(), $pos2->getFloorZ());

				if ($block1 !== BlockTypeIds::DIRT && $block1 !== BlockTypeIds::GRASS && $block1 !== BlockTypeIds::MYCELIUM) {
					return false;
				} else {
					$k2 = $position->getFloorY() + $i;

					if ($block === self::RED) {
						$k2 = $position->getFloorY() + $i - 3;
					}

					for ($l2 = $k2; $l2 <= $position->getY() + $i; ++$l2) {
						$j3 = 1;

						if ($l2 < $position->getY() + $i) {
							++$j3;
						}

						if ($block === self::BROWN) {
							$j3 = 3;
						}

						$k3 = $position->getFloorX() - $j3;
						$l3 = $position->getFloorX() + $j3;
						$j1 = $position->getFloorZ() - $j3;
						$k1 = $position->getFloorZ() + $j3;

						for ($l1 = $k3; $l1 <= $l3; ++$l1) {
							for ($i2 = $j1; $i2 <= $k1; ++$i2) {
								$j2 = 5;

								if ($l1 === $k3) {
									--$j2;
								} elseif ($l1 === $l3) {
									++$j2;
								}

								if ($i2 === $j1) {
									$j2 -= 3;
								} elseif ($i2 === $k1) {
									$j2 += 3;
								}

								$meta = $j2;

								if ($block === self::BROWN || $l2 < $position->getY() + $i) {
									if (($l1 == $k3 || $l1 === $l3) && ($i2 === $j1 || $i2 === $k1)) {
										continue;
									}

									if ($l1 === $position->getX() - ($j3 - 1) && $i2 === $j1) {
										$meta = self::NORTH_WEST;
									}

									if ($l1 === $k3 && $i2 === $position->getZ() - ($j3 - 1)) {
										$meta = self::NORTH_WEST;
									}

									if ($l1 === $position->getX() + ($j3 - 1) && $i2 === $j1) {
										$meta = self::NORTH_EAST;
									}

									if ($l1 === $l3 && $i2 === $position->getZ() - ($j3 - 1)) {
										$meta = self::NORTH_EAST;
									}

									if ($l1 === $position->getX() - ($j3 - 1) && $i2 === $k1) {
										$meta = self::SOUTH_WEST;
									}

									if ($l1 === $k3 && $i2 === $position->getZ() + ($j3 - 1)) {
										$meta = self::SOUTH_WEST;
									}

									if ($l1 === $position->getX() + ($j3 - 1) && $i2 === $k1) {
										$meta = self::SOUTH_EAST;
									}

									if ($l1 === $l3 && $i2 === $position->getZ() + ($j3 - 1)) {
										$meta = self::SOUTH_EAST;
									}
								}

								if ($meta === self::CENTER && $l2 < $position->getY() + $i) {
									$meta = self::ALL_INSIDE;
								}

								if ($position->getY() >= $position->getY() + $i - 1 || $meta !== self::ALL_INSIDE) {
									$blockPos = new Vector3($l1, $l2, $i2);

									if (!($level->getBlockAt($blockPos->x, $blockPos->y, $blockPos->z))->isSolid()) {
										$this->setBlockAndNotifyAdequately($level, $blockPos, $mushroom);
									}
								}
							}
						}
					}

                    for ($i3 = 0; $i3 < $i; ++$i3) {
                        $pos = $position->up($i3);
                        $blockAtPos = $level->getBlockAt($pos->getFloorX(), $pos->getFloorY(), $pos->getFloorZ());

                        if (!$blockAtPos->isSolid()) {
                            $this->setBlockAndNotifyAdequately($level, $pos, $mushroom);
                        }
                    }

					return true;
				}
			}
		} else {
			return false;
		}
	}
}
