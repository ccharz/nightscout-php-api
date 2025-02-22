<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Data;

class ProfileEntryData extends ApiData
{
    /**
     * @param  TimeValueData[]  $basalRate
     * @param  TimeValueData[]  $carbRatio
     * @param  TimeValueData[]  $insulinSensibilityFactor
     * @param  TimeValueData[]  $targetBloodSugarHigh
     * @param  TimeValueData[]  $targetBloodSugarLow
     * @return void
     */
    public function __construct(
        public string $name,
        public float $carb_absorption_per_hour,
        public float $hours_of_insulin_action,
        public float $delay = 20,
        public string $timezone = 'UTC',
        public array $basalRate = [],
        public array $carbRatio = [],
        public array $insulinSensibilityFactor = [],
        public array $targetBloodSugarHigh = [],
        public array $targetBloodSugarLow = [],
    ) {}

    public function toApiArray(): array
    {
        return [
            'name' => $this->name,
            'carbs_hr' => $this->carb_absorption_per_hour,
            'dia' => $this->hours_of_insulin_action,
            'delay' => $this->delay,
            'timezone' => $this->timezone,
            'basal' => array_map(
                fn (TimeValueData $timeValueData): array => $timeValueData->toApiArray(),
                $this->basalRate,
            ),
            'carbratio' => array_map(
                fn (TimeValueData $timeValueData): array => $timeValueData->toApiArray(),
                $this->carbRatio,
            ),
            'sens' => array_map(
                fn (TimeValueData $timeValueData): array => $timeValueData->toApiArray(),
                $this->insulinSensibilityFactor,
            ),
            'target_high' => array_map(
                fn (TimeValueData $timeValueData): array => $timeValueData->toApiArray(),
                $this->targetBloodSugarHigh,
            ),
            'target_low' => array_map(
                fn (TimeValueData $timeValueData): array => $timeValueData->toApiArray(),
                $this->targetBloodSugarLow,
            ),
        ];
    }

    public static function fromApiArray(array $api_array): self
    {
        return new self(
            name: $api_array['name'],
            carb_absorption_per_hour: floatval($api_array['carbs_hr']),
            hours_of_insulin_action: floatval($api_array['dia']),
            delay: floatval($api_array['delay']),
            timezone: $api_array['timezone'],
            basalRate: array_map(
                fn (array $basalRate): TimeValueData => TimeValueData::fromApiArray($basalRate),
                $api_array['basal'],
            ),
            carbRatio: array_map(
                fn (array $carbRatio): TimeValueData => TimeValueData::fromApiArray($carbRatio),
                $api_array['carbratio'],
            ),
            insulinSensibilityFactor: array_map(
                fn (array $insulinSensibilityFactor): TimeValueData => TimeValueData::fromApiArray(
                    $insulinSensibilityFactor
                ),
                $api_array['sens'],
            ),
            targetBloodSugarHigh: array_map(
                fn (array $targetBloodSugarHigh): TimeValueData => TimeValueData::fromApiArray($targetBloodSugarHigh),
                $api_array['target_high'],
            ),
            targetBloodSugarLow: array_map(
                fn (array $targetBloodSugarLow): TimeValueData => TimeValueData::fromApiArray($targetBloodSugarLow),
                $api_array['target_low'],
            ),
        );
    }
}
