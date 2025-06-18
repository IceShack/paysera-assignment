<?php

namespace App\Tests\Service;

use App\Model\TransactionData;
use App\Provider\BinProviderInterface;
use App\Provider\ExchangeRateProviderInterface;
use App\Service\CommissionCalculatorService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionCalculatorServiceTest extends TestCase
{

    private BinProviderInterface&MockObject $binProvider;
    private ExchangeRateProviderInterface&MockObject $exchangeRateProvider;
    private CommissionCalculatorService $commissionCalculatorService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->binProvider = $this->createMock(BinProviderInterface::class);
        $this->exchangeRateProvider = $this->createMock(ExchangeRateProviderInterface::class);

        $this->commissionCalculatorService = new CommissionCalculatorService(
            $this->binProvider,
            $this->exchangeRateProvider,
        );
    }

    /**
     * @dataProvider calculateCommissionDataProvider
     */
    public function testCalculateCommission(string $bin, float $amount, string $currency, float $rate, string $country, float $expectedResult): void
    {
        $data = new TransactionData($bin, $amount, $currency);

        $this->binProvider->method('getCountryByBin')->with($bin)->willReturn($country);
        $this->exchangeRateProvider->method('getExchangeRateByCurrency')->with($currency)->willReturn($rate);

        $result = $this->commissionCalculatorService->calculate($data);
        self::assertSame($expectedResult, $result);
    }

    public static function calculateCommissionDataProvider(): array
    {
        return [
            ['45717360', 100.00, 'EUR', 0.0, 'DE', 1.00],
            ['516793', 50.00, 'USD', 1.150424, 'US', 0.87],
            ['45417360', 10000.00, 'JPY', 166.676255, 'JP', 1.20],
            ['41417360', 130.00, 'USD', 1.150424, 'JP', 2.27],
            ['4745030', 2000.00, 'GBP', 0.85507, 'UK', 46.78],
        ];
    }
}
