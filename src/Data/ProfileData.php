<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Data;

use Ccharz\NightscoutPhpApi\Enum\Unit;
use DateTimeImmutable;
use Exception;

class ProfileData extends ApiData
{
    /**
     * @param  ProfileEntryData[]  $profiles
     * @return void
     */
    public function __construct(
        public DateTimeImmutable $starts_at,
        public DateTimeImmutable $created_at,
        public string $default_profile,
        public Unit $units,
        public array $profiles = [],
        public ?string $id = null,
        public ?DateTimeImmutable $updated_at = null,
    ) {}

    public function defaultProfile(): ProfileEntryData
    {
        if (! $defaultProfile = current(
            array_filter(
                $this->profiles,
                fn (ProfileEntryData $profile): bool => $profile->name === $this->default_profile)
        )) {
            throw new Exception('Default profile not found');
        }

        return $defaultProfile;
    }

    public function toApiArray(): array
    {
        $profiles = [];

        $starts_at = $this->starts_at->format('Y-m-d\TH:i:s.000\Z');

        foreach ($this->profiles as $profile) {
            if (isset($profiles[$profile->name])) {
                throw new Exception('Multiple profiles with the same name "'.$profile->name.'"');
            }

            $profiles[$profile->name] = [
                ...$profile->toApiArray(),
                'startDate' => $starts_at,
            ];
        }

        return [
            'created_at' => $this->created_at->format('Y-m-d\TH:i:s.000\Z'),
            'defaultProfile' => $this->default_profile,
            'mills' => 0,
            'srvModified' => $this->updated_at instanceof DateTimeImmutable
                ? $this->updated_at->format('Uu')
                : floor(microtime(true) * 1000),
            'startDate' => $starts_at,
            'units' => $this->units->value,
            'store' => $profiles,
            ...(isset($this->id) ? ['_id' => $this->id] : []),
        ];
    }

    public static function fromApiArray(array $api_array): self
    {
        return new self(
            new DateTimeImmutable($api_array['startDate']),
            new DateTimeImmutable($api_array['created_at']),
            $api_array['defaultProfile'],
            Unit::from($api_array['units']),
            array_map(
                fn (string $profile): ProfileEntryData => ProfileEntryData::fromApiArray([
                    ...$api_array['store'][$profile],
                    'name' => $profile,
                ]),
                array_keys($api_array['store'])
            ),
            $api_array['_id'],
            new DateTimeImmutable('@'.($api_array['srvModified'] / 1000))
        );
    }
}
