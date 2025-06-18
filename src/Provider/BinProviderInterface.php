<?php

namespace App\Provider;

interface BinProviderInterface
{
    public function getCountryByBin(string $bin): ?string;
}
