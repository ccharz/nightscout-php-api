<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi\Tests;

use Ccharz\NightscoutPhpApi\NightscoutApi;
use PHPUnit\Framework\TestCase;
use Spatie\Snapshots\MatchesSnapshots;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class NightscoutApiTest extends TestCase
{
    use MatchesSnapshots;

    private function getTestDataPath(string $file): string
    {
        return __DIR__.'/__data__/'.$file;
    }

    private function getTestData(string $file): string
    {
        return file_get_contents($this->getTestDataPath($file));
    }

    private function mockResponse(string $response_file): MockResponse
    {
        return new MockResponse($this->getTestData('response_'.$response_file.'.json'));
    }

    /**
     * @param  callable|callable[]|ResponseInterface|ResponseInterface[]|iterable|null  $responseFactory
     */
    private function getApi(callable|iterable|ResponseInterface|null $responseFactory = null): NightscoutApi
    {
        return new NightscoutApi(
            new MockHttpClient($responseFactory),
            'https://nightscout.test',
            'test-code'
        );
    }

    public function test_auth(): void
    {
        $api = $this->getApi([
            $this->mockResponse('v2_authorization_request'),
            $this->mockResponse('v1_experiments_test'),
        ]);

        $this->assertSame(['status' => 'ok'], $api->test());
    }

    public function test_get_profiles(): void
    {
        $api = $this->getApi([
            $this->mockResponse('v2_authorization_request'),
            $this->mockResponse('v1_profile'),
        ]);

        $profiles = $api->getProfiles();

        $this->assertMatchesJsonSnapshot($profiles);
        $this->assertMatchesJsonSnapshot($profiles[0]->toApiArray());
    }

    public function test_get_entries(): void
    {
        $api = $this->getApi([
            $this->mockResponse('v2_authorization_request'),
            $this->mockResponse('v1_entries'),
        ]);

        $entries = $api->getEntries();

        $this->assertMatchesJsonSnapshot($entries);
    }

    public function test_get_spec_entries(): void
    {
        $api = $this->getApi([
            $this->mockResponse('v2_authorization_request'),
            $this->mockResponse('v1_sgv_entries'),
        ]);

        $entries = $api->getEntries('sgv');

        $this->assertMatchesJsonSnapshot($entries);
    }
}
