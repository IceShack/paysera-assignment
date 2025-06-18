<?php

namespace App\Service;

use App\Model\TransactionData;
use App\Provider\BinProviderInterface;
use App\Provider\ExchangeRateProviderInterface;

class CommissionCalculatorService
{
    private const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct(
        private readonly BinProviderInterface $binProvider,
        private readonly ExchangeRateProviderInterface $exchangeRateProvider,
    ) {
    }

    public function calculate(TransactionData $transactionData): float
    {
        $country = $this->binProvider->getCountryByBin($transactionData->getBin());
        $amount = $transactionData->getAmount();
        if ($transactionData->getCurrency() !== 'EUR') {
            $rate = $this->exchangeRateProvider->getExchangeRateByCurrency($transactionData->getCurrency());
            $amount /= $rate;
        }

        $commission = $amount * ($this->isEuCountry($country) ? 0.01 : 0.02);

        return ceil($commission * 100) / 100;
    }

    private function isEuCountry(string $country): bool
    {
        return in_array($country, self::EU_COUNTRIES);
    }
}
