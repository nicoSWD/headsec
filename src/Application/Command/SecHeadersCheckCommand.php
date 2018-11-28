<?php declare(strict_types=1);

/**
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/nicoSWD
 * @author   Nicolas Oelgart <nico@oelgart.com>
 */
namespace nicoSWD\SecHeaderCheck\Application\Command;

use nicoSWD\SecHeaderCheck\Domain\Headers\HeaderService;
use nicoSWD\SecHeaderCheck\Domain\ResultPrinter\ResultPrinterFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class SecHeadersCheckCommand extends Command
{
    /** @var HeaderService */
    private $headerService;
    /** @var ResultPrinterFactory */
    private $resultPrinterFactory;

    public function __construct(HeaderService $headerService, ResultPrinterFactory $resultPrinterFactory)
    {
        parent::__construct();

        $this->headerService = $headerService;
        $this->resultPrinterFactory = $resultPrinterFactory;
    }

    protected function configure()
    {
        $this->setName('nicoswd:security-header-check')
            ->setDescription('Check a site\'s security headers')
            ->addArgument('url', InputArgument::REQUIRED, 'URL to check')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format', 'json');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resultSet = $this->headerService->analise($input->getArgument('url'));
        $printer = $this->resultPrinterFactory->createFromFormat($input->getOption('format'));

        $output->writeln($printer->getOutput($resultSet));
    }
}
