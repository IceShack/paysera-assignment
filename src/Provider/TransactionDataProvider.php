<?php

namespace App\Provider;

use App\Model\TransactionData;
use JMS\Serializer\SerializerInterface;

class TransactionDataProvider
{
    public function __construct(
        private readonly SerializerInterface $serializer,
    ) {
    }

    public function fromFile(string $fileName): \Generator
    {
        $fh = fopen($fileName, 'rb');
        while ($row = fgets($fh)) {
            yield $this->serializer->deserialize(trim($row), TransactionData::class, 'json');
        }
    }
}
