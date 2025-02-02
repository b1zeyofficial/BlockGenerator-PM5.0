<?php
declare(strict_types=1);

namespace BlockHorizons\BlockGenerator\noise;

use BlockHorizons\BlockGenerator\math\CustomRandom;
use JetBrains\PhpStorm\Pure;
use pocketmine\utils\Random;

class SimplexF extends PerlinF
{
    private static array $grad3 = [
        [1, 1, 0], [ -1, 1, 0], [ 1, -1, 0], [ -1, -1, 0],
        [ 1, 0, 1], [ -1, 0, 1], [ 1, 0, -1], [ -1, 0, -1],
        [ 0, 1, 1], [ 0, -1, 1], [ 0, 1, -1], [ 0, -1, -1]
    ];

    public function __construct(float $expansion, int $octaves, float $persistence, ?Random $random = null) {
        if (!$random || is_integer($random)) {
            $random = new CustomRandom($random ?? microtime(true));
        }

        parent::__construct($random, $octaves, $persistence, $expansion);
    }

    #[Pure]
    public function getValue(float $x, float $y): float
    {
        $d3 = 0.5 * (M_SQRT3 - 1.0);
        $d4 = ($x + $y) * $d3;
        $i = self::fastFloor($x + $d4);
        $j = self::fastFloor($y + $d4);
        $d5 = (3.0 - M_SQRT3) / 6.0;
        $d6 = (float)($i + $j) * $d5;
        $d7 = (float)$i - $d6;
        $d8 = (float)$j - $d6;
        $d9 = $x - $d7;
        $d10 = $y - $d8;

        if ($d9 > $d10) {
            $k = 1;
            $l = 0;
        } else {
            $k = 0;
            $l = 1;
        }

        $d11 = $d9 - (float)$k + $d5;
        $d12 = $d10 - (float)$l + $d5;
        $d13 = $d9 - 1.0 + 2.0 * $d5;
        $d14 = $d10 - 1.0 + 2.0 * $d5;
        $i1 = $i & 255;
        $j1 = $j & 255;
        $k1 = $this->permutations[$i1 + $this->permutations[$j1]] % 12;
        $l1 = $this->permutations[$i1 + $k + $this->permutations[$j1 + $l]] % 12;
        $i2 = $this->permutations[$i1 + 1 + $this->permutations[$j1 + 1]] % 12;
        $d15 = 0.5 - $d9 * $d9 - $d10 * $d10;
        $d0 = 0.0;

        if ($d15 < 0.0) {
            $d0 = 0.0;
        } else {
            $d15 = $d15 * $d15;
            $d0 = $d15 * $d15 * self::dot(self::$grad3[$k1], $d9, $d10);
        }

        $d16 = 0.5 - $d11 * $d11 - $d12 * $d12;

        if ($d16 < 0.0) {
            $d1 = 0.0;
        } else {
            $d16 = $d16 * $d16;
            $d1 = $d16 * $d16 * self::dot(self::$grad3[$l1], $d11, $d12);
        }

        $d17 = 0.5 - $d13 * $d13 - $d14 * $d14;

        if ($d17 < 0.0) {
            $d2 = 0.0;
        } else {
            $d17 = $d17 * $d17;
            $d2 = $d17 * $d17 * self::dot(self::$grad3[$i2], $d13, $d14);
        }

        return 70.0 * ($d0 + $d1 + $d2);
    }

    private static function fastFloor(float $value): int
    {
        return $value > 0.0 ? (int)$value : (int)$value - 1;
    }

    private static function dot(array $p_151604_0_, float $p_151604_1_, float $p_151604_3_): float
    {
        return (float)$p_151604_0_[0] * $p_151604_1_ + (float)$p_151604_0_[1] * $p_151604_3_;
    }
}
