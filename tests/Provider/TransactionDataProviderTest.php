<?php

namespace App\Tests\Provider;

use App\Model\TransactionData;
use App\Provider\TransactionDataProvider;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TransactionDataProviderTest extends TestCase
{
    private SerializerInterface&MockObject $serializer;
    private TransactionDataProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->provider = new TransactionDataProvider($this->serializer);
    }

    public function testFromFile(): void
    {
        $expectedRows = [
            '{"bin":"45717360","amount":"100.00","currency":"EUR"}',
            '{"bin":"516793","amount":"50.00","currency":"USD"}',
        ];
        $this->serializer->method('deserialize')->willReturnCallback(function (string $data) use(&$expectedRows) {
            $expectedRow = array_shift($expectedRows);
            self::assertSame($expectedRow, $data);

            return $this->createMock(TransactionData::class);
        });
        $entries = $this->provider->fromFile(__DIR__ . '/input.txt');
        $result = [];
        foreach ($entries as $entry) {
            self::assertInstanceOf(TransactionData::class, $entry);
            $result[] = $entry;
        }
        self::assertCount(2, $result);

    }

}
