<?php

namespace App\Provider;

interface ExchangeRateProviderInterface
{
    public function getExchangeRateByCurrency(string $currency): float;
}
