<?php

namespace App\Model;

use JMS\Serializer\Annotation as Serializer;

class TransactionData
{
    #[Serializer\Type('string')]
    private string $bin;

    #[Serializer\Type('float')]
    private float $amount;

    #[Serializer\Type('string')]
    private string $currency;

    public function __construct(string $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getBin(): string
    {
        return $this->bin;
    }

    public function setBin(string $bin): void
    {
        $this->bin = $bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }
}
