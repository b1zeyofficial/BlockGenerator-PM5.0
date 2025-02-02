<?php

namespace BlockHorizons\BlockGenerator\noise;

use pocketmine\utils\Random;

class PerlinNoiseGenerator
{
    private array $noiseLevels = [];
    private int $levels;

    public function __construct(Random $random, int $levels, int $octaves = 4, float $persistence = 0.5)
    {
        $this->levels = $levels;

        $this->noiseLevels = [];

        for ($i = 0; $i < $levels; ++$i) {
            $this->noiseLevels[$i] = new SimplexF($random->nextBoundedInt(1000), $octaves, $persistence);
        }
    }

    public function getValue(float $x, float $z): float
    {
        $value = 0.0;
        $weight = 1.0;

        for ($i = 0; $i < $this->levels; ++$i) {
            $value += $this->noiseLevels[$i]->getValue($x * $weight, $z * $weight) / $weight;
            $weight /= 2.0;
        }

        return $value;
    }

    public function getRegion(array &$region, float $x, float $z, int $width, int $height, float $scaleX, float $scaleZ, float $persistence, float $lacunarity = 0.5): array
    {
        if ($region === null || count($region) < $width * $height) {
            $region = array_fill(0, $width * $height, 0.0);
        }

        $d0 = 1.0;
        $d1 = 1.0;

        for ($i = 0; $i < $this->levels; ++$i) {
            $this->noiseLevels[$i]->add(
                $region, $x, $z, $width, $height,
                $scaleX * $d0 * $d1, $scaleZ * $d0 * $d1,
                0.55 / $d1
            );

            $d0 *= $persistence;
            $d1 *= $lacunarity;
        }

        return $region;
    }
}
