<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Data;

use DateTimeImmutable;

class EntryData extends ApiData
{
    /**
     * @return void
     */
    public function __construct(
        public DateTimeImmutable $date,
        public DateTimeImmutable $sysTime,
        public string $type,
        public int $sgv,
        public int $delta,
        public string $direction,
        public int $noise,
        public int $rssi,
        public int $filtered,
        public int $unfiltered,
        public string $device,
        public ?string $id = null,

    ) {}

    public function toApiArray(): array
    {
        return [
            'dateString' => $this->date->format('Y-m-d\TH:i:s.000\Z'),
            ...(isset($this->id) ? ['_id' => $this->id] : []),
        ];
    }

    public static function fromApiArray(array $api_array): self
    {
        return new self(
            new DateTimeImmutable($api_array['dateString']),
            new DateTimeImmutable($api_array['sysTime']),
            $api_array['type'],
            $api_array['sgv'],
            $api_array['delta'],
            $api_array['direction'],
            $api_array['noise'],
            $api_array['rssi'],
            $api_array['filtered'],
            $api_array['unfiltered'],
            $api_array['device'],
            $api_array['_id'],
        );
    }
}
