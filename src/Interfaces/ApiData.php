<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Interfaces;

interface ApiData
{
    public function toArray(): array;

    public function toApiArray(): array;

    public static function fromApiArray(array $api_array): self;
}
