<?php

namespace App\Services;

class PeriodService
{
    private const ALL_YEAR = 'All year';
    private const PERIODS = [
        'Jan' => 1,
        'Feb' => 2,
        'Mar' => 3,
        'Apr' => 4,
        'May' => 5,
        'Jun' => 6,
        'Jul' => 7,
        'Aug' => 8,
        'Sep' => 9,
        'Oct' => 10,
        'Nov' => 11,
        'Dec' => 12,
    ];

    public function isInPeriod(string $periodCreature, string $periodSearch): bool
    {
        if ($periodSearch === self::ALL_YEAR) {
            return $periodCreature === self::ALL_YEAR;
        }

        if ($periodCreature === self::ALL_YEAR) {
            return true;
        }

        $periods = preg_split('/–|;|[\s]|-/', $periodCreature, -1, PREG_SPLIT_NO_EMPTY);
        $period = self::PERIODS[$periodSearch];

        for($i=0; $i < sizeof($periods); $i= $i+2) {
            $startPeriod = self::PERIODS[substr($periods[$i],0, 3)];

            if (sizeof($periods) === 1 || !isset($periods[$i + 1])) {
                return $startPeriod == $period;
            }

            $endPeriod = self::PERIODS[substr($periods[$i + 1],0, 3)];

            if (
                (
                    $startPeriod <= $endPeriod &&
                    $period >= $startPeriod &&
                    $period <= $endPeriod
                ) ||
                (
                    $startPeriod >= $endPeriod &&
                    ($period <= $endPeriod || $period >= $startPeriod)
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
