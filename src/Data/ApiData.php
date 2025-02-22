<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Data;

use Ccharz\NightscoutPhpApi\Interfaces\ApiData as ApiDataInterface;

abstract class ApiData implements ApiDataInterface
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
