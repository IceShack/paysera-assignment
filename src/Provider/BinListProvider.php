<?php

namespace App\Provider;

use App\Exception\BinProviderException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class BinListProvider implements BinProviderInterface
{
    private const BASE_URL = 'https://lookup.binlist.net/';

    private ?Client $client = null;

    public function getCountryByBin(string $bin): string
    {
        try {
            $response = $this->getClient()->request('GET', $bin);
            $data = json_decode($response->getBody()->getContents(), true);

            return $data['country']['alpha2'];

        } catch (GuzzleException $e) {
            throw new BinProviderException($e->getMessage(), 0, $e);
        }
    }

    private function getClient(): Client
    {
        if ($this->client === null) {
            $this->client = new Client([
                'base_uri' => self::BASE_URL,
            ]);
        }
        return $this->client;
    }
}
