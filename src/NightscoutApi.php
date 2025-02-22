<?php

declare(strict_types=1);

namespace Ccharz\NightscoutPhpApi;

use Ccharz\NightscoutPhpApi\Data\EntryData;
use Ccharz\NightscoutPhpApi\Data\ProfileData;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NightscoutApi
{
    private string $bearerToken;

    public function __construct(protected readonly HttpClientInterface $client, protected readonly string $url, protected readonly string $token, private readonly ?string $store_response_at = null) {}

    protected function getBearerToken(): string
    {
        if (! isset($this->bearerToken)) {
            $response = $this->client->request(
                'GET',
                $this->url.'/api/v2/authorization/request/'.$this->token
            );

            $this->bearerToken = $response->toArray()['token'];
        }

        return $this->bearerToken;
    }

    protected function request(string $endpoint, string $method = 'GET', array $options = []): array
    {
        $response = $this->client->request(
            $method,
            $this->url.'/api'.$endpoint,
            [
                'auth_bearer' => $this->getBearerToken(),
                ...$options,
            ]
        );

        if ($this->store_response_at !== null) {
            file_put_contents($this->store_response_at, $response->getContent());
        }

        return $response->toArray();
    }

    public function test(): array
    {
        return $this->request(
            '/v1/experiments/test'
        );
    }

    /**
     * @return ProfileData[]
     */
    public function getProfiles(array $query = []): array
    {
        $result = $this->request(
            '/v1/profile',
            'GET',
            [
                'query' => $query,
            ],
        );

        return array_map(
            fn (array $api_data): ProfileData => ProfileData::fromApiArray($api_data),
            $result
        );
    }

    public function storeProfile(ProfileData $data): void
    {
        $this->request(
            '/v1/profile',
            'PUT',
            [
                'json' => $data->toApiArray(),
            ]
        );
    }

    public function deleteProfile(string $id): void
    {
        $this->request(
            '/v1/profile/'.$id,
            'DELETE'
        );
    }

    public function getEntries(?string $spec = null, array $query = []): array
    {
        $result = $this->request(
            '/v1/entries'.($spec !== null ? '/'.$spec : '').'.json',
            'GET',
            [
                'query' => $query,
            ],
        );

        return array_map(
            fn (array $api_data): EntryData => EntryData::fromApiArray($api_data),
            $result
        );
    }
}
