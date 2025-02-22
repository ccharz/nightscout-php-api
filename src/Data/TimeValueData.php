<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Data;

use DateInterval;

class TimeValueData extends ApiData
{
    /**
     * @param  \Ccharz\NightscoutPhpApi\Data\Profile[]  $profiles
     * @return void
     */
    public function __construct(
        public readonly DateInterval $time,
        public readonly float $value
    ) {}

    public function toApiArray(): array
    {
        return [
            'time' => $this->time->format('%H:%I'),
            'value' => $this->value,
            'timeAsSeconds' => 0,
        ];
    }

    public static function fromApiArray(array $api_array): self
    {
        return new TimeValueData(
            new DateInterval('P0000-00-00T'.$api_array['time'].':00'),
            floatval($api_array['value'])
        );
    }
}
