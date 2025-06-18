<?php

namespace App\Provider;

use GuzzleHttp\Client;

class ExchangeRatesApiProvider implements ExchangeRateProviderInterface
{
    private const BASE_URL = 'https://api.exchangeratesapi.io/v1/latest';

    private ?Client $client = null;
    private ?array $exchangeRates = null;

    public function __construct(
        private readonly string $rateAccessKey,
    )
    {
    }


    public function getExchangeRateByCurrency(string $currency): float
    {
        return $this->getExchangeRates()[$currency];
    }

    private function getExchangeRates(): array
    {
        if (null === $this->exchangeRates) {
            $response = $this->getClient()->get('', [
                'query' => [
                    'access_key' => $this->rateAccessKey,
                ]
            ]);
            $data = json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);

            $this->exchangeRates = $data['rates'] ?? [];
        }

        return $this->exchangeRates;
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
