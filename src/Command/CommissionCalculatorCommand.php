<?php

namespace App\Command;

use App\Provider\TransactionDataProvider;
use App\Service\CommissionCalculatorService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'commission-calculator')]
class CommissionCalculatorCommand extends Command
{
    public function __construct(
        private readonly TransactionDataProvider $transactionDataProvider,
        private readonly CommissionCalculatorService $commissionCalculatorService,
    ) {
        parent::__construct();
    }

    private const FILE_ARG = 'file';

    protected function configure(): void
    {
        $this->setDescription('Calculate transaction commissions')
        ->addArgument(self::FILE_ARG, InputArgument::REQUIRED, 'Transaction file');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileName = $input->getArgument(self::FILE_ARG);
        if (!file_exists($fileName)) {
            $output->writeln('<error>Transaction file not found</error>');

            return Command::FAILURE;
        }

        $entries = $this->transactionDataProvider->fromFile($fileName);
        foreach ($entries as $entry) {
            $commission = $this->commissionCalculatorService->calculate($entry);
            $output->writeln($commission);
        }

        return Command::SUCCESS;
    }
}
