<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\Config\RectorConfig;
use Rector\Privatization\Rector\Class_\FinalizeTestCaseClassRector;
use Rector\TypeDeclaration\Rector\Class_\AddTestsVoidReturnTypeWhereNoReturnRector;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withSkip([
        __DIR__.'/**/vendor/*',
    ])
    ->withRules([
        AddTestsVoidReturnTypeWhereNoReturnRector::class,
        FinalizeTestCaseClassRector::class,
        InlineConstructorDefaultToPropertyRector::class,
    ])
    ->withPhpSets(php84: true)
    ->withPreparedSets(
        codeQuality: true,
        codingStyle: true,
        deadCode: true,
        earlyReturn: true,
        instanceOf: true,
        privatization: true,
        strictBooleans: true,
        typeDeclarations: true,
    )
    ->withImportNames();
