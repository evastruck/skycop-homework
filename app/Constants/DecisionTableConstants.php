<?php

namespace App\Constants;

class DecisionTableConstants
{
    public const CANCEL_CONDITION_KEY = 'cancelDays';
    public const DELAY_CONDITION_KEY = 'delayHours';
    public const EU_CONDITION_KEY = 'eu';
    public const CLAIMABLE_KEY = 'claimable';

    public const RULES = [
        [
            self::EU_CONDITION_KEY => true,
            self::CANCEL_CONDITION_KEY => '<=14',
            self::CLAIMABLE_KEY => true,
        ],
        [
            self::EU_CONDITION_KEY => true,
            self::DELAY_CONDITION_KEY => '>=3',
            self::CLAIMABLE_KEY => true,
        ],
        [
            self::EU_CONDITION_KEY => true,
            self::CANCEL_CONDITION_KEY => '>14',
            self::CLAIMABLE_KEY => false,
        ],
        [
            self::EU_CONDITION_KEY => true,
            self::DELAY_CONDITION_KEY => '<3',
            self::CLAIMABLE_KEY => false,
        ],
        [
            self::EU_CONDITION_KEY => false,
            self::CLAIMABLE_KEY => false,
        ]
    ];
}
